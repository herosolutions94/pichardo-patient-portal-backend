<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class Register extends Controller
{
    public function index(){
        return view('admin.register');
    }
    public function store(Request $request){
        $this->validate($request,[
            'name'=>'required|max:255',
            'email'=>'required|email',
            'password'=>'required|confirmed',
        ]);
        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);
        auth()->attempt($request->only('email','password'));
        return redirect("admin/dashboard");
    }
}
