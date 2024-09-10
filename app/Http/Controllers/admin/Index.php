<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Permissions_admin_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class Index extends Controller
{
    // private $data=array();
    public function __construct()
    {
        // $this->middleware(['guest']);
        $this->data['site_settings']=$this->getSiteSettings();
    }
    public function register(){
        return view('admin.register',$this->data);
    }
    public function store(Request $request){
        $this->validate($request,[
            'site_username'=>'required',
            'site_password'=>'required',
        ]);
        Admin::create([
            'site_username'=>$request->site_username,
            'site_password'=>Hash::make($request->site_password),
        ]);
        // auth()->attempt($request->only('site_username','site_password'));
        return redirect("admin/login");
    }
    public function admin_login(){
        return view('admin.login',$this->data);
    }
    public function login(Request $request){
        $this->validate($request,[
            'site_username'=>'required',
            'site_password'=>'required',
        ]);
        $admin=Admin::where('site_username','=',$request->site_username)->first();
        if(!$admin){
            return back()
                ->with('error','Username does not exist.');
        }
        else{
            if(Hash::check($request->site_password,$admin->site_password)){
                if ($admin->id) {
                    $permissions=Permissions_admin_model::select(DB::raw('GROUP_CONCAT(permission_id) as perms'))->where('admin_id',$admin->id)
                    ->get()->first();
                    if($admin->site_status==0){
                        return redirect('admin/login')
                            ->with('error','Please contact administrator to activate your account!');
                    }
                    if($admin->site_admin_type != 'admin' && (empty($permissions) || $permissions->perms == '')){
                        return redirect('admin/login')
                            ->with('error','Insufficient permissions, please contact administrator!');
                    }
                    $request->session()->put('admin_type',$admin->site_admin_type);
                    $request->session()->put('PropertyLoginId',$admin->id);
                    if(!empty($permissions) && !empty($permissions->perms)){
                        $request->session()->put('permissions',explode(",",$permissions->perms));
                    }
                    
                    return redirect('admin/dashboard');
                }else{
                    return redirect('admin/login');
                }
            }
            else{
                return redirect('admin/login')
                ->with('error','Password is not correct!');
            }
        }

    }
    public function logout(){
        if(Session()->has("PropertyLoginId")){
            Session::pull('PropertyLoginId');
            return redirect("admin/login");
        }
        // auth()->logout();

    }
}
