<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Contact_model;

class Contact extends Controller
{
    public function index(){
        has_access(5);
        $this->data['rows']=Contact_model::orderBy('id', 'DESC')->get();
        return view('admin.contact',$this->data);
    }
    public function view($id){
        has_access(5);
        $contact=Contact_model::find($id);
        $contact->status=1;
        $contact->update();
        $this->data['row']=$contact;
        return view('admin.contact',$this->data);
    }
    public function delete($id){
        has_access(5);
        $faq = Contact_model::find($id);
        $faq->delete();
        return redirect('admin/contact/')
                ->with('error','Message deleted Successfully');
    }
}
