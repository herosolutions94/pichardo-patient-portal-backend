<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Team_model;
use Illuminate\Http\Request;

class Team extends Controller
{
    public function index(){
        has_access(14);
        $this->data['rows']=Team_model::orderBy('id', 'DESC')->get();
        return view('admin.team.index',$this->data);
    }
    public function add(Request $request){
        has_access(14);
        $input = $request->all();
        
        if($input){
            $data=array();
            if ($request->hasFile('image')) {

                $request->validate([
                    'image' => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                ]);
                $image=$request->file('image')->store('public/team/');
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
            $data['designation']=$input['designation'];
            $data['description']=$input['description'];
            // pr($data);
            $id = Team_model::create($data);
            return redirect('admin/team/')
                ->with('success','Content Updated Successfully');
        }
        $this->data['enable_editor']=true;
        return view('admin.team.index',$this->data);
    }
    public function edit(Request $request, $id){
        has_access(14);
        $service=Team_model::find($id);
        $input = $request->all();
        if($input){
            $data=array();
            if ($request->hasFile('image')) {

                $request->validate([
                    'image' => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                ]);
                $image=$request->file('image')->store('public/team/');
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
            $service->designation=$input['designation'];
            $service->description=$input['description'];
            // pr($data);
            $service->update();
            return redirect('admin/team/edit/'.$request->segment(4))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Team_model::find($id);
        $this->data['enable_editor']=true;
        return view('admin.team.index',$this->data);
    }
    public function delete($id){
        has_access(14);
        $service = Team_model::find($id);
        removeImage("team/".$service->image);
        $service->delete();
        return redirect('admin/team/')
                ->with('error','Content deleted Successfully');
    }
}