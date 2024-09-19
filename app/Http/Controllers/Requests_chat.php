<?php

namespace App\Http\Controllers;

use Stripe\StripeClient;
use App\Models\Requests_chat_model;
use Cartalyst\Stripe\Stripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;


class Requests_chat extends Controller
{
    public function chat_requests(Request $request)
    {
        $token = $request->input('token', null);
        $member = $this->authenticate_verify_token($token);
        $requestId = $request->input('request_id');
        $msg = $request->input('msg');
        if (!empty($member)) {
            if(!empty($requestId)){
                $input = $request->all();
                
                if ($input){
                    $request_data = [
                        'msg' => 'required|string'
                    ];
                    $validator = Validator::make($input, $request_data);
                   
                    if ($validator->fails()) {
                        $res['msg'] = 'Error >>' . $validator->errors()->first();
                        // pr("error accur");
                    }else{
                        $chatRequest = Requests_chat_model::create([
                            'request_id' => $requestId,
                            'receiver_id' => 1,
                            'msg' => $msg,
                            'sender_id' =>$member->id,
                            'msg_by'=>'user',
                            'status' => 'sent',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                        if ($chatRequest) {
                            $res = [
                                'success' => true,
                                'message' => 'Message sent successfully',
                                'data' => $chatRequest,
                            ];
                        } else {
                            $res = [
                                'success' => false,
                                'message' => 'Failed to send message',
                            ];
                        }
                    }
                }
            }else{
                $res = [
                    'success' => false,
                    'message' => 'Invalid request',
                ];
            }
        }else {
            $res = [
                'success' => false,
                'message' => 'This user does not exist',
            ];
        }

        exit(json_encode($res));
    }
    
}
