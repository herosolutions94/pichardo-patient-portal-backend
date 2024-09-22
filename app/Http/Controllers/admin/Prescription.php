<?php

namespace App\Http\Controllers\admin;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\Prescription_model;
use App\Models\Medication_model;
use App\Models\Chat_attachments_model;
use App\Http\Controllers\Controller;

class Prescription extends Controller
{
    public function index(){
        $this->data['rows'] = Prescription_model::with(['member_row', 'medications'])->orderByDesc('id')->where('is_deleted', '0')->get();
        // pr($this->data['rows']);
        return view('admin.prescription', $this->data);
    }

    public function view($id){
        // pr($id);
        $this->data['rows'] = Prescription_model::with(['member_row', 'medications'])->where('id', $id)->first();
        return view('admin.prescription', $this->data);
    }

    public function delete($id) {
        $request = Prescription_model::find($id);
        
        if ($request && $request->is_deleted == '0') {
            // Set is_deleted to 1
            $request->is_deleted = '1'; 
            $request->save(); // Save the changes
            
            return redirect('admin/prescription/')
                    ->with('success', 'Prescription deleted successfully');
        } else {
            return redirect('admin/prescription/')
                    ->with('error', 'Prescription not found or already deleted');
        }
    }
}