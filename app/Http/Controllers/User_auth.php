<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member_model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class User_auth extends Controller
{
    public function signup(Request $request)
    {
        $res = array();
        $res['status'] = 0;
        $input = $request->all();

        if ($input) {
            // if ($input['type'] == 'google') {
            //     $request_data = [
            //         'googleId' => 'required',
            //         'email' => 'required|email|unique:members,mem_email',
            //         'name' => 'required',
            //         // 'phone' => 'required|unique:members,mem_phone',
            //         'password' => 'required',
            //         'confirm_password' => 'required|same:password',
            //     ];
            // } else {
            $request_data = [
                'email' => 'required|email|unique:members,mem_email',
                'name' => 'required',
                // 'phone' => 'required|unique:members,mem_phone',
                'password' => 'required',
                'confirm_password' => 'required|same:password',
            ];
            // }

            $validator = Validator::make($input, $request_data);
            // json is null
            if ($validator->fails()) {
                $res['status'] = 0;
                $res['msg'] = convertArrayMessageToString($validator->errors()->all());
            } else {
                // if ($input['type'] == 'google' && !empty($input['googleId'])) {

                //     $member_count = Member_model::where(['mem_email' => $input['email'], 'googleId' => $input['googleId']])->get()->count();
                //     if (intval($member_count) > 0) {
                //         $res['google_status'] = 1;
                //         $res['status'] = 0;
                //         $res['msg'] = 'Authentication Error >> Google ID does not exist for your email. Please use email and password to login!';
                //         exit(json_encode($res));
                //     } else {
                //         $data = array(
                //             'mem_type' => 'member',
                //             'mem_image' => !empty($input['user_image']) ? write_image($input['user_image'], 'public/members/') : "",
                //             'googleId' => !empty($input['googleId']) ? $input['googleId'] : "",
                //             'mem_type' => 'member',
                //             'mem_fullname' => $input['name'],
                //             'mem_email' => $input['email'],
                //             // 'mem_phone'=>$input['phone'],
                //             'mem_password' => md5($input['password']),
                //             'otp' => random_int(100000, 999999),
                //             'otp_phone' => random_int(100000, 999999),
                //             'otp_expire' => date('Y-m-d H:i:s', strtotime('+3 minute')),
                //             'mem_status' => 1,
                //             'mem_email_verified' => 1,
                //             'mem_verified' => 1,
                //             'mem_username' => convertEmailToUsername($input['email'])
                //         );
                //     }
                // } else {
                $random_user_image = get_users_folder_random_image();
                $data = array(
                    'mem_type' => 'member',
                    'mem_fullname' => $input['name'],
                    'mem_email' => $input['email'],
                    // 'mem_phone'=>$input['phone'],
                    'mem_password' => md5($input['password']),
                    'otp' => random_int(100000, 999999),
                    // 'otp_phone' => random_int(100000, 999999),
                    'otp_expire' => date('Y-m-d H:i:s', strtotime('+3 minute')),
                    'mem_status' => 1,
                    'mem_username' => convertEmailToUsername($input['email']),
                    'mem_image' => $random_user_image
                );
                // }
                // pr($data);
                $mem_data = Member_model::create($data);
                $mem_id = $mem_data->id;
                if ($mem_id > 0) {
                    $token = $mem_id . "-" . $input['email'] . "-" . $data['mem_type'] . "-" . rand(99, 999);
                    $userToken = encrypt_string($token);
                    $token_array = array(
                        'mem_type' => $data['mem_type'],
                        'token' => $userToken,
                        'mem_id' => $mem_id,
                        'expiry_date' => date("Y-m-d", strtotime("6 months")),
                    );
                    DB::table('tokens')->insert($token_array);
                    // $email_data = array(
                    //     'email_to' => $data['mem_email'],
                    //     'email_to_name' => $data['mem_fullname'],
                    //     'email_from' => $this->data['site_settings']->site_noreply_email,
                    //     'email_from_name' => $this->data['site_settings']->site_name,
                    //     'subject' => 'Email Verification',
                    //     // 'link' => config('app.react_url') . "/verification/" . $userToken,
                    //     'code' => $data['otp'],
                    // );
                    // $email = send_email($email_data, 'account');
                    $res['expire_time'] = $data['otp_expire'];
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
                    $res['mem_type'] = $data['mem_type'];
                    $res['authToken'] = $userToken;
                    $res['status'] = 1;
                    $res['msg'] = 'You are register successfully. And We’ve sent a verify email to your email. If you don’t see the email, check your spam folder.';
                    // }

                } else {
                    $res['status'] = 0;
                    $res['msg'] = 'Technical problem!';
                }
            }
        }
        exit(json_encode($res));
    }

    public function verify_otp(Request $request)
    {
        $res = array();
        $res['status'] = 0;
        $res['email_verify'] = 0;
        $input = $request->all();
        $token = $request->input('token', null);
        $member = $this->authenticate_verify_token($token);
        // exit(json_encode($res));
        if (!empty($member)) {
            if ($input) {
                if (strtotime(date('Y-m-d H:i:s')) > strtotime(date('Y-m-d H:i:s', strtotime($member->otp_expire)))) {
                    $res['msg'] = "Your OTP has expired. Please resend a new OTP to verify your phone number. ";
                    $res['status'] = 0;
                    $res['expired'] = 1;
                    exit(json_encode($res));
                }
                if ($member->otp == $input['otp']) {
                    $member_row = Member_model::find($member->id);
                    $member_row->otp = '';
                    $member_row->mem_verified = 1;
                    $member_row->mem_email_verified = 1;
                    $member_row->mem_phone_verified = 1;
                    $member_row->mem_status = 1;
                    $member_row->update();
                    $mem_id = $member->id;
                    $token = $mem_id . "-" . $member->mem_email . "-" . $member->mem_type . "-" . rand(99, 999);
                    $userToken = encrypt_string($token);
                    $token_array = array(
                        'mem_type' => $member->mem_type,
                        'token' => $userToken,
                        'mem_id' => $mem_id,
                        'expiry_date' => date("Y-m-d", strtotime("6 months")),
                    );
                    DB::table('tokens')->insert($token_array);
                    $res['mem_type'] = $member->mem_type;
                    $res['authToken'] = $userToken;
                    $res['status'] = 1;
                    $res['msg'] = 'Your account has been verified successfully!';
                    exit(json_encode($res));
                } else {
                    $res['status'] = 0;
                    $res['msg'] = 'OTP is not correct!';
                }
            }
        } else {
            $res['status'] = 0;
            $res['msg'] = 'Something went wrong!';
        }

        exit(json_encode($res));
    }

    public function resend_email(Request $request)
    {
        $res = array();
        $res['status'] = 0;
        $token = $request->input('token', null);
        $member = $this->authenticate_verify_token($token);
        if (!empty($member)) {
            $memberRow = Member_model::where(['id' => $member->id])->get()->first();
            $otp = random_int(100000, 999999);
            $memberRow->otp = $otp;
            $memberRow->otp_expire = date('Y-m-d H:i:s', strtotime('+3 minute'));
            $memberRow->update();
            $token = $memberRow->id . "-" . $memberRow->mem_email . "-" . $memberRow->mem_type . "-" . rand(99, 999);
            $userToken = encrypt_string($token);
            $token_array = array(
                'mem_type' => $memberRow->mem_type,
                'token' => $userToken,
                'mem_id' => $memberRow->id,
                'expiry_date' => date("Y-m-d", strtotime("6 months")),
            );
            DB::table('tokens')->insert($token_array);
            $res['expire_time'] = $memberRow->otp_expire;
            // $email_data = array(
            //     'email_to' => $memberRow->mem_email,
            //     'email_to_name' => $memberRow->mem_fname,
            //     'email_from' => $this->data['site_settings']->site_noreply_email,
            //     'email_from_name' => $this->data['site_settings']->site_name,
            //     'subject' => 'Email Verification',
            //     // 'link'=>config('app.react_url')."/verification/".$userToken,
            //     'code' => $otp,
            // );
            // $email = send_email($email_data, 'account');
            // if($email){
            $res['msg'] = "Verification email has been sent with verification link to your email.";
            $res['status'] = 1;
            // }
            // else{
            //     $res['msg']="Email could not be sent!";
            // }

        } else {
            $res['member'] = null;
        }

        exit(json_encode($res));
    }

    public function login(Request $request)
    {
        $res = array();
        $res['status'] = 0;
        $res['google_status'] = 0;
        $input = $request->all();

        if ($input) {
            $request_data = [
                'email' => 'required|email',
                'password' => 'required',
            ];
            $validator = Validator::make($input, $request_data);
            // json is null
            if ($validator->fails()) {
                $res['status'] = 0;
                $res['msg'] = 'Error >>' . $validator->errors();
            } else {
                $member = Member_model::where(['mem_email' => $input['email'], 'mem_password' => md5($input['password'])])->get()->first();
                if (!empty($member)) {
                    if ($member->mem_status == 1) {

                        $mem_id = $member->id;
                        $token = $mem_id . "-" . $member->mem_email . "-" . $member->mem_type . "-" . rand(99, 999);
                        $userToken = encrypt_string($token);
                        $token_array = array(
                            'mem_type' => $member->mem_type,
                            'token' => $userToken,
                            'mem_id' => $mem_id,
                            'expiry_date' => date("Y-m-d", strtotime("6 months")),
                        );
                        DB::table('tokens')->insert($token_array);
                        $res['mem_type'] = $member->mem_type;
                        $res['authToken'] = $userToken;


                        if ($member->mem_verified == 1) {
                            $res['user_id'] = $member->id;
                            $res['status'] = 1;
                            $res['msg'] = 'Logged In successfully!';
                        } else {
                            $res['not_verified'] = true;

                            $res['user_id'] = $member->id;
                            $res['status'] = 1;
                            $res['msg'] = 'Logged In successfully!';
                        }
                    } else {
                        $res['msg'] = 'Your account is not active right now. Ask website admit to activate your account!';
                    }
                } else {
                    $res['msg'] = 'Email or password is not correct. Please try again!';
                }
            }
        }
        exit(json_encode($res));
    }

    public function forget_password(Request $request)
    {
        $res = array();
        $res['status'] = 0;
        $input = $request->all();
        if ($input) {
            $request_data = [
                'email' => 'required|email',
            ];
            $validator = Validator::make($input, $request_data);
            // json is null
            if ($validator->fails()) {
                $res['status'] = 0;
                $res['msg'] = convertArrayMessageToString($validator->errors()->all());
            } else {
                $member = Member_model::where(['mem_email' => $input['email']])->get()->first();
                if (!empty($member)) {
                    if ($member->mem_status == 1) {

                        $mem_id = $member->id;
                        $token = $mem_id . "-" . $member->mem_email . "-" . $member->mem_type . "-" . rand(99, 999);
                        $userToken = encrypt_string($token);
                        $token_array = array(
                            'mem_type' => $member->mem_type,
                            'token' => $userToken,
                            'mem_id' => $mem_id,
                            'expiry_date' => date("Y-m-d", strtotime("6 months")),
                        );
                        DB::table('tokens')->insert($token_array);
                        $verify_link = config('app.react_url') . "/reset-password/" . $userToken;
                        $res['verify_link'] = $verify_link;
                        // $email_data = array(
                        //     'email_to' => $member->mem_email,
                        //     'email_to_name' => $member->mem_fname,
                        //     'email_from' => $this->data['site_settings']->site_noreply_email,
                        //     'email_from_name' => $this->data['site_settings']->site_name,
                        //     'subject' => 'Password Reset Request',
                        //     'link' => $verify_link,
                        // );
                        // send_email($email_data, 'forget');
                        $res['status'] = 1;
                        $res['msg'] = 'Email has been sent to reset your password.';
                    } else {
                        $res['msg'] = 'Your account is not active right now. Ask website admit to activate your account!';
                    }
                } else {
                    $res['msg'] = 'Email does not exist.';
                }
            }
        }
        exit(json_encode($res));
    }

    public function reset_password(Request $request, $token)
    {
        // pr($token);
        $res = array();
        $res['status'] = 0;
        $member = $this->authenticate_verify_token($token);
        if ($member) {
            if ($member == 'expired') {
                $res['msg'] = "Link timeout. Send request again to reset your password.";
            } else {
                $input = $request->all();
                if ($input) {
                    $request_data = [
                        'password' => 'required',
                        'confirm_password' => 'required|same:password',
                    ];
                    $validator = Validator::make($input, $request_data);
                    // json is null
                    if ($validator->fails()) {
                        $res['status'] = 0;
                        $res['msg'] = convertArrayMessageToString($validator->errors()->all());
                    } else {
                        $member->mem_password = md5($input['password']);
                        $member->update();
                        $res['msg'] = "Password reset successfully!";
                        $res['status'] = 1;
                    }
                } else {
                    $res['msg'] = 'Nothing to reset';
                }
            }
        } else {
            $res['msg'] = 'This user does not exist';
            $res['status'] = 0;
        }

        exit(json_encode($res));
    }
}
