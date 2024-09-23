<?php

namespace App\Http\Controllers;

use Stripe\StripeClient;
use App\Models\Member_model;
use App\Models\Requests_chat_model;
use Cartalyst\Stripe\Stripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Earnings_model;
use App\Models\Withdrawal_methods_model;
use App\Models\Withdraw_requests_model;
use App\Models\Withdraw_request_details_model;
use App\Models\Booking_model;
use App\Models\Mem_payment_methods_model;
use App\Models\Msg_requests_model;
use App\Models\Request_model;
use App\Models\Transactions_model;
use Carbon\Carbon;
use Illuminate\Support\Str;


class Account extends Controller
{
    public function user_data(Request $request)
    {
        $res = array();
        $res['status'] = 0;
        $token = $request->input('token', null);
        $member = $this->authenticate_verify_token($token);
        if (!empty($member)) {
            // $member = Member_model::where(['id' => $member->id])->get()->first();
            $res['member'] = $member;
            $res['status'] = 1;
        } else {
            $res['member'] = null;
        }

        exit(json_encode($res));
    }

    public function update_profile(Request $request)
    {
        $res = array();
        $res['status'] = 0;
        $token = $request->input('token', null);
        $member = $this->authenticate_verify_token($token);
        if (!empty($member)) {
            $input = $request->all();
            $request_data = [
                'mem_fname'     => 'required',
                'mem_lname'     => 'required',
                'mem_address1'     => 'required',
                'phone'     => 'required',
                'gender'     => 'required',
                'preferred_pharmacy' => 'required',
                'allergies'     => 'required',
                'surgical_history'     => 'required',
                'pregnancy_status'     => 'required',
                'smoking_history'     => 'required',
                'identification_photo' => 'required',


            ];
            $validator = Validator::make($input, $request_data);
            // json is null
            if ($validator->fails()) {
                $res['status'] = 0;
                $res['msg'] = 'Error >>' . $validator->errors()->first();
            } else {
                $member = Member_model::where(['id' => $member->id])->get()->first();
                $input = $request->all();
                $member->mem_fullname = $input['mem_fname']." ".$input['mem_lname'];
                $member->mem_phone=$request->input('phone', null);
                $member->mem_address1=$request->input('mem_address1', null);
                $member->gender=$request->input('gender', null);
                $member->preferred_pharmacy=$request->input('preferred_pharmacy', null);
                $member->allergies=$request->input('allergies', null);
                $member->surgical_history=$request->input('surgical_history', null);
                $member->pregnancy_status=$request->input('pregnancy_status', null);
                $member->smoking_history=$request->input('smoking_history', null);
                $member->identification_photo=$request->input('identification_photo', null);

                if ($request->hasFile('member_image')) {
                    $member_image = $request->file('member_image')->store('public/members/');
                    if (!empty(basename($member_image))) {
                        $member->mem_image = basename($member_image);
                    }
                }
                $member->update();
                $res['msg'] = "Profile updated successfully!";
                $res['status'] = 1;
            }
        } else {
            $res['member'] = null;
        }

        exit(json_encode($res));
    }

    public function update_password(Request $request)
    {
        $res = array();
        $res['status'] = 0;
        $token = $request->input('token', null);
        $member = $this->authenticate_verify_token($token);
        if (!empty($member)) {
            $member = Member_model::where(['id' => $member->id])->get()->first();
            $input = $request->all();
            $request_data = [
                'old_password'     => 'required',
                'new_password'     => 'required',
                'confirm_password' => 'required|same:new_password',
            ];
            $validator = Validator::make($input, $request_data);
            // json is null
            if ($validator->fails()) {
                $res['status'] = 0;
                $res['msg'] = 'Error >>' . $validator->errors()->first();
            } else {
                $memberRow = Member_model::where(['mem_password' => md5($input['old_password'])])->get()->first();
                if (!empty($memberRow)) {
                    $member->mem_password = md5($input['new_password']);
                    $member->update();
                    $res['msg'] = "Password updated successfully!";
                    $res['status'] = 1;
                } else {
                    $res['msg'] = 'Old password does not match';
                }
            }
        } else {
            $res['member'] = null;
        }

        exit(json_encode($res));
    }

    
}
