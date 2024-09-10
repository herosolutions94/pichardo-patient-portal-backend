<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Faq_categories_model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Faq_categories extends Controller
{
    public function index(){
        has_access(20);
        $this->data['rows']=Faq_categories_model::orderBy('id', 'DESC')->get();
        return view('admin.faq.category',$this->data);
    }
    public function add(Request $request){
        has_access(20);
        $input = $request->all();
        if($input){
            $data=array();
            if(!empty($input['status'])){
                $data['status']=1;
            }
            else{
                $data['status']=0;
            }
            $data['name']=$input['name'];
            $data['slug'] = checkSlug(Str::slug($input['name'], '-'),'faq_categories');
            // pr($data);
            $id = Faq_categories_model::create($data);
            return redirect('admin/faq_categories/')
                ->with('success','Content Updated Successfully');
        }

        return view('admin.faq.category',$this->data);
    }
    public function edit(Request $request, $id){
        has_access(20);
        $faq_category=Faq_categories_model::find($id);
        $input = $request->all();
        if($input){
            $data=array();

            if(!empty($input['status'])){
                $faq_category->status=1;
            }
            else{
                $faq_category->status=0;
            }
            $faq_category->name=$input['name'];
            $faq_category->slug=checkSlug(Str::slug($input['name'], '-'),'faq_categories',$faq_category->id);

            // pr($data);
            $faq_category->update();
            return redirect('admin/faq_categories/edit/'.$request->segment(4))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Faq_categories_model::find($id);
        return view('admin.faq.category',$this->data);
    }
    public function delete($id){
        has_access(20);
        $faq_category = Faq_categories_model::find($id);
        $faq_category->delete();
        return redirect('admin/faq_categories/')
                ->with('error','Content deleted Successfully');
    }
}