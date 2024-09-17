<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Member_model;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function __construct()
    {
        $this->data['site_settings'] = $this->getSiteSettings();
        $this->data['enable_editor'] = false;
        $this->data['enable_listing_script'] = false;
        $this->data['all_pages'] = get_pages();
        // $this->checkAchPaymentStatus();
        // $this->checkLeasePayments();

    }


    function checkIfItemsExist($array1, $array2)
    {
        $intersection = array_intersect($array1, $array2);
        return !empty($intersection);
    }


    public function getSiteSettings()
    {
        return Admin::where('id', '=', 1)->first();
    }
    public function getMember($mem_id)
    {
        return Member_model::where(['id' => $mem_id])->get()->first();
    }
    // public function payment_methods_loop($payment_methods){
    //     $member_payment_methods_arr=array();
    //     foreach($payment_methods as $payment_method){
    //     	if($payment_method->payment_method=='credit-card'){
    //     		$payment_method->encoded_id=doEncode($payment_method->id);
    //         	$payment_method->payment_method_id=doDecode($payment_method->payment_method_id);
    //         	$payment_method->customer_id=doDecode($payment_method->customer_id);
    //         	$payment_method->card_number=doDecode($payment_method->card_number);
    //         	$payment_method->card_brand=doDecode($payment_method->card_brand);
    //         	$payment_method->card_exp_month=doDecode($payment_method->card_exp_month);
    //         	$payment_method->card_exp_year=doDecode($payment_method->card_exp_year);
    //         	$payment_method->setup_id=doDecode($payment_method->setup_id);
    //         	$payment_method->card_holder_name=ucfirst($payment_method->card_holder_name);
    //     	}

    //     	$member_payment_methods_arr[]=$payment_method;
    //     }
    //     return $member_payment_methods_arr;
    // }
    public function authenticate_verify_token($token)
    {
        // pr($userToken= DB::table('tokens')->where('token', $token)->first());
        if (!empty($token) && $userToken = DB::table('tokens')->where('token', $token)->first()) {
            $toke_expiry = date('Y-m-d', strtotime($userToken->expiry_date));
            if (strtotime($toke_expiry) <= strtotime(date('Y-m-d'))) {
                return false;
            } else {
                $token_parts = decrypt_string($userToken->token);
                $token_array = explode("-", $token_parts);
                $member = $this->getMember($token_array[0]);
                if (!empty($member)) {
                    // $member->payment_methods=$this->payment_methods_loop($member->payment_methods);
                    $mem_name = explode(" ", $member->mem_fullname);
                    $member->mem_fname = $mem_name[0];
                    $member->mem_lname = $mem_name[1];
                    return $member;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }
    public function authenticate_verify_email_token($token)
    {
        if (!empty($token) && $userToken = DB::table('tokens')->where('token', $token)->first()) {
            $toke_expiry = date('Y-m-d H:i:s', strtotime($userToken->expiry_date));
            if (strtotime($toke_expiry) <= strtotime(date('Y-m-d'))) {
                return false;
            } else {
                $token_parts = decrypt_string($userToken->token);
                $token_array = explode("-", $token_parts);
                $member = $this->getMember($token_array[0], $token_array[1]);
                if (!empty($member)) {
                    return $member;
                } else {
                    return false;
                }
            }
        }
    }
}
