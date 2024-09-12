<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Categories_model;
use App\Models\Blog_model;
use App\Models\Faq_categories_model;
use App\Models\Testimonial_model;
use App\Models\Team_model;
use App\Models\Services_model;
use App\Models\Top_searches_model;
use App\Models\Blog_categories_model;
use App\Models\Locations_model;
use App\Models\Faq_model;
use App\Models\Listings_model;
use App\Models\Msgs_model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContentPages extends Controller
{
    public function website_settings(Request $request){
        $token=$request->input('token', null);
        $member=$this->authenticate_verify_token($token);
        $countries=get_countries();
        $states=get_country_states(231);
        if(empty($header) || $header==null || $header=='null'){
            $output['not_logged']=true;
        }
        if(!empty($member) && $member!=false){
            $this->data['site_settings']->member=$member;
        }
        else{
            $this->data['site_settings']->member=null;
        }
        $output['site_settings']=$this->data['site_settings'];
        exit(json_encode($output));
    }
    
    public function member_settings(Request $request){
        $member_obj=(object)[];
        $token=$request->input('token', null);
        $member=$this->authenticate_verify_token($token);
        if(!empty($member)){
            
            $member->id_verification=$member->id_verification($member->mem_id_verification_id);
            $output['expire_time']=format_date($member->otp_expire,'Y-m-d H:i:s');
            $output['mem_image']=$member->mem_image;
            $output['mem_name']=$member->mem_fullname;
            $output['mem_email']=$member->mem_email;
            $output['unread_msgs']=Msgs_model::where(['status'=>'sent','receiver'=>$member->id])->where('message_by','!=',$member->id)->orderBy('created_at', 'desc')->get()->count();
            if(empty($member->mem_phone) || empty($member->mem_address1) || empty($member->mem_display_name) || empty($member->mem_phone) || empty($member->mem_fullname)){
                $member->complete_required=1;
            }
        }
        $output['member']=$member;

        exit(json_encode($output));
    }
    
    public function home_page(Request $request){
        $token=$request->input('token', null);
        $member=$this->authenticate_verify_token($token);
        $this->data['content']=get_page('home');     
        $this->data['page_title']=$this->data['content']['page_title'];
           
        $this->data['testimonials']=Testimonial_model::orderBy('id', 'DESC')->where('status',1)->get(); 
          
        exit(json_encode($this->data));
    }
    public function services_page(Request $request){
        $token=$request->input('token', null);
        $member=$this->authenticate_verify_token($token);
        $this->data['content']=get_page('services');     
        $this->data['page_title']=$this->data['content']['page_title'];   
        $this->data['services']=Services_model::orderBy('id', 'DESC')->where('status',1)->get();   
        exit(json_encode($this->data));
    }
    public function about_page(Request $request){
        $token=$request->input('token', null);
        $member=$this->authenticate_verify_token($token);
        $this->data['content']=get_page('about');     
        $this->data['page_title']=$this->data['content']['page_title'];    
        $this->data['team']=Team_model::orderBy('id', 'DESC')->where('status',1)->get();   
        exit(json_encode($this->data));
    }
    public function contact_page(Request $request){
        $token=$request->input('token', null);
        $member=$this->authenticate_verify_token($token);
        $this->data['content']=get_page('contact');     
        $this->data['page_title']=$this->data['content']['page_title'];      
        exit(json_encode($this->data));
    }
    public function privacy_policy_page(Request $request){
        $token=$request->input('token', null);
        $member=$this->authenticate_verify_token($token);
        $this->data['content']=get_page('privacy_policy');     
        $this->data['page_title']=$this->data['content']['page_title'];     
        exit(json_encode($this->data));
    }
    public function terms_conditions_page(Request $request){
        $token=$request->input('token', null);
        $member=$this->authenticate_verify_token($token);
        $this->data['content']=get_page('terms_conditions');     
        $this->data['page_title']=$this->data['content']['page_title'];     
        exit(json_encode($this->data));
    }
    public function signup_page(Request $request){
        $token=$request->input('token', null);
        $member=$this->authenticate_verify_token($token);
        $this->data['content']=get_page('signup');     
        $this->data['page_title']=$this->data['content']['page_title'];     
        exit(json_encode($this->data));
    }
    public function login_page(Request $request){
        $token=$request->input('token', null);
        $member=$this->authenticate_verify_token($token);
        $this->data['content']=get_page('login');     
        $this->data['page_title']=$this->data['content']['page_title'];     
        exit(json_encode($this->data));
    }
    public function forgot_page(Request $request){
        $token=$request->input('token', null);
        $member=$this->authenticate_verify_token($token);
        $this->data['content']=get_page('forgot');     
        $this->data['page_title']=$this->data['content']['page_title'];     
        exit(json_encode($this->data));
    }
    public function reset_page(Request $request){
        $token=$request->input('token', null);
        $member=$this->authenticate_verify_token($token);
        $this->data['content']=get_page('reset');     
        $this->data['page_title']=$this->data['content']['page_title'];     
        exit(json_encode($this->data));
    }

}