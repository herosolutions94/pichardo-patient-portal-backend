<?php

namespace App\Http\Controllers\admin;
use App\Models\Sitecontent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Pages extends Controller
{

    public function home(Request $request){
        has_access(12);
        $page=Sitecontent::where('ckey',$request->segment(3))->first();
        if(empty($page)){
            $page = new Sitecontent;
            $page->ckey=$request->segment(3);
            $page->code='';
            $page->save();
        }
        $input = $request->all();
        if($input){
            $content_row = unserialize($page->code);
            if(!is_array($content_row))
                $content_row = array();
            for ($i = 1; $i <= 8; $i++) {
                if ($request->hasFile('image'.$i)) {

                    $request->validate([
                        'image'.$i => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $image=$request->file('image'.$i)->store('public/images/');
                    if(!empty($image)){
                        $input['image'.$i]=basename($image);
                    }

                }

            }
            $data = serialize(array_merge($content_row, $input));
            // pr($input);
            $page->ckey=$request->segment(3);
            $page->code=$data;
            $page->save();
            return redirect('admin/pages/'.$request->segment(3))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Sitecontent::where('ckey',$request->segment(3))->first();
        $this->data['sitecontent']=unserialize($this->data['row']->code);
        return view('admin.website_pages.site_home',$this->data);
    }
    public function thankyou(Request $request){
        has_access(12);
        $page=Sitecontent::where('ckey',$request->segment(3))->first();
        if(empty($page)){
            $page = new Sitecontent;
            $page->ckey=$request->segment(3);
            $page->code='';
            $page->save();
        }
        $input = $request->all();
        if($input){
            $content_row = unserialize($page->code);
            if(!is_array($content_row))
                $content_row = array();
            $data = serialize(array_merge($content_row, $input));
            // pr($input);
            $page->ckey=$request->segment(3);
            $page->code=$data;
            $page->save();
            return redirect('admin/pages/'.$request->segment(3))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Sitecontent::where('ckey',$request->segment(3))->first();
        $this->data['sitecontent']=unserialize($this->data['row']->code);
        return view('admin.website_pages.site_thankyou',$this->data);
    }
    public function how_it_works(Request $request){
        has_access(12);
        $page=Sitecontent::where('ckey',$request->segment(3))->first();
        if(empty($page)){
            $page = new Sitecontent;
            $page->ckey=$request->segment(3);
            $page->code='';
            $page->save();
        }
        $input = $request->all();
        if($input){
            $content_row = unserialize($page->code);
            if(!is_array($content_row))
                $content_row = array();
            for ($i = 1; $i <= 14; $i++) {
                if ($request->hasFile('image'.$i)) {

                    $request->validate([
                        'image'.$i => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $image=$request->file('image'.$i)->store('public/images/');
                    if(!empty($image)){
                        $input['image'.$i]=basename($image);
                    }

                }

            }
            // pr($input);
            $data = serialize(array_merge($content_row, $input));
            $page->ckey=$request->segment(3);
            $page->code=$data;
            $page->save();
            return redirect('admin/pages/'.$request->segment(3))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Sitecontent::where('ckey',$request->segment(3))->first();;
        if(!empty($this->data['row']->code)){
            $this->data['sitecontent']=unserialize($this->data['row']->code);
        }
        else{
            $this->data['sitecontent']=array();
        }

        return view('admin.website_pages.site_how_it_works',$this->data);
    }
    public function help(Request $request){
        has_access(12);
        $page=Sitecontent::where('ckey',$request->segment(3))->first();
        if(empty($page)){
            $page = new Sitecontent;
            $page->ckey=$request->segment(3);
            $page->code='';
            $page->save();
        }
        $input = $request->all();
        if($input){

            $content_row = unserialize($page->code);
            if(!is_array($content_row))
                $content_row = array();
            for ($i = 12; $i <= 14; $i++) {
                if ($request->hasFile('image'.$i)) {

                    $request->validate([
                        'image'.$i => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $image=$request->file('image'.$i)->store('public/images/');
                    if(!empty($image)){
                        $input['image'.$i]=basename($image);
                    }

                }

            }

            $data = serialize(array_merge($content_row, $input));
            $page->ckey=$request->segment(3);
            $page->code=$data;
            $page->save();
            return redirect('admin/pages/'.$request->segment(3))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Sitecontent::where('ckey',$request->segment(3))->first();;
        if(!empty($this->data['row']->code)){
            $this->data['sitecontent']=unserialize($this->data['row']->code);
        }
        else{
            $this->data['sitecontent']=array();
        }
        return view('admin.website_pages.site_help',$this->data);
    }
    
    public function blog(Request $request){
        has_access(12);
        $page=Sitecontent::where('ckey',$request->segment(3))->first();
        if(empty($page)){
            $page = new Sitecontent;
            $page->ckey=$request->segment(3);
            $page->code='';
            $page->save();
        }
        $input = $request->all();
        if($input){

            $content_row = unserialize($page->code);
            if(!is_array($content_row))
                $content_row = array();
            for ($i = 12; $i <= 14; $i++) {
                if ($request->hasFile('image'.$i)) {

                    $request->validate([
                        'image'.$i => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $image=$request->file('image'.$i)->store('public/images/');
                    if(!empty($image)){
                        $input['image'.$i]=basename($image);
                    }

                }

            }
            $data = serialize(array_merge($content_row, $input));
            $page->ckey=$request->segment(3);
            $page->code=$data;
            $page->save();
            return redirect('admin/pages/'.$request->segment(3))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Sitecontent::where('ckey',$request->segment(3))->first();;
        if(!empty($this->data['row']->code)){
            $this->data['sitecontent']=unserialize($this->data['row']->code);
        }
        else{
            $this->data['sitecontent']=array();
        }
        return view('admin.website_pages.site_blog',$this->data);
    }
    public function about(Request $request){
        has_access(12);
        $page=Sitecontent::where('ckey',$request->segment(3))->first();
        if(empty($page)){
            $page = new Sitecontent;
            $page->ckey=$request->segment(3);
            $page->code='';
            $page->save();
        }
        $input = $request->all();
        if($input){

            $content_row = unserialize($page->code);
            if(!is_array($content_row))
                $content_row = array();
            for ($i = 1; $i <= 14; $i++) {
                if ($request->hasFile('image'.$i)) {

                    $request->validate([
                        'image'.$i => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $image=$request->file('image'.$i)->store('public/images/');
                    if(!empty($image)){
                        $input['image'.$i]=basename($image);
                    }

                }
                else{
                    // $input['image'.$i]='';
                }

            }
            $data = serialize(array_merge($content_row, $input));
            $page->ckey=$request->segment(3);
            $page->code=$data;
            $page->save();
            return redirect('admin/pages/'.$request->segment(3))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Sitecontent::where('ckey',$request->segment(3))->first();;
        if(!empty($this->data['row']->code)){
            $this->data['sitecontent']=unserialize($this->data['row']->code);
        }
        else{
            $this->data['sitecontent']=array();
        }
        $this->data['enable_editor']=true;
        return view('admin.website_pages.site_about',$this->data);
    }
    public function contact(Request $request){
        has_access(12);
        $page=Sitecontent::where('ckey',$request->segment(3))->first();
        if(empty($page)){
            $page = new Sitecontent;
            $page->ckey=$request->segment(3);
            $page->code='';
            $page->save();
        }
        $input = $request->all();
        if($input){

            $content_row = unserialize($page->code);
            if(!is_array($content_row))
                $content_row = array();
            for ($i = 1; $i <= 1; $i++) {
                if ($request->hasFile('image'.$i)) {

                    $request->validate([
                        'image'.$i => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $image=$request->file('image'.$i)->store('public/images/');
                    if(!empty($image)){
                        $input['image'.$i]=basename($image);
                    }

                }

            }
            $data = serialize(array_merge($content_row, $input));
            $page->ckey=$request->segment(3);
            $page->code=$data;
            $page->save();
            return redirect('admin/pages/'.$request->segment(3))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Sitecontent::where('ckey',$request->segment(3))->first();;
        if(!empty($this->data['row']->code)){
            $this->data['sitecontent']=unserialize($this->data['row']->code);
        }
        else{
            $this->data['sitecontent']=array();
        }
        $this->data['enable_editor']=true;
        return view('admin.website_pages.site_contact',$this->data);
    }
    public function privacy_policy(Request $request){
        has_access(12);
        $page=Sitecontent::where('ckey',$request->segment(3))->first();
        if(empty($page)){
            $page = new Sitecontent;
            $page->ckey=$request->segment(3);
            $page->code='';
            $page->save();
        }
        $input = $request->all();
        if($input){

            $content_row = unserialize($page->code);
            if(!is_array($content_row))
                $content_row = array();
            for ($i = 1; $i <= 1; $i++) {
                if ($request->hasFile('image'.$i)) {

                    $request->validate([
                        'image'.$i => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $image=$request->file('image'.$i)->store('public/images/');
                    if(!empty($image)){
                        $input['image'.$i]=basename($image);
                    }

                }
                else{
                    // $input['image'.$i]='';
                }

            }
            $data = serialize(array_merge($content_row, $input));
            $page->ckey=$request->segment(3);
            $page->code=$data;
            $page->save();
            return redirect('admin/pages/'.$request->segment(3))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Sitecontent::where('ckey',$request->segment(3))->first();;
        if(!empty($this->data['row']->code)){
            $this->data['sitecontent']=unserialize($this->data['row']->code);
        }
        else{
            $this->data['sitecontent']=array();
        }
        $this->data['enable_editor']=true;
        return view('admin.website_pages.site_privacy',$this->data);
    }
    public function terms_conditions(Request $request){
        has_access(12);
        $page=Sitecontent::where('ckey',$request->segment(3))->first();
        if(empty($page)){
            $page = new Sitecontent;
            $page->ckey=$request->segment(3);
            $page->code='';
            $page->save();
        }
        $input = $request->all();
        if($input){

            $content_row = unserialize($page->code);
            if(!is_array($content_row))
                $content_row = array();
            for ($i = 1; $i <= 1; $i++) {
                if ($request->hasFile('image'.$i)) {

                    $request->validate([
                        'image'.$i => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $image=$request->file('image'.$i)->store('public/images/');
                    if(!empty($image)){
                        $input['image'.$i]=basename($image);
                    }

                }
                else{
                    // $input['image'.$i]='';
                }

            }
            $data = serialize(array_merge($content_row, $input));
            $page->ckey=$request->segment(3);
            $page->code=$data;
            $page->save();
            return redirect('admin/pages/'.$request->segment(3))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Sitecontent::where('ckey',$request->segment(3))->first();;
        if(!empty($this->data['row']->code)){
            $this->data['sitecontent']=unserialize($this->data['row']->code);
        }
        else{
            $this->data['sitecontent']=array();
        }
        $this->data['enable_editor']=true;
        return view('admin.website_pages.site_terms_conditions',$this->data);
    }
    public function signup(Request $request){
        has_access(12);
        $page=Sitecontent::where('ckey',$request->segment(3))->first();
        if(empty($page)){
            $page = new Sitecontent;
            $page->ckey=$request->segment(3);
            $page->code='';
            $page->save();
        }
        $input = $request->all();
        if($input){

            $content_row = unserialize($page->code);
            if(!is_array($content_row))
                $content_row = array();
            for ($i = 1; $i <= 1; $i++) {
                if ($request->hasFile('image'.$i)) {

                    $request->validate([
                        'image'.$i => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $image=$request->file('image'.$i)->store('public/images/');
                    if(!empty($image)){
                        $input['image'.$i]=basename($image);
                    }

                }
                else{
                    // $input['image'.$i]='';
                }

            }
            $data = serialize(array_merge($content_row, $input));
            $page->ckey=$request->segment(3);
            $page->code=$data;
            $page->save();
            return redirect('admin/pages/'.$request->segment(3))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Sitecontent::where('ckey',$request->segment(3))->first();;
        if(!empty($this->data['row']->code)){
            $this->data['sitecontent']=unserialize($this->data['row']->code);
        }
        else{
            $this->data['sitecontent']=array();
        }
        $this->data['enable_editor']=true;
        return view('admin.website_pages.site_signup',$this->data);
    }
    public function login(Request $request){
        has_access(12);
        $page=Sitecontent::where('ckey',$request->segment(3))->first();
        if(empty($page)){
            $page = new Sitecontent;
            $page->ckey=$request->segment(3);
            $page->code='';
            $page->save();
        }
        $input = $request->all();
        if($input){

            $content_row = unserialize($page->code);
            if(!is_array($content_row))
                $content_row = array();
            for ($i = 1; $i <= 1; $i++) {
                if ($request->hasFile('image'.$i)) {

                    $request->validate([
                        'image'.$i => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $image=$request->file('image'.$i)->store('public/images/');
                    if(!empty($image)){
                        $input['image'.$i]=basename($image);
                    }

                }
                else{
                    // $input['image'.$i]='';
                }

            }
            $data = serialize(array_merge($content_row, $input));
            $page->ckey=$request->segment(3);
            $page->code=$data;
            $page->save();
            return redirect('admin/pages/'.$request->segment(3))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Sitecontent::where('ckey',$request->segment(3))->first();;
        if(!empty($this->data['row']->code)){
            $this->data['sitecontent']=unserialize($this->data['row']->code);
        }
        else{
            $this->data['sitecontent']=array();
        }
        $this->data['enable_editor']=true;
        return view('admin.website_pages.site_login',$this->data);
    }
    public function forgot(Request $request){
        has_access(12);
        $page=Sitecontent::where('ckey',$request->segment(3))->first();
        if(empty($page)){
            $page = new Sitecontent;
            $page->ckey=$request->segment(3);
            $page->code='';
            $page->save();
        }
        $input = $request->all();
        if($input){

            $content_row = unserialize($page->code);
            if(!is_array($content_row))
                $content_row = array();
            for ($i = 1; $i <= 1; $i++) {
                if ($request->hasFile('image'.$i)) {

                    $request->validate([
                        'image'.$i => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $image=$request->file('image'.$i)->store('public/images/');
                    if(!empty($image)){
                        $input['image'.$i]=basename($image);
                    }

                }
                else{
                    // $input['image'.$i]='';
                }

            }
            $data = serialize(array_merge($content_row, $input));
            $page->ckey=$request->segment(3);
            $page->code=$data;
            $page->save();
            return redirect('admin/pages/'.$request->segment(3))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Sitecontent::where('ckey',$request->segment(3))->first();;
        if(!empty($this->data['row']->code)){
            $this->data['sitecontent']=unserialize($this->data['row']->code);
        }
        else{
            $this->data['sitecontent']=array();
        }
        $this->data['enable_editor']=true;
        return view('admin.website_pages.site_forgot',$this->data);
    }
    public function reset(Request $request){
        has_access(12);
        $page=Sitecontent::where('ckey',$request->segment(3))->first();
        if(empty($page)){
            $page = new Sitecontent;
            $page->ckey=$request->segment(3);
            $page->code='';
            $page->save();
        }
        $input = $request->all();
        if($input){

            $content_row = unserialize($page->code);
            if(!is_array($content_row))
                $content_row = array();
            for ($i = 1; $i <= 1; $i++) {
                if ($request->hasFile('image'.$i)) {

                    $request->validate([
                        'image'.$i => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $image=$request->file('image'.$i)->store('public/images/');
                    if(!empty($image)){
                        $input['image'.$i]=basename($image);
                    }

                }
                else{
                    // $input['image'.$i]='';
                }

            }
            $data = serialize(array_merge($content_row, $input));
            $page->ckey=$request->segment(3);
            $page->code=$data;
            $page->save();
            return redirect('admin/pages/'.$request->segment(3))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Sitecontent::where('ckey',$request->segment(3))->first();;
        if(!empty($this->data['row']->code)){
            $this->data['sitecontent']=unserialize($this->data['row']->code);
        }
        else{
            $this->data['sitecontent']=array();
        }
        $this->data['enable_editor']=true;
        return view('admin.website_pages.site_reset',$this->data);
    }


}
