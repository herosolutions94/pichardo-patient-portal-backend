<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Preferred_pharmacy_model;
use Illuminate\Http\Request;

class Preferred_pharmacy extends Controller
{
    public function index(){
        has_access(13);
        $this->data['rows']=Preferred_pharmacy_model::orderBy('id', 'DESC')->get();
        return view('admin.preferred_pharmacy.index',$this->data);
    }
    public function add(Request $request){
        has_access(13);
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
            // pr($data);
            $id = Preferred_pharmacy_model::create($data);
            return redirect('admin/preferred_pharmacy/')
                ->with('success','Content Updated Successfully');
        }
        $this->data['enable_editor']=true;
        return view('admin.preferred_pharmacy.index',$this->data);
    }
    public function edit(Request $request, $id){
        has_access(13);
        $pharmacy=Preferred_pharmacy_model::find($id);
        $input = $request->all();
        if($input){
            $data=array();
            
            if(!empty($input['status'])){
                $pharmacy->status=1;
            }
            else{
                $pharmacy->status=0;
            }
            $pharmacy->name=$input['name'];
            // pr($data);
            $pharmacy->update();
            return redirect('admin/preferred_pharmacy/edit/'.$request->segment(4))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Preferred_pharmacy_model::find($id);
        $this->data['enable_editor']=true;
        return view('admin.preferred_pharmacy.index',$this->data);
    }
    public function delete($id){
        has_access(13);
        $pharmacy = Preferred_pharmacy_model::find($id);
        $pharmacy->delete();
        return redirect('admin/preferred_pharmacy/')
                ->with('error','Content deleted Successfully');
    }
}