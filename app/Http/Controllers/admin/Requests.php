<?php

namespace App\Http\Controllers\admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Request_model;
use App\Http\Controllers\Controller;

class Requests extends Controller
{
    public function index(){
        $this->data['rows'] = Request_model::with(['messages','messages.attachments','member_row:mem_fullname,mem_image'])->get();
        return view('admin.requests', $this->data);
    }
    // public function delete($id){
    //     has_access(13);
    //     $category = Request_model::find($id);
    //     removeImage("categories/".$category->image);
    //     $category->delete();
    //     return redirect('admin/requests/')
    //             ->with('error','Request deleted Successfully');
    // }
    // public function orderAll(Request $request)
    // {
    //     $rows = Request_model::all();
    //     foreach ($rows as $row) {
    //         $orderId = $request->input('orderid' . $row->id);
    //         if ($orderId !== null) {
    //             $row->order_no = $orderId;
    //             $row->save();
    //         }
    //     }
        
    //     return redirect('admin/requests/')
    //             ->with('success','Order updated Successfully');
    // }
}