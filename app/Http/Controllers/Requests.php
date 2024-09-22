<?php

namespace App\Http\Controllers;

use Stripe\StripeClient;
use App\Models\Requests_chat_model;
use App\Models\Chat_attachments_model;
use App\Models\Request_model;
use App\Models\Member_model;
use App\Models\Invoices_model;
use Cartalyst\Stripe\Stripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;


class Requests extends Controller
{
    public function user_request(Request $request)
    {
        $res = array();
        $res['status'] = 0;
        $input = $request->all();
        $token = $request->input('token', null);
        // pr($input);
        $member = $this->authenticate_verify_token($token);
        if (!empty($member)) {
            $input = $request->all();
            if ($input) {
                $request_data = [
                    'mem_address1' => 'required',
                    'preferred_pharmacy' => 'required',
                    'subject' => 'required',
                    'symptoms' => 'required',
                    'requested_medication' => 'required',
                    'file' => 'required',
                ];
                $custom_messages = [
                    'file.required' => 'Please upload your document!'
                ];

                $validator = Validator::make($input, $request_data, $custom_messages);
                // json is null
                if ($validator->fails()) {
                    $res['status'] = 0;
                    $res['msg'] = 'Error >>' . $validator->errors()->first();
                } else {
                    $data = array(
                        'mem_id' => $member->id,
                        'preferred_pharmacy' => $input['preferred_pharmacy'],
                        'address' => $input['mem_address1'],
                        'subject' => $input['subject'],
                        'symptoms' => $input['symptoms'],
                        'requested_medication' => $input['requested_medication'],
                        'document' => $input['file'],
                        'status' => 1,

                    );
                    // pr($data);
                    $createdRequest = Request_model::create($data);
                    $res['status'] = 1;
                    $res['encodedId'] = doEncode($createdRequest->id);
                    $res['msg'] = 'Your request created successfully!';
                }
            }
        } else {
            $res['msg'] = 'This user does not exist';
            $res['status'] = 0;
        }
        exit(json_encode($res));
    }

    public function user_all_request(Request $request)
    {
        $res = array();
        $res['status'] = 0;
        $token = $request->input('token', null);
        $member = $this->authenticate_verify_token($token);
        if (!empty($member)) {
            $all_requests = Request_model::where(['mem_id' => $member->id])->where('is_deleted', '0')->orderByDesc("id")->get();
            $open_requests = Request_model::where(['mem_id' => $member->id, 'status' => 0])->count();

            foreach($all_requests as $request){
                $request->encoded_id=doEncode($request->id);
                $request->created_on=format_date($request->created_at,'m/d/Y');
            }

            $res['requests'] = $all_requests;
            $res['count_open_requests'] = $open_requests;
            $res['status'] = 1;
        } else {
            $res['member'] = null;
        }

        exit(json_encode($res));
    }

    public function viewRequest(Request $request, $encodedId) {
        $this->data['status'] = 0;
            $token = $request->input('token', null);
            $member = $this->authenticate_verify_token($token);
            
            if ($member) {
                $this->data['member'] = $member;
                $id = doDecode($encodedId);
                
                if (intval($id) > 0 && $result = Request_model::with(['messages','messages.attachments','invoice'])->where('id', $id)
                ->where('mem_id', $member->id)->where('is_deleted', '0')
                ->first()) {
                    if(!empty($result->invoice)){
                        
                        $result->invoice->invoice_no=setInvoiceNo($result->invoice->id);
                        $result->invoice->created_date=format_date($result->invoice->created_at,'Y-m-d');
                    }
                    $result->encoded_id=doEncode($result->id);
                    $result->created_date=format_date($result->created_at,'Y-m-d');
                    $this->data['status'] = 1;
                    $this->data['request_data'] = $result;
                    $this->data['countries'] = get_countries(array());
                } else {
                    $this->data['message'] = 'Invalid Request';
                }
            } else {
                $this->data['not_logged_in'] = true;
            }
            exit(json_encode($this->data));
    }

    public function chat_requests(Request $request)
    {
        $res=array();
        $res['status']=0;
        $token = $request->input('token', null);
        $member = $this->authenticate_verify_token($token);
        $requestId = $request->input('request_id');
        $msg = $request->input('msg');
        $attachments = $request->input('attachments',null);
        // pr($attachmentFiles);
        if (!empty($member)) {
            if (intval($requestId) > 0 && $request_row = Request_model::where('id', $requestId)
                ->where('mem_id', $member->id)
                ->first()) {
                $input = $request->all();
                if ($input){
                    $request_data = [
                        'msg' => 'required|string'
                    ];
                    $validator = Validator::make($input, $request_data);
                   
                    if ($validator->fails()) {
                        $res['msg'] = 'Error >>' . $validator->errors()->first();
                    }else{
                        if(!empty($attachments)){
                            $attachments=json_decode($attachments);
                        }
                        $chatRequest = Requests_chat_model::create([
                            'request_id' => $request_row->id,
                            'receiver_id' => 1,
                            'msg' => $msg,
                            'sender_id' =>$member->id,
                            'msg_by'=>'user',
                            'status' => 'sent',
                        ]);
                        $id=$chatRequest->id;
                        if ($id) {
                            if(!empty($attachments)){
                                foreach($attachments as $attachment){
                                    Chat_attachments_model::create(array(
                                        'chat_id'=>$id,
                                        'file'=>$attachment
                                    ));
                                }
                            }
                            $res['status']=1;
                            $res['msg']='Sent successfully!';
                        } else {
                            $res['msg']='Technical problem!';
                        }
                    }
                }
            }else{
                $res['msg']='Invalid request!';
            }
        }else {
            $res['msg']='This user does not exist';
        }

        exit(json_encode($res));
    }
    public function create_payment_intent(Request $request){
        $token=$request->input('token', null);
        $member=$this->authenticate_verify_token($token);
        $res=array();
        $res['status']=0;
        if(!empty($member)){
            // if($member->mem_id_verified==1){
                $input = $request->all();
                // pr($input);
                $request_data = [
                    'fname' => 'required',
                    'lname' => 'required',
                    'email' => 'required',
                    'phone' => 'required',
                    'address' => 'required',
                    'city' => 'required',
                    'state_id' => 'required',
                    'country_id' => 'required',
                    'zip_code' => 'required',
                    'payment_method' => 'required',
                    'payment_method_id' => 'required',
                    'request_id' => 'required',
                ];
                $validator = Validator::make($input, $request_data);
                if ($validator->fails()) {
                    $res['status']=0;
                    $res['msg']='Error >>'.$validator->errors()->first();
                }
                else{
                    $request_id=doDecode($input['request_id']);
                    if(intval($request_id) > 0 && $request_row=Request_model::whereHas('invoice')->where('id',intval($request_id))->where('status','prescription_in_progress')->get()->first()){
                        $stripe = new StripeClient(
                            intval($this->data['site_settings']->site_sandbox) ==1 ? env('STRIPE_TESTING_SECRET_KEY') : env('STRIPE_LIVE_SECRET_KEY')
                        );
                        
                        try{
                            $amount=floatval($request_row->invoice->amount);
                            $cents = intval($amount * 100);
                            if(!empty($member->customer_id)){
                                $customer_id=$member->customer_id;
                            }
                            else{
                                $customer = $stripe->customers->create([
                                    'email' =>$input['email'],
                                    'name' =>$input['fname']." ".$input['lname'],
                                ]);
                                $customer_id=$customer->id;
                                Member_model::where('id',$member->id)->update(array('customer_id'=>$customer_id));
                            }
                            $intent= $stripe->paymentIntents->create([
                                'amount' => $cents,
                                'currency' => 'AUD',
                                'customer'=>$customer_id,
                                // 'payment_method' => $vals['payment_method'],
                                'setup_future_usage' => 'off_session',
                            ]);
                            $arr=array(
                                'paymentIntentId'=>$intent->id,
                                'client_secret'=>$intent->client_secret,
                                'customer'=>$customer_id,
                                'status'=>1
                            );
                            $res['arr']=$arr;
                            $res['status']=1;
                        }
                        catch(Exception $e) {
                            $arr['msg']="Error >> ".$e->getMessage();
                            $arr['status']=0;
                        }
                    }
                    else{
                        $res['msg']='Invalid request!';
                    }
                }
            // }
            // else{
            //     $res['msg']='Please verify your identity first to make booking!';
            // }
        }
        else{
            $res['msg']='Invalid user!';
        }
        exit(json_encode($res));
    }
    public function invoice_pay(Request $request){
        $token=$request->input('token', null);
        $member=$this->authenticate_verify_token($token);
        $res=array();
        $res['status']=0;
        if(!empty($member)){
                $input = $request->all();
                $request_data = [
                    'fname' => 'required',
                    'lname' => 'required',
                    'email' => 'required',
                    'phone' => 'required',
                    'address' => 'required',
                    'city' => 'required',
                    'state_id' => 'required',
                    'country_id' => 'required',
                    'zip_code' => 'required',
                    'payment_method' => 'required',
                    'payment_method_id' => 'required',
                    'request_id' => 'required',
                    'payment_intent' => 'required',
                    'customer_id' => 'required',
                ];
                $validator = Validator::make($input, $request_data);
                if ($validator->fails()) {
                    $res['status']=0;
                    $res['msg']='Error >>'.$validator->errors()->first();
                }
                else{
                    $request_id=doDecode($input['request_id']);
                    if(intval($request_id) > 0 && $request_row=Request_model::whereHas('invoice')->where('id',intval($request_id))->where('status','prescription_in_progress')->get()->first()){
                        if(!empty($request_row->invoice)){
                            Invoices_model::where('id',$request_row->invoice->id)->update(array(
                                'fname'=>$input['fname'],
                                'lname'=>$input['lname'],
                                'email'=>$input['email'],
                                'phone'=>$input['phone'],
                                'address'=>$input['address'],
                                'city'=>$input['city'],
                                'zip_code'=>$input['zip_code'],
                                'state'=>get_state_name($input['state_id']),
                                'country'=>get_country_name($input['country_id']),
                                'payment_method'=>$input['payment_method'],
                                'payment_method_id'=>$input['payment_method_id'],
                                'payment_intent'=>$input['payment_intent'],
                                'customer_id'=>$input['customer_id'],
                                'status'=>'paid'
                            ));
                            Request_model::where('id',$request_row->id)->update(array('status'=>'paid','created_at'=>$request_row->created_at));
                            $res['status']=1;
                            $res['msg']='Successfully paid!';
                        }
                        else{
                            $res['msg']='Invalid invoice!';
                        }
                    }
                    else{
                        $res['msg']='Invalid request!';
                    }
                }
        }
        else{
            $res['msg']='Invalid user!';
        }
        exit(json_encode($res));
    }
    
}
    
    
