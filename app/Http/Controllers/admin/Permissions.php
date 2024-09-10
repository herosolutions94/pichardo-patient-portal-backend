<?php

namespace App\Http\Controllers\admin;

use App\Models\Permissions_model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class Permissions extends Controller
{
    public function index(){
        $this->data['rows']=Permissions_model::orderByDesc("id")->get();
        return view('admin.permissions.index',$this->data);
    }
    public function add(Request $request){

        $input = $request->all();
        if($input){
            $request_data = [
                'permission' => 'required',
            ];
            $validator = Validator::make($input, $request_data);
            // json is null
            if ($validator->fails()) {
                return redirect('admin/permissions/add/')
                ->with('error','Error >>'.$validator->errors()->first());
            }
            else{
                $data=array('permission'=>$request->permission);
                $id = Permissions_model::create($data);
                return redirect('admin/permissions/')
                    ->with('success','Updated Successfully');
            }
        }
        $this->data['enable_editor']=true;
        return view('admin.permissions.index',$this->data);
    }
    
    public function edit(Request $request, $id){

        if($permissions=Permissions_model::where(['id' => $id])->get()->first()){
            $input = $request->all();
            if($input){
                
                $request_data = [
                    'permission' => 'required',
                ];
                $validator = Validator::make($input, $request_data);
                // json is null
                if ($validator->fails()) {
                    return redirect('admin/permissions/add/'.$permissions->id)
                        ->with('error','Error >>'.$validator->errors()->first());
                }
                else{
                    $data=array('permission'=>$request->permission);
                    Permissions_model::where('id',$permissions->id)->update($data);
                    return redirect('admin/permissions/edit/'.$request->segment(4))
                            ->with('success','Updated Successfully');
                    
                }
            }
            $this->data['row']=Permissions_model::where(['id' => $id,'site_admin_type' => 'subadmin'])->get()->first();
            return view('admin.permissions.index',$this->data);
        }
        else{
            return redirect('admin/permissions/')
                    ->with('error','Invalid admin!');
        }
    }
    public function delete($id){
        if($permissions = Permissions_model::where(['id' => $id])->get()->first()){
            $permissions->delete();
            return redirect('admin/permissions/')
                    ->with('error','SubAdmin deleted Successfully');
        }
        else{
            return redirect('admin/permissions/')
                    ->with('error','Invalid admin!');
        }
    }
}
