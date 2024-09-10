<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Categories_model;
use App\Models\Blog_model;
use App\Models\Faq_categories_model;
use App\Models\Testimonial_model;
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
            $member->id_verification=$member->id_verification($member->mem_id_verification_id);
            $member->unread_notifications=DB::table('notifications')->where(['mem_id'=> $member->id,'status'=>0])->get()->count();
            $member->notifications=get_notifications($member->id,2)['content'];
            if(empty($member->mem_phone) || empty($member->mem_address1) || empty($member->mem_display_name) || empty($member->mem_phone)){
                $member->complete_required=1;
            }
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
    public function thankyou_content(Request $request) {
        $token=$request->input('token', null);
        $member=$this->authenticate_verify_token($token);
        $this->data['content']=get_page('thankyou');     
        $this->data['page_title']=$this->data['content']['page_title'];
        $this->data['listings']=Listings_model::orderBy('id', 'DESC')->take(8)->get();
        foreach($this->data['listings'] as $listing){
            $listing->encoded_id = doEncode($listing->id);
            $listing->images_arr = $listing->images->take(4);
            $listing->price = formatAmount($listing->price);
            $listing->cat_name = !empty($listing->category_row) ? $listing->category_row->name : "N/A";
            if (!empty($listing->member_row)) {
                if (!empty($listing->member_row->mem_display_name)) {
                    $listing->mem_name = $listing->member_row->mem_display_name;
                } else {
                    $mem_name = $listing->member_row->mem_fullname;
                    $mem_name = explode(" ", $mem_name);
                    $listing->mem_name = $mem_name[0];
                }
                $listing->mem_buisness_phone = $listing->member_row->mem_buisness_phone;
                $listing->mem_ratings = formatNumber($listing->member_row->averageRating());
                $listing->total_reviews = $listing->member_row->total_reviews();
            }
        }
        exit(json_encode($this->data));
    }
    public function how_it_works_page(Request $request){
        $token=$request->input('token', null);
        $member=$this->authenticate_verify_token($token);
        $this->data['content']=get_page('how_it_works');     
        $this->data['page_title']=$this->data['content']['page_title'];      
        exit(json_encode($this->data));
    }
    public function about_page(Request $request){
        $token=$request->input('token', null);
        $member=$this->authenticate_verify_token($token);
        $this->data['content']=get_page('about');     
        $this->data['page_title']=$this->data['content']['page_title'];      
        exit(json_encode($this->data));
    }
    public function contact_page(Request $request){
        $token=$request->input('token', null);
        $member=$this->authenticate_verify_token($token);
        $this->data['content']=get_page('contact');     
        $this->data['page_title']=$this->data['content']['page_title'];      
        exit(json_encode($this->data));
    }

    public function blog_page(Request $request){
        $token=$request->input('token', null);
        $member=$this->authenticate_verify_token($token);
        $this->data['content']=get_page('blog');     
        $this->data['page_title']=$this->data['content']['page_title'];   
        $this->data['featured_blog_posts']=Blog_model::orderBy('id', 'DESC')->where('status',1)->where('featured',1)->get();  
        foreach($this->data['featured_blog_posts'] as $featured_blog_post){
            $featured_blog_post->cat_name=!empty($featured_blog_post->category_row) ? $featured_blog_post->category_row->name : '';
            $featured_blog_post->created_date=format_date($featured_blog_post->created_at,'d M, Y');
        }    
        $this->data['blog_categories']=Blog_categories_model::orderBy('id', 'DESC')->where('status',1)->has('blog_posts')->get();
        foreach ($this->data['blog_categories'] as $key => $blog_category) {
            $blog_category->blog_posts=$blog_category->blog_posts;
            foreach($blog_category->blog_posts as $blog_post){
                $blog_post->cat_name=!empty($blog_post->category_row) ? $blog_post->category_row->name : '';
                $blog_post->created_date=format_date($blog_post->created_at,'d M, Y');
            }
        } 
        exit(json_encode($this->data));
    }
    public function blog_details_page(Request $request,$slug){
        if(!empty($slug) && $this->data['blog_post']=Blog_model::orderBy('id', 'DESC')->where('status',1)->where('slug',$slug)->get()->first()){
            $this->data['content']=get_page('blog');    
            $this->data['page_title']=$this->data['blog_post']->title;
            $this->data['blog_post']->cat_name=!empty($this->data['blog_post']->category_row) ? $this->data['blog_post']->category_row->name : '';
            $this->data['blog_post']->created_date=format_date($this->data['blog_post']->created_at,'d M, Y');
        }
        exit(json_encode($this->data));
    }
    public function help_page(Request $request){
        $token=$request->input('token', null);
        $member=$this->authenticate_verify_token($token);
        $this->data['content']=get_page('help');     
        $this->data['page_title']=$this->data['content']['page_title'];  
        $this->data['faq_cats']=Faq_categories_model::orderBy('id', 'DESC')->where('status',1)->get();  
        foreach($this->data['faq_cats'] as $faq_cat){
            $faq_cat->faqs=$faq_cat->faqs;
            foreach($faq_cat->faqs as $faq){
                $faq->author_name=getFirstLetters($faq->author);
                $faq->created_date=time_ago($faq->created_at);
            }
        }    
        exit(json_encode($this->data));
    }
    public function help_search_page(Request $request){
        $token=$request->input('token', null);
        $member=$this->authenticate_verify_token($token);
        $search_query=$request->input('search_query', null);
        $this->data['content']=get_page('help');     
        $this->data['page_title']=!empty($search_query) ? "Searched for: ".$search_query : $this->data['content']['page_title'];  
        $searched_faqs_query=Faq_model::leftJoin('faq_categories', 'faq_categories.id', '=', 'faqs.category')->orderBy('faqs.id', 'DESC')->where('faqs.status',1)->where(function($query) use ($search_query)
        {
            if (!empty($search_query)) {
                $query->where('faqs.question','like',"%$search_query%");
                $query->orWhere('faqs.answer','like',"%$search_query%");
                $query->orWhere('faq_categories.name','like',"%$search_query%");
            }
        });  
        $p_sql = Str::replaceArray('?', $searched_faqs_query->getBindings(), $searched_faqs_query->toSql());
        $this->data['faqs']=$searched_faqs_query->get(['faqs.*']);
        $this->data['p_sql']=$p_sql;
        foreach($this->data['faqs'] as $faq){
            $faq->faq_category_row=$faq->faq_category_row;
            $faq->author_name=getFirstLetters($faq->author);
            $faq->created_date=time_ago($faq->created_at);
        }   
        $this->data['search_query']=$search_query; 
        exit(json_encode($this->data));
    }
    public function help_details_page(Request $request,$slug){
        if(!empty($slug) && $this->data['faq']=Faq_model::orderBy('id', 'DESC')->where('status',1)->where('slug',$slug)->get()->first()){
            $this->data['content']=get_page('help');   
            $this->data['page_title']=$this->data['faq']->question;
            $this->data['faq']->created_date=time_ago($this->data['faq']->created_at);
            $this->data['faq']->author_name=getFirstLetters($this->data['faq']->author);
            $this->data['faq']->created_date=time_ago($this->data['faq']->created_at);
        }
        $this->data['slug']=$slug;
        exit(json_encode($this->data));
    }
    public function category_help_page(Request $request,$slug){
        if(!empty($slug) && $this->data['faq_category']=Faq_categories_model::orderBy('id', 'DESC')->where('status',1)->where('slug',$slug)->get()->first()){
            $this->data['content']=get_page('help'); 
            $this->data['page_title']=$this->data['faq_category']->name;
            $this->data['faq_category']->faqs=$this->data['faq_category']->faqs;
            foreach($this->data['faq_category']->faqs as $faq){
                $faq->author_name=getFirstLetters($faq->author);
                $faq->created_date=time_ago($faq->created_at);
            }
        }
        $this->data['slug']=$slug;
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