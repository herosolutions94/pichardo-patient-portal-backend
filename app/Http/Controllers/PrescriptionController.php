<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Prescription_model;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Admin;

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
    public function generate_prescription_pdf(Request $request, $id) {
        $res = array();
        $res['status'] = 0;
        $token = $request->input('token', null);
        $member = $this->authenticate_verify_token($token);
        if ($member) {
            if (intval($id) > 0 && $result = Prescription_model::with(['member_row', 'medications', 'requests'])->where('id', $id)
                ->where('mem_id', $member->id)->first()) {
                    $site_settings = Admin::where('id', 1)->first();
                    $data = [
                        'invoice_number' => $result->id,
                        'issue_date' => format_date($result->created_at,'m/d/Y'),
                        'member_name' => $result->member_row->mem_fullname,
                        'member_address' => $result->requests->address,
                        'member_email' => $result->member_row->mem_email,
                        'doctor_name' => $result->doctor_name,
                        'site_email' => $site_settings->site_email,
                        'medications' => [
                            ['name' => 'Aspirin', 'dosage' => '500mg', 'instructions' => 'Take one tablet daily'],
                            ['name' => 'Ibuprofen', 'dosage' => '200mg', 'instructions' => 'Take as needed for pain'],
                            // Add more medications as needed
                        ],
                    ];
                    // pr($data);

            }else {
                $this->data['message'] = 'Invalid Request';
            }
        }
        else {
            $this->data['not_logged_in'] = true;
        }
        $data = [
            'medications' => [
                ['name' => 'Aspirin', 'dosage' => '500mg', 'instructions' => 'Take one tablet daily'],
                ['name' => 'Ibuprofen', 'dosage' => '200mg', 'instructions' => 'Take as needed for pain'],
                // Add more medications as needed
            ],
        ];
    
       // Load PDF view with data
       $pdf = PDF::loadView('pdf.invoice', compact('data'));
       $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

       // Optionally set paper size, margins, etc.
       $pdf->setPaper('A4', 'portrait');

       // Return PDF as download
       return response()->streamDownload(function () use ($pdf) {
           echo $pdf->output();
       }, 'invoice.pdf', [
           'Content-Type' => 'application/pdf',
       ]);
    }
    
}