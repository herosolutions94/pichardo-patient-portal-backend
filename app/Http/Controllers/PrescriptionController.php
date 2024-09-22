<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Prescription_model;
use App\Http\Controllers\Controller;

class PrescriptionController extends Controller
{
    public function prescription_all(Request $request){
        $res = array();
        $res['status'] = 0;
        $token = $request->input('token', null);
        $member = $this->authenticate_verify_token($token);
        if (!empty($member)) {
        $all_prescriptions = Prescription_model::with(['member_row', 'medications'])->orderByDesc('id')->where('is_deleted', '0')->get();
        
        foreach($all_prescriptions as $prescription){
            $prescription->encoded_id=doEncode($prescription->id);
            $prescription->created_on=format_date($prescription->created_at,'m/d/Y');
            $prescription->prescription_id = setInvoiceNo($prescription->id);
        }
        $res['prescriptions'] = $all_prescriptions;
            $res['status'] = 1;
        } else {
            $res['member'] = null;
        }

        exit(json_encode($res));
    }

    public function view_prescription(Request $request, $encodedId) {
        $this->data['status'] = 0;
            $token = $request->input('token', null);
            $member = $this->authenticate_verify_token($token);
            if ($member) {
                $this->data['member'] = $member;
                $id = doDecode($encodedId);
                
                if (intval($id) > 0 && $result = Prescription_model::with(['member_row', 'medications', 'requests'])->where('id', $id)
                ->where('mem_id', $member->id)->first()) {
                    $result->prescription_id = setInvoiceNo($result->$id);
                    $this->data['status'] = 1;
                    $this->data['prescription'] = $result;
                } else {
                    $this->data['message'] = 'Invalid Request';
                }
            } else {
                $this->data['not_logged_in'] = true;
            }
            exit(json_encode($this->data));
    }
}