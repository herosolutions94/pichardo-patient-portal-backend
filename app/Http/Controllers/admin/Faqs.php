<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Faq_model;
use App\Models\Faq_categories_model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Faqs extends Controller
{
    public function index(){
        has_access(19);
        $this->data['rows']=Faq_model::orderBy('id', 'DESC')->get();
        return view('admin.faq.index',$this->data);
    }
    public function add(Request $request){
        has_access(19);
        $input = $request->all();
        if($input){
            $data=array();
            if(!empty($input['status'])){
                $data['status']=1;
            }
            else{
                $data['status']=0;
            }
            $data['question']=$input['question'];
            $data['answer']=$input['answer'];
            $data['author']=$input['author'];
            $data['slug']=checkSlug(Str::slug($input['question'], '-'),'faqs');
            $data['category']=$input['category'];
            // pr($data);
            $id = Faq_model::create($data);
            return redirect('admin/faqs/')
                ->with('success','Content Updated Successfully');
        }
        $this->data['enable_editor']=true;
        $this->data['categories']=Faq_categories_model::where('status', 1)->get();
        return view('admin.faq.index',$this->data);
    }
    public function edit(Request $request, $id){
        has_access(19);
        $faq=Faq_model::find($id);
        $input = $request->all();
        if($input){
            $data=array();

            if(!empty($input['status'])){
                $faq->status=1;
            }
            else{
                $faq->status=0;
            }
            $faq->question=$input['question'];
            
            if($input['question']!=$faq->question){
                $faq->slug=checkSlug(Str::slug($input['question'], '-'),'faqs',$faq->id);
            }
            $faq->answer=$input['answer'];
            $faq->category=$input['category'];
            $faq->author=$input['author'];

            // pr($data);
            $faq->update();
            return redirect('admin/faqs/edit/'.$request->segment(4))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Faq_model::find($id);
        $this->data['categories']=Faq_categories_model::where('status', 1)->get();
        $this->data['enable_editor']=true;
        return view('admin.faq.index',$this->data);
    }
    public function delete($id){
        has_access(19);
        $faq = Faq_model::find($id);
        $faq->delete();
        return redirect('admin/faqs/')
                ->with('error','Content deleted Successfully');
    }
}