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

class Requests extends Controller
{
    public function index(){
        $this->data['rows'] = Request_model::with(['messages','messages.attachments','member_row'])->orderByDesc("id")->where('is_deleted', '0')->get();
        // pr($this->data['rows']->toArray());
        return view('admin.requests', $this->data);
    }
    public function view(Request $request,$id){
        $input=$request->all();
        if($id > 0 && $this->data['rows'] = Request_model::with(['messages', 'messages.attachments', 'member_row','invoice'])->where('id', $id)->where('is_deleted', '0')->first()){
            if($input && !empty($input['amount'])){
                Request_model::where('id',$this->data['rows']->id)->update(array('status'=>'prescription_in_progress','created_at'=>$this->data['rows']->created_at));
                Invoices_model::create(array(
                    'request_id'=>$this->data['rows']->id,
                    'status'=>'pending',
                    'amount'=>$input['amount']
                ));
                return redirect('admin/requests/view/' . $id)
                    ->with('success', 'invoice created successfully!');
            }
            return view('admin.requests', $this->data);
        }
        else{
            return redirect('admin/requests/')
                ->with('error', 'invalid request!');
        }
            
    }
    public function edit($id){
        if($id > 0 && $request = Request_model::with(['messages', 'messages.attachments', 'member_row'])->where('id', $id)->first()){
            $request->status = 'in_progress';
            $request->save();
            $this->data['rows'] = $request;
            return redirect('admin/requests');
        }else{
            return redirect('admin/requests/')
                ->with('error', 'invalid request!');
        }
    }
    public function post_comment(Request $request, $id)
    {
        if ($req = Request_model::with(['member_row'])->where('id', $id)->first()) {
            $input = $request->all();
            $request_data = [
                'comment' => 'required',
            ];
            $validator = Validator::make($input, $request_data);

            if ($validator->fails()) {
                return redirect('admin/requests/view/' . $id)
                    ->with('error', 'Error >>' . $validator->errors()->first());
            } else {
               $chat_id=Requests_chat_model::create([
                    'msg' => $request->comment,
                    'msg_by'=> 'admin',
                    'sender_id' => 1,
                    'receiver_id' => $req->mem_id,
                    'request_id' => $id
                ]);

                // Handle the attachments
                if ($request->hasFile('attachments')) {
                    foreach ($request->file('attachments') as $file) {
                        $fileName=$file->store('public/attachments');
                        $fileName = basename($fileName);
                        // pr($fileName);
                        Chat_attachments_model::create([
                            'chat_id' => $chat_id->id,
                            'file' => $fileName
                        ]);
                    }
                }

                return redirect('admin/requests/view/' . $id)
                    ->with('success', 'comment posted successfully!');
            }
        } else {
            return redirect('admin/requests/')
                ->with('error', 'invalid request!');
        }
    }

    public function delete($id) {
        $request = Request_model::find($id);
        
        if ($request && $request->is_deleted == '0') {
            // Set is_deleted to 1
            $request->is_deleted = '1'; 
            $request->save(); // Save the changes
            
            return redirect('admin/requests/')
                    ->with('success', 'Request deleted successfully');
        } else {
            return redirect('admin/requests/')
                    ->with('error', 'Request not found or already deleted');
        }
    }
    public function create_prescription(Request $request, $id)
    {
        $input = $request->all();
        if($req = Request_model::with(['member_row'])->where('id', $id)->first()){
            $memId = $req->mem_id;
            $newPrescription = Prescription_model::create([
                'mem_id' => $memId,
                'request_id' => $id,
                'doctor_name' => $input['doctor_name'],
                'doctor_note' => $input['doctor_note'] ?? '',
                'additional_note' => $input['additional_note'] ?? '',
            ]);
            
            $medications = $input['medication'];
            $dosages = $input['dosage'];
            $instructions = $input['instructions'];
    
            $medicationDetails = [];
            foreach ($medications as $index => $medication) {
                $medicationDetails[] = [
                    'prescription_id' => $newPrescription->id,
                    'medication' => $medication,
                    'dosage' => $dosages[$index],
                    'instructions' => $instructions[$index],
                ];
            }
            Medication_model::insert($medicationDetails);
            return redirect('admin/requests/view/' . $id)
                    ->with('success', 'Prescription and medications created successfully!');
        }else{
            return redirect('admin/requests/')
                    ->with('error', 'Member not found');
        }

        
    }
}