<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Blog_model;
use App\Models\Blog_categories_model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Blog extends Controller
{
    public function index(){
        has_access(17);
        $this->data['rows']=Blog_model::orderBy('id', 'DESC')->get();
        foreach($this->data['rows'] as $row){
            $row->cat_name=$row->category_row->name;
        }
        return view('admin.blog.index',$this->data);
    }
    public function add(Request $request){
        has_access(17);
        $input = $request->all();
        if($input){
            $data=array();
            if ($request->hasFile('image')) {

                $request->validate([
                    'image' => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                ]);
                $image=$request->file('image')->store('public/blog/');
                if(!empty(basename($image))){
                    generateThumbnail('blog',basename($image),'square','large');
                    $data['image']=basename($image);
                }

            }
            if(!empty($input['status'])){
                $data['status']=1;
            }
            else{
                $data['status']=0;
            }
            if(!empty($input['featured'])){
                $data['featured']=1;
            }
            else{
                $data['featured']=0;
            }
            if(!empty($input['popular'])){
                $data['popular']=1;
            }
            else{
                $data['popular']=0;
            }
            $data['meta_title']=$input['meta_title'];
            $data['meta_description']=$input['meta_description'];
            $data['meta_keywords']=$input['meta_keywords'];
            // $data['tags']=$input['tags'];
            $data['title']=$input['title'];
            $data['slug'] = checkSlug(Str::slug($data['title'], '-'),'blog');
            $data['detail']=$input['detail'];
            $data['category']=$input['category'];
            // pr($data);
            $id = Blog_model::create($data);
            return redirect('admin/blog/')
                ->with('success','Content Updated Successfully');
        }
        $this->data['enable_editor']=true;
        $this->data['categories']=Blog_categories_model::where('status', 1)->get();
        return view('admin.blog.index',$this->data);
    }
    public function edit(Request $request, $id){
        has_access(17);
        $blog=Blog_model::find($id);
        $input = $request->all();
        if($input){
            $data=array();
            if ($request->hasFile('image')) {

                $request->validate([
                    'image' => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                ]);
                $image=$request->file('image')->store('public/blog/');
                if(!empty($image)){
                    removeImage("blog/".$blog->image);
                    generateThumbnail('blog',basename($image),'square','large');
                    $blog->image=basename($image);
                }

            }
            if(!empty($input['status'])){
                $blog->status=1;
            }
            else{
                $blog->status=0;
            }
            if(!empty($input['featured'])){
                $blog->featured=1;
            }
            else{
                $blog->featured=0;
            }
            if(!empty($input['popular'])){
                $blog->popular=1;
            }
            else{
                $blog->popular=0;
            }
            $blog->meta_title=$input['meta_title'];
            $blog->meta_description=$input['meta_description'];
            $blog->meta_keywords=$input['meta_keywords'];
            // $blog->tags=$input['tags'];
            $blog->title=$input['title'];
            $blog->slug = checkSlug(Str::slug($blog->title, '-'),'blog',$blog->id);
            $blog->detail=$input['detail'];
            $blog->category=$input['category'];
            // pr($data);
            $blog->update();
            return redirect('admin/blog/edit/'.$request->segment(4))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Blog_model::find($id);
        $this->data['enable_editor']=true;
        $this->data['categories']=Blog_categories_model::where('status', 1)->get();
        return view('admin.blog.index',$this->data);
    }
    public function delete($id){
        has_access(17);
        $blog = Blog_model::find($id);
        removeImage("blog/".$blog->image);
        $blog->delete();
        return redirect('admin/blog/')
                ->with('error','Content deleted Successfully');
    }
}