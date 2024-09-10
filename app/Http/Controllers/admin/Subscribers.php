<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletter_model;

class Subscribers extends Controller
{
    public function index(){
        has_access(6);
        $rows=Newsletter_model::orderBy('id', 'DESC')->get();
        foreach($rows as $row){
            Newsletter_model::where('id',$row->id)->update(array('status'=>1));
        }
        $this->data['rows']=Newsletter_model::orderBy('id', 'DESC')->get();
        return view('admin.subscribers',$this->data);
    }
    public function delete($id){
        has_access(6);
        $faq = Newsletter_model::find($id);
        $faq->delete();
        return redirect('admin/subscribers/')
                ->with('error','Subscriber deleted Successfully');
    }
}
