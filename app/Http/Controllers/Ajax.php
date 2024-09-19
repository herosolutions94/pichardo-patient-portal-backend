<?php

namespace App\Http\Controllers;


use Stripe\StripeClient;
use App\Models\Member_model;
use Cartalyst\Stripe\Stripe;
use Illuminate\Http\Request;
use App\Models\Contact_model;
use App\Models\Newsletter_model;
use App\Models\Mem_id_verifications_model;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class Ajax extends Controller
{
    public function upload_editor_image(Request $request)
    {
        // Validate the request
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Get the uploaded file
        $file = $request->file('upload');

        // Generate a unique filename
        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();

        // Store the file in the public directory (adjust the path as needed)
        $path = $file->storeAs('public/uploads', $filename);

        // Get the public URL of the stored file
        $url = asset('storage/uploads/' . $filename);

        // Return a JSON response with the URL
        return response()->json(['url' => $url]);
    }
    public function create_stripe_intent(Request $request)
    {
        $res = array();
        $res['status'] = 0;
        $token = $request->input('token', null);
        $member = $this->authenticate_verify_token($token);
        $input = $request->all();
        if ($input) {
            $stripe = new StripeClient(
                $this->data['site_settings']->site_stripe_testing_secret_key
            );
            try {
                $amount = $input['amount'];
                if (!empty($input['expires_in'])) {
                    // $expires_in=$input['expires_in'];
                    // $total=floatval($amount) * intval($expires_in);
                    $total = floatval($amount);
                } else {
                    $total = floatval($amount);
                }



                $cents = intval($total * 100);
                if (!empty($member->customer_id)) {
                    $customer_id = $member->customer_id;
                } else {
                    $customer = $stripe->customers->create([
                        'email' => $member->mem_email,
                        'name' => $member->mem_fname . " " . $member->mem_lname,
                        // 'address' => $stripe_adddress,
                    ]);
                    $customer_id = $customer->id;
                }

                $intent = $stripe->paymentIntents->create([
                    'amount' => $cents,
                    'currency' => 'usd',
                    'customer' => $customer_id,
                    // 'payment_method' => $vals['payment_method'],
                    'setup_future_usage' => 'off_session',
                ]);
                $setupintent = $stripe->setupIntents->create([
                    'customer' => $customer_id,
                ]);
                $arr = array(
                    'paymentIntentId' => $intent->id,
                    'setup_client_secret' => $setupintent->client_secret,
                    'setup_intent_id' => $setupintent->id,
                    'client_secret' => $intent->client_secret,
                    'customer' => $customer_id,
                    'status' => 1
                );
                $res['arr'] = $arr;
                $res['status'] = 1;
                // pr($arr);

            } catch (Exception $e) {
                $arr['msg'] = "Error >> " . $e->getMessage();
                $arr['status'] = 0;
            }
        }
        exit(json_encode($res));
    }

    public function get_data()
    {
        print_r(env('NODE_SOCKET'));
        print_r("hiii");
        $data = array(
            'mem_id' => 7,
            'name' => "Abida"
        );
        $notify = sendPostRequest('https://staging.rentaro.com.au:3002/receive-notification/', $data);
        pr($notify);
        $thumb = generateThumbnail('members', 'FItXGuMegirvYSESVGiyyLflo7llVdZMwMSqvgGi.png');
        pr(get_users_folder_random_image());
        // phpinfo();

    }

    public function get_states($country_id)
    {
        $output = array();
        if ($country_id > 0 && $country_row = DB::table('countries')->where('id', $country_id)->first()) {
            $output = get_country_states($country_row->id);
        }

        exit(json_encode($output));
    }

    public function save_image(Request $request)
    {
        $token = $request->input('token', null);
        $member = $this->authenticate_verify_token($token);
        $res = array();
        $res['status'] = 0;
        if (!empty($member)) {
            $input = $request->all();
            if ($request->hasFile('image')) {

                $request_data = [
                    'image' => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                ];
                $validator = Validator::make($input, $request_data);
                // json is null
                if ($validator->fails()) {
                    $res['status'] = 0;
                    $res['msg'] = 'Error >>' . $validator->errors()->first();
                } else {
                    $image = $request->file('image')->store('public/members/');
                    if (!empty(basename($image))) {
                        // generateThumbnail('members', basename($image), 'avatar', 'large');
                        $member_row = Member_model::find($member->id);
                        $member_row->mem_image = basename($image);
                        $member_row->update();
                        $res['status'] = 1;
                        $res['mem_image'] = basename($image);
                    } else {
                        $res['msg'] = "Something went wrong while uploading image. Please try again!";
                    }
                }
            } else {
                $res['image'] = "Only images are allowed to upload!";
            }
        } else {
            $res['status'] = 0;
            $res['msg'] = 'Something went wrong!';
        }
        exit(json_encode($res));
    }
    public function save_verification_uploads(Request $request)
    {
        $token = $request->input('token', null);
        $member = $this->authenticate_verify_token($token);
        $res = array();
        $res['status'] = 0;
        if (!empty($member) && $member != false) {
            $input = $request->all();
            if ($request->hasFile('image')) {

                $request_data = [
                    'image' => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                ];
                $validator = Validator::make($input, $request_data);
                // json is null
                if ($validator->fails()) {
                    $res['status'] = 0;
                    $res['msg'] = 'Error >>' . $validator->errors()->first();
                } else {
                    $image = $request->file('image')->store('public/attachments/');
                    if (!empty(basename($image))) {
                        $data = array();
                        if ($input['type'] == 'selfie') {
                            $data['selfie'] = basename($image);
                        }
                        if ($input['type'] == 'cnic') {
                            $data['cnic'] = basename($image);
                        }
                        if ($input['type'] == 'cnic_selfie') {
                            $data['cnic_selfie'] = basename($image);
                        }
                        $id_verification_row = $member->id_verification($member->mem_id_verification_id);
                        if (!empty($id_verification_row) && $id_verification_row->status == 'in_progress') {
                            if (!empty($id_verification_row->cnic) && !empty($id_verification_row->selfie) && $input['type'] == 'cnic_selfie') {
                                $data['status'] = 'requested';
                            }
                            Mem_id_verifications_model::where('id', $id_verification_row->id)->update($data);
                        } else if (!empty($id_verification_row) && $id_verification_row->status == 'verified') {
                            $res['msg'] = "Your ID verification is already verified!";
                            exit(json_encode($res));
                        } else if (!empty($id_verification_row) && $id_verification_row->status == 'requested') {
                            $res['msg'] = "Your ID verification request has already been sent to admin for approval!";
                            exit(json_encode($res));
                        } else {
                            $data['mem_id'] = $member->id;
                            $data['status'] = 'in_progress';

                            $id = Mem_id_verifications_model::create($data);
                            $mem_id_verification_id = $id->id;
                            Member_model::where('id', $member->id)->update(array('mem_id_verification_id' => $mem_id_verification_id));
                        }
                        $memberRow = Member_model::where('id', $member->id)->get()->first();
                        $memberRow->id_verification = $memberRow->id_verification($memberRow->mem_id_verification_id);
                        $res['status'] = 1;
                        $res['memberRow'] = $memberRow;
                    } else {
                        $res['msg'] = "Something went wrong while uploading image. Please try again!";
                    }
                }
            } else {
                $res['image'] = "Only images are allowed to upload!";
            }
        } else {
            $res['status'] = 0;
            $res['msg'] = 'Something went wrong!';
        }
        exit(json_encode($res));
    }
    public function upload_image(Request $request)
    {
        $res = array();
        $res['status'] = 0;
        $input = $request->all();
        $res['input'] = $input;
        if ($request->hasFile('image')) {
            $type = $input['type'];
            $file_type = $request->input('file_type', null);
            $res['type'] = 'public/' . $type . '/';
            if ($file_type == 'files'):
                $request_data = [
                    'image' => 'max:40000'
                ];
            else:
                $request_data = [
                    'image' => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                ];

            endif;
            $validator = Validator::make($input, $request_data);
            // json is null
            if ($validator->fails()) {
                $res['status'] = 0;
                $res['msg'] = 'Error >>' . $validator->errors()->first();
            } else {
                $uploadedFile = $request->file('image');
                $image = $request->file('image')->store('public/' . $type . '/');
                $filename = $uploadedFile->getClientOriginalName();
                $res['image'] = $image;
                if (!empty(basename($image))) {
                    // generateThumbnail($type, basename($image), 'square', 'large');
                    $res['status'] = 1;
                    $res['image_name'] = basename($image);
                    $res['file_name'] = $filename;
                    // $res['image_path']=storage_path('app/public/'.basename($image));
                } else {
                    $res['msg'] = "Something went wrong while uploading image. Please try again!";
                }
            }
        } else {
            $res['msg'] = "Only images are allowed to upload!";
        }

        exit(json_encode($res));
    }
    public function upload_file(Request $request)
    {
        $res = array();
        $res['status'] = 0;
        $input = $request->all();
        if ($request->hasFile('file')) {
            $request_data = [
                'file' => 'mimes:jpg,jpeg,pdf,docx|max:40000'
            ];
            $validator = Validator::make($input, $request_data);
            // json is null
            if ($validator->fails()) {
                $res['status'] = 0;
                $res['msg'] = 'Error >>' . $validator->errors()->first();
            } else {
                $image = $request->file('file')->store('public/attachments/');
                $res['file_name'] = $_FILES['file']['name'];
                $res['file'] = $image;
                if (!empty(basename($image))) {
                    $uploadedFile = $request->file('file');
                    $filename = $uploadedFile->getClientOriginalName();
                    $res['status'] = 1;
                    $res['file_name'] = basename($image);
                    $res['file_name_text'] = $filename;
                } else {
                    $res['msg'] = "Something went wrong while uploading file. Please try again!";
                }
            }
        } else {
            $res['msg'] = "No file selected!";
        }

        exit(json_encode($res));
    }

    public function newsletter(Request $request)
    {
        $res = array();
        $res['status'] = 0;
        $input = $request->all();
        if ($input) {
            $request_data = [
                'email' => 'required|email|unique:newsletter,email',
            ];
            $validator = Validator::make($input, $request_data);
            // json is null
            if ($validator->fails()) {
                $res['status'] = 0;
                $res['msg'] = 'Error >>' . $validator->errors()->first();
            } else {
                $data = array(
                    'email' => $input['email'],
                    'status' => 0
                );
                Newsletter_model::create($data);
                $res['status'] = 1;
                $res['msg'] = 'Subscribed successfully!';
            }
        }
        exit(json_encode($res));
    }
    public function contact_us(Request $request)
    {

        $res = array();
        $res['status'] = 0;
        $input = $request->all();
        if ($input) {
            $request_data = [
                'email' => 'required|email',
                'fname' => 'required',
                'lname' => 'required',
                'phone' => 'required',
                'comments' => 'required',
                'hear_about' => 'required',
                'services' => 'required',
            ];
            $custom_messages = [
                'services.required' => 'Please select at least one service from the list.'
            ];

            $validator = Validator::make($input, $request_data, $custom_messages);
            // $validator = Validator::make($input, $request_data);
            // json is null
            if ($validator->fails()) {
                $res['status'] = 0;
                $res['msg'] = 'Error >>' . $validator->errors();
            } else {
                $services=json_decode($input['services']);
                $data = array(
                    'name' => $input['fname'] . " " . $input['lname'],
                    'email' => $input['email'],
                    'phone' => $input['phone'],
                    'message' => $input['comments'],
                    'hear_about' => $input['hear_about'],
                    'services' => implode(",",$services),
                    'status' => 0
                );
                // pr($data);
                Contact_model::create($data);
                $res['status'] = 1;
                $res['msg'] = 'Message sent successfully!';
            }
        }
        exit(json_encode($res));
    }
}
