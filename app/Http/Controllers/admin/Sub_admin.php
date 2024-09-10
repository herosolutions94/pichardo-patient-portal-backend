<?php

namespace App\Http\Controllers\admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Permissions_model;
use App\Models\Permissions_admin_model;

class Sub_admin extends Controller
{
    public function index(){
        has_access(2);
        $this->data['rows']=Admin::where('site_admin_type', 'subadmin')->orderByDesc("id")->get();
        return view('admin.sub_admin.index',$this->data);
    }
    public function add(Request $request){
        has_access(2);
        $input = $request->all();
        if($input){
            $request_data = [
                'site_username' => 'required|unique:admin,site_username',
                'site_admin_name' => 'required',
                'site_password' => 'required',
            ];
            $validator = Validator::make($input, $request_data);
            // json is null
            if ($validator->fails()) {
                return redirect('admin/sub-admin/add/')
                ->with('error','Error >>'.$validator->errors()->first());
            }
            else{
                $data=array();
                if(!empty($input['site_status'])){
                    $data['site_status']=1;
                }
                else{
                    $data['site_status']=0;
                }
                
                $data['site_admin_type']='subadmin';
                $data['site_username']=$input['site_username'];
                $data['site_admin_name']=$input['site_admin_name'];
                if(!empty($input['site_password'])){
                    $data['site_password']=Hash::make($input['site_password']);
                }
                // pr($data);
                $id = Admin::create($data);
                return redirect('admin/sub-admin/')
                    ->with('success','Updated Successfully');
            }
        }
        $this->data['enable_editor']=true;
        return view('admin.sub_admin.index',$this->data);
    }
    
    public function edit(Request $request, $id){
        has_access(2);
        if($sub_admin=Admin::where(['id' => $id,'site_admin_type' => 'subadmin'])->get()->first()){
            $input = $request->all();
            if($input){
                
                $request_data = [
                    'site_username' => 'required|unique:admin,site_username',
                    'site_admin_name' => 'required',
                    'site_password' => 'required',
                ];
                $validator = Validator::make($input, $request_data);
                // json is null
                if ($validator->fails()) {
                    return redirect('admin/sub-admin/add/'.$sub_admin->id)
                        ->with('error','Error >>'.$validator->errors()->first());
                }
                else{
                    
                        $data=array();
                        if(!empty($input['site_status'])){
                            $data['site_status']=1;
                        }
                        else{
                            $data['site_status']=0;
                        }
                        
                        $data['site_admin_type']='subadmin';
                        $data['site_username']=$input['site_username'];
                        $data['site_admin_name']=$input['site_admin_name'];
                        if(!empty($input['site_password'])){
                            $data['site_password']=Hash::make($input['site_password']);
                        }
                        // pr($data);
                        Admin::where('id',$sub_admin->id)->update($data);
                        return redirect('admin/sub-admin/edit/'.$request->segment(4))
                            ->with('success','Updated Successfully');
                    
                }
            }
            $this->data['row']=Admin::where(['id' => $id,'site_admin_type' => 'subadmin'])->get()->first();
            $this->data['enable_editor']=true;
            return view('admin.sub_admin.index',$this->data);
        }
        else{
            return redirect('admin/sub-admin/')
                    ->with('error','Invalid admin!');
        }
    }
    public function permissions(Request $request, $id){
        has_access(2);
        if($this->data['row']=Admin::where(['id' => $id,'site_admin_type' => 'subadmin'])->get()->first()){
            $input = $request->all();
            if($input){
                $permissions=$request->input('permissions', null);
                if(!empty($permissions)){
                    foreach($permissions as $permission){
                        Permissions_admin_model::create(array('admin_id'=>$this->data['row']->id,'permission_id'=>$permission));
                    }
                    return redirect('admin/sub-admin/permissions/'.$request->segment(4))
                            ->with('success','Updated Successfully');
                }
                else{
                    return redirect('admin/sub-admin/permissions/'.$request->segment(4))
                            ->with('error','Nothing to update!');
                }
            }
            $this->data['permissions']=Permissions_model::with('subPermissions')->orderByDesc("id")->get();
            return view('admin.sub_admin.permissions',$this->data);
        }
        else{
            return redirect('admin/sub-admin/')
                    ->with('error','Invalid admin!');
        }
    }
    public function delete($id){
        has_access(2);
        if($sub_admin = Admin::where(['id' => $id,'site_admin_type' => 'subadmin'])->get()->first()){
            $sub_admin->delete();
            return redirect('admin/sub-admin/')
                    ->with('error','SubAdmin deleted Successfully');
        }
        else{
            return redirect('admin/sub-admin/')
                    ->with('error','Invalid admin!');
        }
    }
}
