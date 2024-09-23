<?php

namespace App\Http\Controllers\admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Request_model;
use App\Models\Invoices_model;
use App\Models\Requests_chat_model;
use App\Models\Prescription_model;
use App\Models\Medication_model;
use App\Models\Chat_attachments_model;
use App\Http\Controllers\Controller;

class Invoice extends Controller
{
    public function index(){
        $this->data['rows'] = Invoices_model::with('member_row', 'member_data')->orderByDesc("id")->get();

        // dd($this->data['rows']->toArray());
        return view('admin.invoice', $this->data);
    }

    public function view($id){
        // pr($id);
        $this->data['rows'] = Invoices_model::with('member_row', 'member_data')->where('id', $id)->first();
        
        $this->data['invoice_id'] = setInvoiceNo($id);
        return view('admin.invoice', $this->data);
    }
}
