<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Services_model;
use Illuminate\Http\Request;

class Services extends Controller
{
    public function index(){
        has_access(16);
        $this->data['rows']=Services_model::orderBy('id', 'DESC')->get();
        return view('admin.services.index',$this->data);
    }
    public function add(Request $request){
        has_access(16);
        $input = $request->all();
        
        if($input){
            $data=array();
            if ($request->hasFile('image')) {

                $request->validate([
                    'image' => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                ]);
                $image=$request->file('image')->store('public/services/');
                if(!empty(basename($image))){
                    $data['image']=basename($image);
                }

            }
            if(!empty($input['status'])){
                $data['status']=1;
            }
            else{
                $data['status']=0;
            }
            $data['name']=$input['name'];
            $data['description']=$input['description'];
            // pr($data);
            $id = Services_model::create($data);
            return redirect('admin/services/')
                ->with('success','Content Updated Successfully');
        }
        $this->data['enable_editor']=true;
        return view('admin.services.index',$this->data);
    }
    public function edit(Request $request, $id){
        has_access(16);
        $service=Services_model::find($id);
        $input = $request->all();
        if($input){
            $data=array();
            if ($request->hasFile('image')) {

                $request->validate([
                    'image' => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                ]);
                $image=$request->file('image')->store('public/services/');
                if(!empty($image)){
                    $service->image=basename($image);
                }

            }
            if(!empty($input['status'])){
                $service->status=1;
            }
            else{
                $service->status=0;
            }
            $service->name=$input['name'];
            $service->description=$input['description'];
            // pr($data);
            $service->update();
            return redirect('admin/services/edit/'.$request->segment(4))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Services_model::find($id);
        $this->data['enable_editor']=true;
        return view('admin.services.index',$this->data);
    }
    public function delete($id){
        has_access(16);
        $service = Services_model::find($id);
        removeImage("services/".$service->image);
        $service->delete();
        return redirect('admin/services/')
                ->with('error','Content deleted Successfully');
    }
}