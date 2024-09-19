<?php

namespace App\Http\Controllers\admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class Dashboard extends Controller
{

    public function index(){
        $this->data['members']=table_count('members',array(),true);
       
        $this->data['contact']=table_count('contact',array(),true);
        $this->data['subscribers']=table_count('newsletter',array(),true);
       
        // dd(Auth::user()->email);
        return view('admin.dashboard',$this->data);

    }
    public function settings(){
        has_access(1);
        return view('admin.site_settings',$this->data);
    }
    public function change_password(Request $request){
        $admin=Admin::find(1);
        $input = $request->all();
        if($input){
            $this->validate($request, [
                'current_password'     => 'required',
                'new_password'     => 'required',
                'confirm_password' => 'required|same:new_password',
            ]);
            if(Hash::check($input['current_password'],$admin->site_password)){
                $admin->site_password=Hash::make($input['new_password']);
                $admin->save();
                return redirect('admin/change-password')
                ->with('success','Updated Successfully');
            }
            else{
                return redirect('admin/change-password')
                ->with('error','Current Password is not right!');
            }
        }
        return view('admin.change_password',$this->data);
    }

    public function settings_update(Request $request){
        
        $admin=Admin::find(1);
        
        if($request->hasFile('site_logo')){
            $request->validate([
                'site_logo' => 'mimes:png,jpg,jpeg,svg,gif,ico|max:2048'
            ]);
            $site_logo=$request->file('site_logo')->store('public/images/');
            if(!empty($site_logo)){
                if(!empty($this->data['site_settings']->site_logo)){
                    removeImage("images/".$this->data['site_settings']->site_logo);
                }

                $admin->site_logo=basename($site_logo);
            }

        }
        if($request->hasFile('site_icon')){
            $request->validate([
                'site_icon' => 'mimes:png,jpg,jpeg,svg,gif,ico|max:2048'
            ]);
            $site_icon=$request->file('site_icon')->store('public/images/');
            if(!empty($site_icon)){
                if(!empty($this->data['site_settings']->site_icon)){
                    removeImage("images/".$this->data['site_settings']->site_icon);
                }
                $admin->site_icon=basename($site_icon);
            }
        }
        if($request->hasFile('site_thumb')){
            $request->validate([
                'site_thumb' => 'mimes:png,jpg,jpeg,svg,gif,ico|max:2048'
            ]);
            $site_thumb=$request->file('site_thumb')->store('public/images/');
            if(!empty($site_thumb)){
                if(!empty($this->data['site_settings']->site_thumb)){
                    removeImage("images/".$this->data['site_settings']->site_thumb);
                }
                $admin->site_thumb=basename($site_thumb);
            }
        }
        if(!empty($request->site_stripe_type) && $request->site_stripe_type=='on'){
            $site_stripe_type=0;
        }
        else{
            $site_stripe_type=1;
        }
        if(!empty($request->site_sandbox) && $request->site_sandbox=='on'){
            $site_sandbox=0;
        }
        else{
            $site_sandbox=1;
        }
        // pr($site_stripe_type);
        $admin->site_domain=$request->site_domain;
        $admin->site_name=$request->site_name;
        $admin->site_phone=$request->site_phone;
        $admin->site_email=$request->site_email;
        $admin->site_noreply_email=$request->site_noreply_email;
        $admin->site_address=$request->site_address;
        $admin->site_about=$request->site_about;
        $admin->site_copyright=$request->site_copyright;
        $admin->site_meta_desc=$request->site_meta_desc;
        $admin->site_meta_keyword=$request->site_meta_keyword;
        $admin->site_facebook=$request->site_facebook;
        $admin->site_twitter=$request->site_twitter;
        $admin->site_linkedin=$request->site_linkedin;
        $admin->site_instagram=$request->site_instagram;
        $admin->site_discord=$request->site_discord;
        $admin->site_stripe_type=$site_stripe_type;
        $admin->site_stripe_testing_api_key=$request->site_stripe_testing_api_key;
        $admin->site_stripe_testing_secret_key=$request->site_stripe_testing_secret_key;
        $admin->site_stripe_live_api_key=$request->site_stripe_live_api_key;
        $admin->site_listing_fee=floatval($request->site_listing_fee);
        $admin->site_processing_fee=floatval($request->site_processing_fee);
        $admin->site_package_cost=floatval($request->site_package_cost);
        $admin->ach_merchant_id=$request->ach_merchant_id;
        $admin->ach_api_key=$request->ach_api_key;
        $admin->ach_site_id=$request->ach_site_id;
        $admin->site_walkscore_api_key=$request->site_walkscore_api_key;
        $admin->site_stripe_fee=$request->site_stripe_fee;
        $admin->site_stripe_flat_fee=$request->site_stripe_flat_fee;
        $admin->site_ach_fee=$request->site_ach_fee;
        $admin->site_ach_flat_fee=$request->site_ach_flat_fee;
        $admin->site_ach_threshold=$request->site_ach_threshold;
        $admin->site_lease_grace_period=$request->site_lease_grace_period;
        $admin->site_sandbox=$site_sandbox;
        $admin->generate_questions=$request->generate_questions;
        $admin->site_percentage=$request->site_percentage;



        $admin->save();
        return redirect('admin/site_settings')
                ->with('success','Updated Successfully');
    }

}