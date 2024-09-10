<?php

namespace App\Http\Controllers;


use Stripe\StripeClient;
use App\Models\Member_model;
use Cartalyst\Stripe\Stripe;
use Illuminate\Http\Request;
use App\Models\Contact_model;
use App\Models\Newsletter_model;
use App\Models\Mem_id_verifications_model;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class Ajax extends Controller
{
    public function upload_editor_image(Request $request)
    {
        // Validate the request
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Get the uploaded file
        $file = $request->file('upload');

        // Generate a unique filename
        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();

        // Store the file in the public directory (adjust the path as needed)
        $path = $file->storeAs('public/uploads', $filename);

        // Get the public URL of the stored file
        $url = asset('storage/uploads/' . $filename);

        // Return a JSON response with the URL
        return response()->json(['url' => $url]);
    }
    public function create_stripe_intent(Request $request){
        $res=array();
        $res['status']=0;
        $token=$request->input('token', null);
        $member=$this->authenticate_verify_token($token);
        $input = $request->all();
        if($input){
            $stripe = new StripeClient(
                $this->data['site_settings']->site_stripe_testing_secret_key
            );
            try{
                $amount = $input['amount'];
                if(!empty($input['expires_in'])){
                    // $expires_in=$input['expires_in'];
                    // $total=floatval($amount) * intval($expires_in);
                    $total=floatval($amount);
                }
                else{
                    $total=floatval($amount);
                }



                $cents = intval($total * 100);
                if(!empty($member->customer_id)){
                    $customer_id=$member->customer_id;
                }
                else{
                    $customer = $stripe->customers->create([
                        'email' =>$member->mem_email,
                        'name' =>$member->mem_fname." ".$member->mem_lname,
                        // 'address' => $stripe_adddress,
                    ]);
                    $customer_id=$customer->id;
                }

                $intent= $stripe->paymentIntents->create([
                    'amount' => $cents,
                    'currency' => 'usd',
                    'customer'=>$customer_id,
                    // 'payment_method' => $vals['payment_method'],
                    'setup_future_usage' => 'off_session',
                ]);
                $setupintent=$stripe->setupIntents->create([
                    'customer' => $customer_id,
                ]);
                $arr=array(
                        'paymentIntentId'=>$intent->id,
                        'setup_client_secret'=>$setupintent->client_secret,
                        'setup_intent_id'=>$setupintent->id,
                        'client_secret'=>$intent->client_secret,
                        'customer'=>$customer_id,
                        'status'=>1
                );
                $res['arr']=$arr;
                $res['status']=1;
                    // pr($arr);

            }
            catch(Exception $e) {
                $arr['msg']="Error >> ".$e->getMessage();
                $arr['status']=0;
            }
        }
        exit(json_encode($res));
    }
    
    public function get_data(){
        print_r(env('NODE_SOCKET'));
        print_r("hiii");
        $data=array(
            'mem_id'=>7,
            'name'=>"Abida"
        );
        $notify=sendPostRequest('https://staging.rentaro.com.au:3002/receive-notification/',$data);
        pr($notify);
        $thumb=generateThumbnail('members','FItXGuMegirvYSESVGiyyLflo7llVdZMwMSqvgGi.png');
        pr(get_users_folder_random_image());
        // phpinfo();
        
    }
    public function get_states($country_id){
        $output=array();
        if($country_id > 0 && $country_row=DB::table('countries')->where('id',$country_id)->first()){
            $output=get_country_states($country_row->id);
        }
        
        exit(json_encode($output));
    }


    public function save_image(Request $request){
        $token=$request->input('token', null);
        $member=$this->authenticate_verify_token($token);
        $res=array();
        $res['status']=0;
        if(!empty($member)){
            $input = $request->all();
            if ($request->hasFile('image')) {

                $request_data = [
                    'image' => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                ];
                $validator = Validator::make($input, $request_data);
                // json is null
                if ($validator->fails()) {
                    $res['status']=0;
                    $res['msg']='Error >>'.$validator->errors()->first();
                }
                else{
                    $image=$request->file('image')->store('public/members/');
                    if(!empty(basename($image))){
                        generateThumbnail('members',basename($image),'avatar','large');
                        $member_row=Member_model::find($member->id);
                        $member_row->mem_image=basename($image);
                        $member_row->update();
                        $res['status']=1;
                        $res['mem_image']=basename($image);
                    }
                    else{
                        $res['msg']="Something went wrong while uploading image. Please try again!";
                    }
                }


            }
            else{
                $res['image']="Only images are allowed to upload!";
            }
        }
        else{
            $res['status']=0;
            $res['msg']='Something went wrong!';
        }
        exit(json_encode($res));
    }
    public function save_verification_uploads(Request $request){
        $token=$request->input('token', null);
        $member=$this->authenticate_verify_token($token);
        $res=array();
        $res['status']=0;
        if(!empty($member) && $member!=false){
            $input = $request->all();
            if ($request->hasFile('image')) {

                $request_data = [
                    'image' => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                ];
                $validator = Validator::make($input, $request_data);
                // json is null
                if ($validator->fails()) {
                    $res['status']=0;
                    $res['msg']='Error >>'.$validator->errors()->first();
                }
                else{
                    $image=$request->file('image')->store('public/attachments/');
                    if(!empty(basename($image))){
                        $data=array();
                        if($input['type']=='selfie'){
                            $data['selfie']=basename($image);
                        }
                        if($input['type']=='cnic'){
                            $data['cnic']=basename($image);
                        }
                        if($input['type']=='cnic_selfie'){
                            $data['cnic_selfie']=basename($image);
                        }
                        $id_verification_row=$member->id_verification($member->mem_id_verification_id);
                        if(!empty($id_verification_row) && $id_verification_row->status=='in_progress'){
                            if(!empty($id_verification_row->cnic) && !empty($id_verification_row->selfie) && $input['type']=='cnic_selfie'){
                                $data['status']='requested';
                            }
                            Mem_id_verifications_model::where('id',$id_verification_row->id)->update($data);
                        }
                        else if(!empty($id_verification_row) && $id_verification_row->status=='verified'){
                            $res['msg']="Your ID verification is already verified!";
                            exit(json_encode($res));
                        }
                        else if(!empty($id_verification_row) && $id_verification_row->status=='requested'){
                            $res['msg']="Your ID verification request has already been sent to admin for approval!";
                            exit(json_encode($res));
                        }
                        else{
                            $data['mem_id']=$member->id;
                            $data['status']='in_progress';
                           
                            $id=Mem_id_verifications_model::create($data);
                            $mem_id_verification_id=$id->id;
                            Member_model::where('id',$member->id)->update(array('mem_id_verification_id'=>$mem_id_verification_id));
                        }
                        $memberRow=Member_model::where('id',$member->id)->get()->first();
                        $memberRow->id_verification=$memberRow->id_verification($memberRow->mem_id_verification_id);
                        $res['status']=1;
                        $res['memberRow']=$memberRow;
                    }
                    else{
                        $res['msg']="Something went wrong while uploading image. Please try again!";
                    }
                }


            }
            else{
                $res['image']="Only images are allowed to upload!";
            }
        }
        else{
            $res['status']=0;
            $res['msg']='Something went wrong!';
        }
        exit(json_encode($res));
    }
    public function upload_image(Request $request){
        $res=array();
        $res['status']=0;
            $input = $request->all();
            $res['input']=$input;
            if ($request->hasFile('image')) {
                $type=$input['type'];
                $file_type=$request->input('file_type', null);
                $res['type']='public/'.$type.'/';
                if($file_type=='files'):
                    $request_data = [
                        'image' => 'max:40000'
                    ];
                else:
                    $request_data = [
                        'image' => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ];
                    
                endif;
                $validator = Validator::make($input, $request_data);
                // json is null
                if ($validator->fails()) {
                    $res['status']=0;
                    $res['msg']='Error >>'.$validator->errors()->first();
                }
                else{
                    $uploadedFile = $request->file('image');
                    $image=$request->file('image')->store('public/'.$type.'/');
                    $filename = $uploadedFile->getClientOriginalName();
                    $res['image']=$image;
                    if(!empty(basename($image))){
                        generateThumbnail($type,basename($image),'square','large');
                        $res['status']=1;
                        $res['image_name']=basename($image);
                        $res['file_name']=$filename;
                        // $res['image_path']=storage_path('app/public/'.basename($image));
                    }
                    else{
                        $res['msg']="Something went wrong while uploading image. Please try again!";
                    }
                }


            }
            else{
                $res['msg']="Only images are allowed to upload!";
            }

        exit(json_encode($res));
    }
    public function upload_file(Request $request){
        $res=array();
        $res['status']=0;
            $input = $request->all();
            if ($request->hasFile('file')) {

                $request_data = [
                    'file' => 'max:40000'
                ];
                $validator = Validator::make($input, $request_data);
                // json is null
                if ($validator->fails()) {
                    $res['status']=0;
                    $res['msg']='Error >>'.$validator->errors()->first();
                }
                else{
                    $image=$request->file('file')->store('public/attachments/');
                    $res['file_name']=$_FILES['file']['name'];
                    $res['file']=$image;
                    if(!empty(basename($image))){
                        $uploadedFile = $request->file('file');
                        $filename = $uploadedFile->getClientOriginalName();
                        $res['status']=1;
                        $res['file_name']=basename($image);
                        $res['file_name_text']=$filename;
                    }
                    else{
                        $res['msg']="Something went wrong while uploading file. Please try again!";
                    }
                }


            }
            else{
                $res['msg']="Only images are allowed to upload!";
            }

        exit(json_encode($res));
    }
    public function newsletter(Request $request){
        $res=array();
        $res['status']=0;
        $input = $request->all();
        if($input){
            $request_data = [
                'email' => 'required|email|unique:newsletter,email',
            ];
            $validator = Validator::make($input, $request_data);
            // json is null
            if ($validator->fails()) {
                $res['status']=0;
                $res['msg']='Error >>'.$validator->errors()->first();
            }
            else{
                $data=array(
                    'email'=>$input['email'],
                    'status'=>0
                );
                Newsletter_model::create($data);
                $res['status']=1;
                $res['msg']='Subscribed successfully!';
            }

        }
        exit(json_encode($res));
    }
    public function contact_us(Request $request){
        $res=array();
        $res['status']=0;
        $input = $request->all();
        if($input){
            $request_data = [
                'email' => 'required|email',
                'fname' => 'required',
                'lname' => 'required',
                'phone' => 'required',
                // 'subject' => 'required',
                'comments' => 'required',
            ];
            $validator = Validator::make($input, $request_data);
            // json is null
            if ($validator->fails()) {
                $res['status']=0;
                $res['msg']='Error >>'.$validator->errors();
            }
            else{
                $data=array(
                    'name'=>$input['fname']." ".$input['lname'],
                    'email'=>$input['email'],
                    'phone'=>$input['phone'],
                    // 'subject'=>$input['subject'],
                    'message'=>$input['comments'],
                    'status'=>0
                );
                Contact_model::create($data);
                $res['status']=1;
                $res['msg']='Message sent successfully!';
            }

        }
        exit(json_encode($res));
    }
    public function reset_password(Request $request,$token){
        $res=array();
        $res['status']=0;
        $member=$this->authenticate_verify_token($token);
        if($member){
            if($member=='expired'){
                $res['msg']="Link timeout. Send request again to reset your password.";
            }
            else{
                $input = $request->all();
                if($input){
                    $request_data = [
                        'password' => 'required',
                        'confirm_password' => 'required|same:password',
                    ];
                    $validator = Validator::make($input, $request_data);
                    // json is null
                    if ($validator->fails()) {
                        $res['status']=0;
                        $res['msg']=convertArrayMessageToString($validator->errors()->all());
                    }
                    else{
                        $member->mem_password=md5($input['password']);
                        $member->update();
                        $res['msg']="Password reset successfully!";
                        $res['status']=1;
                    }
                }
                else{
                    $res['msg']='Nothing to reset';
                }
            }

        }
        else{
            $res['msg']='This user does not exist';
            $res['status']=0;
        }

        exit(json_encode($res));
    }
    public function forget_password(Request $request){
        $res=array();
        $res['status']=0;
        $input = $request->all();
        if($input){
            $request_data = [
                'email' => 'required|email',
            ];
            $validator = Validator::make($input, $request_data);
            // json is null
            if ($validator->fails()) {
                $res['status']=0;
                $res['msg']=convertArrayMessageToString($validator->errors()->all());
            }
            else{
                $member=Member_model::where(['mem_email' => $input['email']])->get()->first();
                if(!empty($member)){
                    if($member->mem_status==1){

                        $mem_id=$member->id;
                        $token=$mem_id."-".$member->mem_email."-".$member->mem_type."-".rand(99,999);
                        $userToken=encrypt_string($token);
                        $token_array=array(
                            'mem_type'=>$member->mem_type,
                            'token'=>$userToken,
                            'mem_id'=>$mem_id,
                            'expiry_date'=>date("Y-m-d", strtotime("6 months")),
                        );
                        DB::table('tokens')->insert($token_array);
                        $verify_link=config('app.react_url')."/reset-password/".$userToken;
                        $res['verify_link']=$verify_link;
                        // $email_data=array(
                        //     'email_to'=>$member->mem_email,
                        //     'email_to_name'=>$member->mem_fname,
                        //     'email_from'=>'noreply@liveloftus.com',
                        //     'email_from_name'=>$this->data['site_settings']->site_name,
                        //     'subject'=>'Password Reset Request',
                        //     'link'=>$verify_link,
                        //     // 'code'=>$data['otp'],
                        // );
                        // $email=send_email($email_data,'forget');
                        $res['status']=1;
                        $res['msg']='Email has been sent to reset your password.';
                    }
                    else{
                        $res['msg']='Your account is not active right now. Ask website admit to activate your account!';
                    }
                }
                else{
                    $res['msg']='Email does not exist.';
                }
            }
        }
        exit(json_encode($res));
    }
    public function signup(Request $request){
        $res=array();
        $res['status']=0;
        $input = $request->all();

        if($input){
            if($input['type']=='google'){
                $request_data = [
                    'googleId' => 'required',
                    'email' => 'required|email|unique:members,mem_email',
                    'name' => 'required',
                    // 'phone' => 'required|unique:members,mem_phone',
                    'password' => 'required',
                    'confirm_password' => 'required|same:password',
                ];
            }
            else{
                $request_data = [
                    'email' => 'required|email|unique:members,mem_email',
                    'name' => 'required',
                    // 'phone' => 'required|unique:members,mem_phone',
                    'password' => 'required',
                    'confirm_password' => 'required|same:password',
                ];
            }

            $validator = Validator::make($input, $request_data);
            // json is null
            if ($validator->fails()) {
                $res['status']=0;
                $res['msg']=convertArrayMessageToString($validator->errors()->all());
            }
            else{
                if($input['type']=='google' && !empty($input['googleId'])){

                    $member_count=Member_model::where(['mem_email'=>$input['email'],'googleId'=>$input['googleId']])->get()->count();
                    if(intval($member_count) > 0){
                        $res['google_status']=1;
                        $res['status']=0;
                        $res['msg']='Authentication Error >> Google ID does not exist for your email. Please use email and password to login!';
                        exit(json_encode($res));
                    }
                    else{
                        $data=array(
                            'mem_type'=>'member',
                            'mem_image'=>!empty($input['user_image']) ? write_image($input['user_image'],'public/members/') : "",
                            'googleId'=>!empty($input['googleId']) ? $input['googleId'] : "",
                            'mem_type'=>'member',
                            'mem_fullname'=>$input['name'],
                            'mem_email'=>$input['email'],
                            // 'mem_phone'=>$input['phone'],
                            'mem_password'=>md5($input['password']),
                            'otp'=>random_int(100000, 999999),
                            'otp_phone'=>random_int(100000, 999999),
                            'otp_expire'=>date('Y-m-d H:i:s', strtotime('+3 minute')),
                            'mem_status'=>1,
                            'mem_email_verified'=>1,
                            'mem_verified'=>1,
                            'mem_username'=>convertEmailToUsername($input['email'])
                        );
                    }

                }
                else{
                    $random_user_image=get_users_folder_random_image();
                    $data=array(
                        'mem_type'=>'member',
                        'mem_fullname'=>$input['name'],
                        'mem_email'=>$input['email'],
                        // 'mem_phone'=>$input['phone'],
                        'mem_password'=>md5($input['password']),
                        'otp'=>random_int(100000, 999999),
                        'otp_phone'=>random_int(100000, 999999),
                        'otp_expire'=>date('Y-m-d H:i:s', strtotime('+3 minute')),
                        'mem_status'=>1,
                        'mem_username'=>convertEmailToUsername($input['email']),
                        'mem_image'=>$random_user_image
                    );
                }
                // pr($data);
                $mem_data=Member_model::create($data);
                $mem_id=$mem_data->id;
                if($mem_id > 0){
                    $token=$mem_id."-".$input['email']."-".$data['mem_type']."-".rand(99,999);
                    $userToken=encrypt_string($token);
                    $token_array=array(
                        'mem_type'=>$data['mem_type'],
                        'token'=>$userToken,
                        'mem_id'=>$mem_id,
                        'expiry_date'=>date("Y-m-d", strtotime("6 months")),
                    );
                    DB::table('tokens')->insert($token_array);
                    $res['expire_time']=$data['otp_expire'];
                    // if(!empty($input['type']) && $input['type']=='google'){
                    //     $email_welcome_data=array(
                    //         'email_to'=>$data['mem_email'],
                    //         'email_to_name'=>$data['mem_fname'],
                    //         'email_from'=>'noreply@liveloftus.com',
                    //         'email_from_name'=>$this->data['site_settings']->site_name,
                    //         'subject'=>'Welcome to Loftus!',
                    //         // 'code'=>$data['otp'],
                    //     );
                    //     send_email($email_welcome_data,'welcome');
                    // }
                    // else if(!empty($input['type']) && $input['type']!='google'){
                    //     $email_data=array(
                    //         'email_to'=>$data['mem_email'],
                    //         'email_to_name'=>$data['mem_fname'],
                    //         'email_from'=>'noreply@liveloftus.com',
                    //         'email_from_name'=>$this->data['site_settings']->site_name,
                    //         'subject'=>'Email Verification',
                    //         'link'=>config('app.react_url')."/verification/".$userToken,
                    //         // 'code'=>$data['otp'],
                    //     );
                    //     send_email($email_data,'account');
                    // }

                    // $otp_req=sendOTP($data['mem_phone'],$data['otp_phone']);

                    // if(!empty($otp_req)){
                    //     $res['mem_type']=$data['mem_type'];
                    //     $res['authToken']=$userToken;
                    //     $res['status']=1;
                    //     $res['msg']='You are register successfully. And We’ve sent a verify email to your email and OTP code to your phone number. If you don’t see the email, check your spam folder.';
                    // }
                    // else{
                        $res['mem_type']=$data['mem_type'];
                        $res['authToken']=$userToken;
                        $res['status']=1;
                        $res['msg']='You are register successfully. And We’ve sent a verify email to your email. If you don’t see the email, check your spam folder.';
                    // }

                }
                else{
                    $res['status']=0;
                    $res['msg']='Technical problem!';
                }

            }

        }
        exit(json_encode($res));
    }
    public function login(Request $request){
        $res=array();
        $res['status']=0;
        $res['google_status']=0;
        $input = $request->all();

        if($input){
            if($input['type']=='google'){
                $request_data = [
                    'googleId' => 'required',
                    'name' => 'required',
                    'email' => 'required',
                ];
            }
            else{
                $request_data = [
                    'email' => 'required|email',
                    'password' => 'required',
                ];
            }

            $validator = Validator::make($input, $request_data);
            // json is null
            if ($validator->fails()) {
                $res['status']=0;
                $res['msg']='Error >>'.$validator->errors();
            }
            else{
                if($input['type']=='google'){
                    // $profileData=json_decode($input['profileObj']);
                    $member=Member_model::where(['mem_email' => $request->email])->get()->first();
                    if(!empty($member)){
                        if(empty($member->googleId)){
                            $res['google_status']=1;
                            $res['status']=0;
                            $res['msg']='Authentication Error >> Google ID does not exist for your email. Please use email and password to login!';
                            exit(json_encode($res));
                        }
                        else{
                            $mem_id=$member->id;
                            $token=$mem_id."-".$member->mem_email."-".$member->mem_type."-".rand(99,999);
                            $userToken=encrypt_string($token);
                            $token_array=array(
                                'mem_type'=>$member->mem_type,
                                'token'=>$userToken,
                                'mem_id'=>$mem_id,
                                'expiry_date'=>date("Y-m-d", strtotime("6 months")),
                            );
                            DB::table('tokens')->insert($token_array);
                            $res['mem_permissions']=$member->permissions;
                            if($member->mem_employee!=1 && empty($member->super_admin)){
                                $res['super_admin']=1;
                            }
                            else if($member->mem_employee==1 && !empty($member->super_admin) && $member->permissions=='admin'){
                                $res['sub_admin']=1;
                            }
                            $res['mem_type']=$member->mem_type;
                            $res['authToken']=$userToken;
                            if($member->mem_verified==1){
                                $res['status']=1;
                                $res['google_status']=1;
                                $res['user_id']=$member->id;
                                $res['msg']='Logged In successfully!';
                            }
                            else{
                                if(empty($member->mem_phone_verified) || $member->mem_phone_verified==0){
                                    $res['phone_verified']=1;
                                }
                                $res['status']=1;
                                $res['google_status']=1;
                                $res['not_verified']=1;
                                $res['msg']='Logged In successfully!';
                            }
                        }
                    }
                    else{
                        $res['status']=0;
                        $res['google_status']=1;
                        $res['msg']='This Google account is not registered in our system. Please proceed to the sign up page to register this account, or login with a different account.';
                        exit(json_encode($res));
                    }
                }
                else{
                    $member=Member_model::where(['mem_email' => $input['email'],'mem_password'=>md5($input['password'])])->get()->first();
                    if(!empty($member)){
                        if($member->mem_status==1){

                            $mem_id=$member->id;
                            $token=$mem_id."-".$member->mem_email."-".$member->mem_type."-".rand(99,999);
                            $userToken=encrypt_string($token);
                            $token_array=array(
                                'mem_type'=>$member->mem_type,
                                'token'=>$userToken,
                                'mem_id'=>$mem_id,
                                'expiry_date'=>date("Y-m-d", strtotime("6 months")),
                            );
                            DB::table('tokens')->insert($token_array);
                            $res['mem_type']=$member->mem_type;
                            $res['authToken']=$userToken;
                            $res['mem_permissions']=$member->permissions;
                            if($member->mem_employee!=1 && empty($member->super_admin)){
                                $res['super_admin']=1;
                            }
                            else if($member->mem_employee==1 && !empty($member->super_admin) && $member->permissions=='admin'){
                                $res['sub_admin']=1;
                            }
                            if($member->mem_employee==1 && !empty($member->super_admin)){
                                $branches=$member->emp_branches->pluck('branch')->toArray();
                                if(empty($branches)){
                                    $res['status']=0;
                                    $res['msg']="Hey there! It does not look like you have any branch level access. You should reach out to your friendly administrative user and ask them to grant you access. They'll be happy to help you out!";
                                    exit(json_encode($res));
                                }
                            }
                            if($member->mem_verified==1){
                                $res['user_id']=$member->id;
                                $res['status']=1;
                                $res['msg']='Logged In successfully!';
                            }
                            else{
                                if(empty($member->mem_phone_verified) || $member->mem_phone_verified==0){
                                    $res['phone_verified']=1;
                                }
                                $res['status']=1;
                                $res['not_verified']=1;
                                $res['msg']='Logged In successfully!';
                            }
                        }
                        else{
                            $res['msg']='Your account is not active right now. Ask website admit to activate your account!';
                        }
                    }
                    else{
                        $res['msg']='Email or password is not correct. Please try again!';
                    }
                }

            }
        }
        exit(json_encode($res));
    }
    public function verify_otp(Request $request){
        $res=array();
        $res['status']=0;
        $res['email_verify']=0;
        $input = $request->all();
        $token=$request->input('token', null);
        $member=$this->authenticate_verify_token($token);
        // exit(json_encode($res));
        if(!empty($member)){
            if($input){
                    if(strtotime(date('Y-m-d H:i:s')) > strtotime(date('Y-m-d H:i:s',strtotime($member->otp_expire)))){
                        $res['msg']="Your OTP has expired. Please resend a new OTP to verify your phone number. ";
                        $res['status']=0;
                        $res['expired']=1;
                        exit(json_encode($res));
                    }
                    if($member->otp==$input['otp']){
                        $member_row=Member_model::find($member->id);
                        $member_row->otp='';
                        $member_row->mem_verified=1;
                        $member_row->mem_email_verified=1;
                        $member_row->mem_phone_verified=1;
                        $member_row->mem_status=1;
                        $member_row->update();
                        $mem_id=$member->id;
                        $token=$mem_id."-".$member->mem_email."-".$member->mem_type."-".rand(99,999);
                        $userToken=encrypt_string($token);
                        $token_array=array(
                            'mem_type'=>$member->mem_type,
                            'token'=>$userToken,
                            'mem_id'=>$mem_id,
                            'expiry_date'=>date("Y-m-d", strtotime("6 months")),
                        );
                        DB::table('tokens')->insert($token_array);
                        $res['mem_type']=$member->mem_type;
                        $res['authToken']=$userToken;
                        $res['status']=1;
                        $res['msg']='Your account has been verified successfully!';
                    exit(json_encode($res));
                    }
                else{
                    $res['status']=0;
                    $res['msg']='OTP is not correct!';
                }


            }
        }
        else{
            $res['status']=0;
            $res['msg']='Something went wrong!';
        }

        exit(json_encode($res));
    }

}