<?php

namespace App\Http\Controllers;

use Stripe\StripeClient;
use App\Models\Member_model;
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

                // $member->mem_fullname = $input['mem_fname']." ".$input['mem_lname'];
                // $member->mem_phone=$request->input('phone', null);
                // $member->mem_address1=$request->input('mem_address1', null);
                // $member->gender=$request->input('gender', null);
                // $member->allergies=$request->input('allergies', null);
                // $member->surgical_history=$request->input('surgical_history', null);
                // $member->pregnancy_status=$request->input('pregnancy_status', null);
                // $member->smoking_history=$request->input('smoking_history', null);
                // $member->is_profile_completed=1;
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
                    Request_model::create($data);
                    $res['status'] = 1;
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
            $res['requests'] = $all_requests;
            $res['count_open_requests'] = $open_requests;
            $res['status'] = 1;
        } else {
            $res['member'] = null;
        }

        exit(json_encode($res));
    }


    // public function payment_methods(Request $request)
    // {
    //     $this->data['status'] = 0;
    //     $token = $request->input('token', null);
    //     $member = $this->authenticate_verify_token($token);
    //     $input = $request->all();
    //     if (!empty($member)) {
    //         $this->data['status'] = 1;
    //         $this->data['countries'] = get_countries();
    //         $this->data['payment_methods'] = $this->get_member_payment_methods($member);
    //     }
    //     exit(json_encode($this->data));
    // }
    // public function get_member_payment_methods($member, $payment_method_type = 'credit-card')
    // {
    //     $payment_methods = Mem_payment_methods_model::where('mem_id', $member->id)->where('payment_method', $payment_method_type)->orderBy('created_at', 'DESC')->get();
    //     $member_payment_methods_arr = array();
    //     foreach ($payment_methods as $payment_method) {
    //         if ($payment_method->payment_method == 'credit-card') {
    //             $payment_method->encoded_id = doEncode($payment_method->id);
    //             $payment_method->payment_method_id = doDecode($payment_method->payment_method_id);
    //             $payment_method->customer_id = doDecode($payment_method->customer_id);
    //             $payment_method->card_number = doDecode($payment_method->card_number);
    //             $payment_method->card_brand = doDecode($payment_method->card_brand);
    //             $payment_method->card_exp_month = doDecode($payment_method->card_exp_month);
    //             $payment_method->card_exp_year = doDecode($payment_method->card_exp_year);
    //             $payment_method->setup_id = doDecode($payment_method->setup_id);
    //             $payment_method->card_holder_name = ucfirst($payment_method->card_holder_name);
    //         }

    //         $member_payment_methods_arr[] = $payment_method;
    //     }
    //     return $member_payment_methods_arr;
    // }
    // public function create_payment_stripe_intent(Request $request)
    // {
    //     $res = array();
    //     $res['status'] = 0;
    //     $token = $request->input('token', null);
    //     $member = $this->authenticate_verify_token($token);
    //     $input = $request->all();
    //     if ($input) {
    //         $payment_method = $request->input('payment_method_id', null);
    //         $card_holder_name = $request->input('card_holder_name', null);
    //         $stripe = new StripeClient(
    //             intval($this->data['site_settings']->site_sandbox) == 0 ? env('STRIPE_LIVE_SECRET_KEY') : env('STRIPE_TESTING_SECRET_KEY')
    //         );
    //         try {

    //             if (!empty($member->customer_id)) {
    //                 $customer_id = $member->customer_id;
    //             } else {
    //                 $customer = $stripe->customers->create([
    //                     'email' => $member->mem_email,
    //                     'name' => !empty($card_holder_name) ? $card_holder_name : $member->mem_fname . " " . $member->mem_lname,
    //                     // 'address' => $stripe_adddress,
    //                 ]);
    //                 $customer_id = $customer->id;
    //                 Member_model::where('id', $member->id)->update(array('customer_id' => $customer_id));
    //             }

    //             $setupintent = $stripe->setupIntents->create([
    //                 'customer' => $customer_id,
    //             ]);
    //             $stripe->paymentMethods->attach(
    //                 $payment_method,
    //                 ['customer' => $customer_id]
    //             );
    //             $arr = array(
    //                 'setup_client_secret' => $setupintent->client_secret,
    //                 'setup_intent_id' => $setupintent->id,
    //                 'customer' => $customer_id,
    //                 'status' => 1
    //             );
    //             $res['arr'] = $arr;
    //             $res['status'] = 1;
    //             // pr($arr);

    //         } catch (Exception $e) {
    //             $arr['msg'] = "Error >> " . $e->getMessage();
    //             $arr['status'] = 0;
    //         }
    //     }
    //     exit(json_encode($res));
    // }
    // public function save_credit_card(Request $request)
    // {
    //     $token = $request->input('token', null);
    //     $member = $this->authenticate_verify_token($token);
    //     $input = $request->all();
    //     if ($input) {
    //         $request_data = [
    //             'payment_method_id' => 'required',
    //             'customer_id' => 'required',
    //             'card_holder_name' => 'required',
    //             'card_number' => 'required',
    //             'card_brand' => 'required',
    //             'card_exp_month' => 'required',
    //             'card_exp_year' => 'required',
    //             'setup_id' => 'required',
    //         ];
    //         $validator = Validator::make($input, $request_data);
    //         if ($validator->fails()) {
    //             $res['status'] = 0;
    //             $res['msg'] = 'Error >>' . $validator->errors();
    //         } else {
    //             $is_default = 0;
    //             $payment_methods = Mem_payment_methods_model::where('mem_id', $member->id)->count();
    //             if ($payment_methods <= 0) {
    //                 $is_default = 1;
    //             }
    //             Mem_payment_methods_model::create(array(
    //                 'mem_id' => $member->id,
    //                 'payment_method' => 'credit-card',
    //                 'payment_method_id' => doEncode($input['payment_method_id']),
    //                 'customer_id' => doEncode($input['customer_id']),
    //                 'card_holder_name' => $input['card_holder_name'],
    //                 'card_number' => doEncode($input['card_number']),
    //                 'card_brand' => doEncode($input['card_brand']),
    //                 'card_exp_month' => doEncode($input['card_exp_month']),
    //                 'card_exp_year' => doEncode($input['card_exp_year']),
    //                 'setup_id' => doEncode($input['setup_id']),
    //                 'is_default' => $is_default
    //             ));
    //             $res['status'] = 1;
    //             $res['msg'] = 'Card saved Successfully';
    //         }
    //     }
    //     exit(json_encode($res));
    // }
    // public function delete_payment_method(Request $request, $id)
    // {
    //     $res = array();
    //     $res['status'] = 0;
    //     $token = $request->input('token', null);
    //     $member = $this->authenticate_verify_token($token);
    //     if (!empty($member)) {
    //         if (intval($id) > 0 && $payment_method = Mem_payment_methods_model::where('id', $id)->where('mem_id', $member->id)->get()->first()) {
    //             if (!empty($payment_method->payment_method_id)) {
    //                 $stripe = new StripeClient(
    //                     intval($this->data['site_settings']->site_sandbox) == 0 ? env('STRIPE_LIVE_SECRET_KEY') : env('STRIPE_TESTING_SECRET_KEY')
    //                 );
    //                 try {
    //                     $stripe_payment_method = $stripe->paymentMethods->retrieve(
    //                         doDecode($payment_method->payment_method_id),
    //                         []
    //                     );
    //                     if (!empty($stripe_payment_method->id)) {
    //                         $stripe->paymentMethods->detach(
    //                             $stripe_payment_method->id,
    //                             []
    //                         );
    //                         $payment_method->delete();
    //                         $res['msg'] = 'Deleted successfully!';
    //                         $res['status'] = 1;
    //                     } else {
    //                         $res['msg'] = "Error >> your card details are invalid!";
    //                     }
    //                 } catch (Exception $e) {
    //                     $res['msg'] = "Error >> " . $e->getMessage();
    //                 }
    //             } else {
    //                 $res['msg'] = 'Stripe payment method ID is not existed!';
    //             }
    //         } else {
    //             $res['msg'] = 'Invalid request!';
    //         }
    //     } else {
    //         $res['msg'] = 'Invalid member!';
    //     }
    //     exit(json_encode($res));
    // }

    // public function deactivate_account(Request $request)
    // {
    //     $res = array();
    //     $res['status'] = 0;
    //     $token = $request->input('token', null);
    //     $member = $this->authenticate_verify_token($token);
    //     if (!empty($member)) {
    //         $member = Member_model::where(['id' => $member->id])->get()->first();
    //         $input = $request->all();
    //         $request_data = [
    //             'reason'     => 'required',
    //         ];
    //         $validator = Validator::make($input, $request_data);
    //         // json is null
    //         if ($validator->fails()) {
    //             $res['status'] = 0;
    //             $res['msg'] = 'Error >>' . $validator->errors()->first();
    //         } else {
    //             $member->is_deactivated = 1;
    //             $member->deactivated_reason = $input['reason'];
    //             $member->update();
    //             $res['msg'] = "Account Deactivated successfully!";
    //             $res['status'] = 1;
    //         }
    //     } else {
    //         $res['member'] = null;
    //     }

    //     exit(json_encode($res));
    // }

    // public function notifications(Request $request)
    // {
    //     $token = $request->input('token', null);
    //     $member = $this->authenticate_verify_token($token);
    //     if (!empty($member)) {
    //         $notifications = DB::table('notifications')->where(['mem_id' => $member->id, 'status' => 0])->get();
    //         if ($notifications->count() > 0) {
    //             foreach ($notifications as $notify) {
    //                 DB::table('notifications')->where('id', $notify->id)->update(array('status' => 1));
    //             }
    //         }
    //         $this->data['notifications'] = get_notifications($member->id);
    //         $this->data['page_title'] = 'Notifications';
    //     }
    //     exit(json_encode($this->data));
    // }
    // public function withdrawal_methods(Request $request)
    // {
    //     $token = $request->input('token', null);
    //     $member = $this->authenticate_verify_token($token);
    //     if (!empty($member)) {
    //         $this->data['countries'] = get_countries(array());
    //         $this->data['bank_methods'] = Withdrawal_methods_model::where('payment_method', 'bank-account')->where('mem_id', $member->id)->orderBy('created_at', 'desc')->get();
    //         $this->data['paypal_methods'] = Withdrawal_methods_model::where('payment_method', 'paypal')->where('mem_id', $member->id)->orderBy('created_at', 'desc')->get();
    //         $this->data['page_title'] = 'Withdrawal Methods';
    //     }
    //     exit(json_encode($this->data));
    // }
    // public function add_withdawal_method(Request $request)
    // {
    //     $res = array();
    //     $res['status'] = 0;
    //     $token = $request->input('token', null);
    //     $member = $this->authenticate_verify_token($token);
    //     if (!empty($member)) {
    //         $input = $request->all();
    //         if ($input['payment_method'] == 'bank-account'):
    //             $request_data = [
    //                 'bank_name' => 'required',
    //                 'account_title' => 'required',
    //                 'account_number' => 'required',
    //                 'swift_routing_no' => 'required',
    //                 'country' => 'required',
    //                 'state' => 'required',
    //                 'city' => 'required',
    //             ];
    //         else:
    //             $request_data = [
    //                 'paypal_email' => 'required',
    //             ];
    //         endif;
    //         $validator = Validator::make($input, $request_data);
    //         // json is null
    //         if ($validator->fails()) {
    //             $res['status'] = 0;
    //             $res['msg'] = 'Error >>' . $validator->errors()->first();
    //         } else {
    //             if ($input['payment_method'] == 'bank-account'):
    //                 $data = array(
    //                     'bank_name' => $request->bank_name,
    //                     'account_title' => $request->account_title,
    //                     'account_number' => $request->account_number,
    //                     'swift_routing_no' => $request->swift_routing_no,
    //                     'country' => get_state_name($request->country),
    //                     'state' => get_state_name($request->state),
    //                     'city' => $request->city,
    //                     'payment_method' => $request->payment_method
    //                 );
    //             else:
    //                 $data = array(
    //                     'paypal_email' => $request->paypal_email,
    //                     'payment_method' => $request->payment_method
    //                 );
    //             endif;
    //             $withdrawal_methods = Withdrawal_methods_model::where('mem_id', $member->id)->count();
    //             if ($withdrawal_methods <= 0) {
    //                 $data['is_default'] = 1;
    //             }
    //             $data['mem_id'] = $member->id;
    //             Withdrawal_methods_model::create($data);
    //             $res['status'] = 1;
    //             $res['msg'] = 'Added successfully!';
    //         }
    //     }
    //     exit(json_encode($res));
    // }
    // public function delete_withdrawal_method(Request $request, $id)
    // {
    //     $res = array();
    //     $res['status'] = 0;
    //     $token = $request->input('token', null);
    //     $member = $this->authenticate_verify_token($token);
    //     if (!empty($member)) {
    //         if (intval($id) > 0 && $withdraw_row = Withdrawal_methods_model::where('id', $id)->where('mem_id', $member->id)->get()->first()) {
    //             $withdraw_row->delete();
    //             $res['msg'] = 'Deleted successfully!';
    //             $res['status'] = 1;
    //         } else {
    //             $res['msg'] = 'Invalid request!';
    //         }
    //     } else {
    //         $res['msg'] = 'Invalid member!';
    //     }
    //     exit(json_encode($res));
    // }
    // public function save_withdrawal_request(Request $request)
    // {
    //     $res = array();
    //     $res['status'] = 0;
    //     $token = $request->input('token', null);
    //     $member = $this->authenticate_verify_token($token);
    //     if (!empty($member)) {
    //         $input = $request->all();
    //         if ($input['payment_method'] == 'bank-account'):
    //             $request_data = [
    //                 'account_details' => 'required',
    //             ];
    //         else:
    //             $request_data = [
    //                 'paypal_details' => 'required',
    //             ];
    //         endif;
    //         $validator = Validator::make($input, $request_data);
    //         // json is null
    //         if ($validator->fails()) {
    //             $res['status'] = 0;
    //             $res['msg'] = 'Error >>' . $validator->errors()->first();
    //         } else {
    //             $available_balance = $member->getAvailableBalance();
    //             if ($available_balance <= 0) {
    //                 $res['msg'] = 'Available balance is too low for this request!';
    //                 exit(json_encode($res));
    //             }
    //             if ($input['payment_method'] == 'bank-account'):
    //                 $data = array(
    //                     'account_details' => $request->account_details,
    //                     'amount' => $available_balance,
    //                     'status' => 'pending',
    //                     'mem_id' => $member->id
    //                 );
    //             else:
    //                 $data = array(
    //                     'account_details' => "Paypal Email: " . $request->paypal_details,
    //                     'amount' => $available_balance,
    //                     'status' => 'pending',
    //                     'mem_id' => $member->id
    //                 );
    //             endif;
    //             Earnings_model::create(array(
    //                 'mem_id' => $member->id,
    //                 'type' => 'debit',
    //                 'booking_id' => 0,
    //                 'amount' => $available_balance,
    //                 'status' => 'cleared'
    //             ));
    //             $withdraw_id = Withdraw_requests_model::create($data);
    //             $w_id = $withdraw_id->id;
    //             if ($w_id > 0) {
    //                 $earnings = Earnings_model::where('mem_id', $member->id)->where('type', 'credit')->where('status', 'cleared')->orderBy('created_at', 'desc')->get();
    //                 foreach ($earnings as $earning) {
    //                     Withdraw_request_details_model::create(array(
    //                         'w_id' => $w_id,
    //                         'earning_id' => $earning->id
    //                     ));
    //                 }
    //                 $res['status'] = 1;
    //                 $res['msg'] = 'Added successfully!';
    //             } else {
    //                 $res['msg'] = 'Technical problem!';
    //             }
    //         }
    //     } else {
    //         $res['msg'] = 'Invalid user!';
    //     }
    //     exit(json_encode($res));
    // }
    // public function earnings(Request $request)
    // {
    //     $token = $request->input('token', null);
    //     $member = $this->authenticate_verify_token($token);
    //     if (!empty($member)) {
    //         $earnings_3_days_before = Earnings_model::where('mem_id', $member->id)
    //             ->whereRaw('DATE(created_at) <= ?', [Carbon::now()->subDays(3)->toDateString()])
    //             ->orderBy('created_at', 'desc')->get();
    //         foreach ($earnings_3_days_before as $earnings_3_days) {
    //             Earnings_model::where('id', $earnings_3_days->id)->update(array('status' => 'cleared'));
    //         }
    //         // $p_sql = Str::replaceArray('?', $earnings_3_days_before->getBindings(), $earnings_3_days_before->toSql());
    //         $earnings = Earnings_model::where('mem_id', $member->id)->orderBy('id', 'desc')->get();;
    //         if ($earnings->count() > 0) {
    //             foreach ($earnings as $earning) {
    //                 $earning->invoice_no = $earning->booking_id > 0 ? setInvoiceNo($earning->booking_id) : "Debit Entry";
    //                 $earning->encoded_id = $earning->booking_id > 0 ? doEncode($earning->booking_id) : "";
    //                 $earning->member_row = $earning->member_row;
    //                 $earning->created_date = format_date($earning->created_at, 'M d, Y');
    //             }
    //         }
    //         $member_id = $member->id;
    //         $this->data['available_balance'] = $member->getAvailableBalance();
    //         $this->data['payouts'] = $member->getPayouts();
    //         $this->data['deliveries'] = Booking_model::with(['msgRequest', 'msgRequest.listing', 'msgRequest.listing.firstListingImage', 'msgRequest.msg.member:id,mem_fullname,mem_image,mem_address1'])->whereHas('msgRequest.listing', function ($query) use ($member_id) {
    //             $query->where('mem_id', $member_id);
    //         })->where('status', 'completed')->count();
    //         $this->data['withdraw_amount'] = Withdraw_requests_model::with('member_row')->where('mem_id', $member->id)->where('status', 'pending')->sum('amount');
    //         $this->data['earnings'] = $earnings;
    //         $this->data['page_title'] = 'Earnings';

    //         $this->data['bank_methods'] = Withdrawal_methods_model::where('payment_method', 'bank-account')->where('mem_id', $member->id)->orderBy('created_at', 'desc')->get();
    //         $this->data['paypal_methods'] = Withdrawal_methods_model::where('payment_method', 'paypal')->where('mem_id', $member->id)->orderBy('created_at', 'desc')->get();
    //     }
    //     exit(json_encode($this->data));
    // }
    // public function transactions(Request $request)
    // {
    //     $token = $request->input('token', null);
    //     $member = $this->authenticate_verify_token($token);
    //     if (!empty($member)) {
    //         $member_id = $member->id;
    //         $transactions = Transactions_model::with(['booking_row', 'extension_row'])
    //             ->where('mem_id', $member->id)
    //             ->withStatus(['paid', 'completed'])
    //             ->get();
    //         foreach ($transactions as $transaction) {
    //             if ($transaction->type == 'booking') {
    //                 $transaction->encoded_id = doEncode($transaction->booking_row->id);
    //                 $transaction->booking_id = setInvoiceNo($transaction->booking_row->id);
    //                 $transaction->type = 'Booking';
    //                 $transaction->status = $transaction->booking_row->status == 'paid' ? 'completed' : $transaction->booking_row->status;

    //                 $transaction->amount = $transaction->booking_row->booking_amount;
    //                 $transaction->total_amount = $transaction->booking_row->booking_amount + floatval($transaction->booking_row->service_fee);
    //                 $transaction->service_fee_amount = $transaction->booking_row->service_fee;
    //             } else if ($transaction->type == 'extension') {
    //                 $transaction->encoded_id = doEncode($transaction->extension_row->booking_row->id);
    //                 $transaction->booking_id = setInvoiceNo($transaction->extension_row->booking_row->id);
    //                 $transaction->type = 'Booking Extension';
    //                 $transaction->status = $transaction->extension_row->status == 'paid' ? 'completed' : $transaction->extension_row->status;

    //                 $transaction->amount = $transaction->extension_row->booking_amount;
    //                 $transaction->total_amount = $transaction->extension_row->booking_amount + floatval($transaction->extension_row->service_fee);
    //                 $transaction->service_fee_amount = $transaction->extension_row->service_fee;
    //             }

    //             $transaction->transaction_date = format_date($transaction->created_at, 'M d, Y');
    //         }
    //         $this->data['transactions'] = $transactions;
    //         $this->data['page_title'] = 'Transactions';
    //     }
    //     exit(json_encode($this->data));
    // }
    // public function delete_notification(Request $request, $id)
    // {
    //     $res = array();
    //     $res['status'] = 0;
    //     $token = $request->input('token', null);
    //     $member = $this->authenticate_verify_token($token);
    //     if (!empty($member)) {

    //         if (intval($id) > 0 && $notification = DB::table('notifications')->where(['mem_id' => $member->id, 'id' => $id])->get()->first()) {
    //             DB::table('notifications')->where('id', $id)->delete();
    //             $res['status'] = 1;
    //             $res['msg'] = 'Notification deleted successfully!';
    //         } else {
    //             $res['msg'] = 'Notification does not found!';
    //         }
    //     }
    //     exit(json_encode($res));
    // }
}
