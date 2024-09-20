<?php

namespace App\Http\Controllers;

use Stripe\StripeClient;
use App\Models\Requests_chat_model;
use App\Models\Chat_attachments_model;
use App\Models\Request_model;
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
            $all_requests = Request_model::where(['mem_id' => $member->id])->get();
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
                
                if (intval($id) > 0 && $result = Request_model::with(['messages','messages.attachments'])->where('id', $id)
                ->where('mem_id', $member->id)
                ->first()) {
                    $this->data['status'] = 1;
                    $this->data['request_data'] = $result;
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
            $res = [
                'success' => false,
                'message' => 'This user does not exist',
            ];
        }

        exit(json_encode($res));
    }
    
}
