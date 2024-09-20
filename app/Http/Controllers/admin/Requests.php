<?php

namespace App\Http\Controllers\admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Request_model;
use App\Http\Controllers\Controller;

class Requests extends Controller
{
    public function index(){
        $this->data['rows'] = Request_model::with(['messages','messages.attachments','member_row'])->get();
        // pr($this->data['rows']->toArray());
        return view('admin.requests', $this->data);
    }
    public function view($id){
        $this->data['rows'] = Request_model::with(['messages', 'messages.attachments', 'member_row'])
                                        ->where('id', $id)
                                        ->first();
        return view('admin.requests', $this->data);
    }
    public function edit($id){
        $request = Request_model::with(['messages', 'messages.attachments', 'member_row'])
                                        ->where('id', $id)
                                        ->first();
                                        if ($request) {
                                            $request->status = 'in_progress';
                                            $request->save();
                                        }
                                    
                                        // Pass the updated request data to the view
                                        $this->data['rows'] = $request;
                                        return redirect('admin/requests')->with('success', 'Status updated successfully.');
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