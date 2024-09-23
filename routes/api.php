<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Ajax;
use App\Http\Controllers\Account;
use App\Http\Controllers\Requests;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContentPages;
use App\Http\Controllers\User_auth;

use App\Http\Controllers\Requests_chat;
use App\Http\Controllers\PrescriptionController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
/*==============================API POST Routes =====================================*/



Route::post('/get_data', [App\Http\Controllers\Ajax::class, 'get_data']);
Route::post('/save-newsletter', [App\Http\Controllers\Ajax::class, 'newsletter']);
Route::post('/save-contact-message', [App\Http\Controllers\Ajax::class, 'contact_us']);
Route::post('/save-image', [App\Http\Controllers\Ajax::class, 'save_image']);
Route::post('/save-verification-uploads', [App\Http\Controllers\Ajax::class, 'save_verification_uploads']);
Route::post('/upload-image', [App\Http\Controllers\Ajax::class, 'upload_image']);
Route::post('/upload-file', [App\Http\Controllers\Ajax::class, 'upload_file']);
Route::post('/upload-files', [App\Http\Controllers\Ajax::class, 'upload_files']);
Route::get('/get-states/{country_id}', [App\Http\Controllers\Ajax::class, 'get_states']);


/*==============================API GET Routes =====================================*/
Route::match(['GET', 'POST'], '/site-settings', [ContentPages::class, 'website_settings']);
Route::match(['GET', 'POST'], '/member-settings', [ContentPages::class, 'member_settings']);
Route::match(['GET', 'POST'], '/home-page', [ContentPages::class, 'home_page']);
Route::match(['GET', 'POST'], '/services-page', [ContentPages::class, 'services_page']);
Route::match(['GET', 'POST'], '/about-page', [ContentPages::class, 'about_page']);
Route::match(['GET', 'POST'], '/contact-page', [ContentPages::class, 'contact_page']);
Route::match(['GET', 'POST'], '/privacy-policy-page', [ContentPages::class, 'privacy_policy_page']);
Route::match(['GET', 'POST'], '/terms-conditions-page', [ContentPages::class, 'terms_conditions_page']);
Route::match(['GET', 'POST'], '/signup-page', [ContentPages::class, 'signup_page']);
Route::match(['GET', 'POST'], '/login-page', [ContentPages::class, 'login_page']);
Route::match(['GET', 'POST'], '/forget-password-page', [ContentPages::class, 'forgot_page']);
Route::match(['GET', 'POST'], '/reset-password-page', [ContentPages::class, 'reset_page']);


/*==============================Member Routes =====================================*/
Route::post('/create-account', [App\Http\Controllers\User_auth::class, 'signup']);
Route::post('/save-login', [App\Http\Controllers\User_auth::class, 'login']);
Route::post('/forgot-password', [App\Http\Controllers\User_auth::class, 'forget_password']);
Route::post('/reset-password/{token}', [App\Http\Controllers\User_auth::class, 'reset_password']);
Route::post('/verify-otp', [App\Http\Controllers\User_auth::class, 'verify_otp']);
Route::post('/resend-email', [App\Http\Controllers\User_auth::class, 'resend_email']);
Route::get('signup-page', [ContentPages::class, 'signup_page']);
Route::get('signin-page', [ContentPages::class, 'signin_page']);
Route::get('forgot-page', [ContentPages::class, 'forgot_page']);
Route::get('reset-page/{token}', [ContentPages::class, 'reset_page']);



/*==============================Account Routes =====================================*/
Route::post('/update-profile', [App\Http\Controllers\Account::class, 'update_profile']);
Route::post('/update-password', [App\Http\Controllers\Account::class, 'update_password']);

Route::post('/deactivate-account', [App\Http\Controllers\Account::class, 'deactivate_account']);
Route::post('/user-dashboard', [App\Http\Controllers\Account::class, 'user_dashboard']);
Route::post('/create-request', [App\Http\Controllers\Requests::class, 'user_request']);
Route::post('/user-requests', [App\Http\Controllers\Requests::class, 'user_all_request']);
Route::post('/view-request/{encodedId}', [App\Http\Controllers\Requests::class, 'viewRequest']);
Route::post('/create-payment-intent', [App\Http\Controllers\Requests::class, 'create_payment_intent']);
Route::post('/pay-invoice', [App\Http\Controllers\Requests::class, 'invoice_pay']);

// ===========================requests chat==========================================
// Route::post('/chat-requests', [App\Http\Controllers\Requests_chat::class, 'chat_requests']);
Route::post('/chat-requests', [Requests::class, 'chat_requests']);
// Route::post('/all-chat-requests/{id}', [App\Http\Controllers\Requests_chat::class, 'chat_all_request']);

// ========================prescription=============
Route::post('/prescriptions-all', [App\Http\Controllers\PrescriptionController::class, 'prescription_all']);
Route::post('/view-prescription/{encodedId}', [App\Http\Controllers\PrescriptionController::class, 'view_prescription']);
Route::match(['GET', 'POST'], '/generate-prescription/{id}', [PrescriptionController::class, 'generate_prescription_pdf']);

// ===========notifications===========
Route::match(['GET', 'POST'], '/notifications', [Account::class, 'notifications']);
Route::post('/delete-notification/{id}', [App\Http\Controllers\Account::class, 'delete_notification']);
Route::match(['GET', 'POST'], '/earnings', [Account::class, 'earnings']);
Route::match(['GET', 'POST'], '/transactions', [Account::class, 'transactions']);
Route::match(['GET', 'POST'], '/withdrawal-methods', [Account::class, 'withdrawal_methods']);
Route::match(['GET', 'POST'], '/add-withdrawal-method', [Account::class, 'add_withdawal_method']);
Route::match(['GET', 'POST'], '/delete-withdrawal-method/{id}', [Account::class, 'delete_withdrawal_method']);
Route::match(['GET', 'POST'], '/save-withdrawal-request', [Account::class, 'save_withdrawal_request']);



Route::match(['GET', 'POST'], '/payment-methods', [Account::class, 'payment_methods']);
Route::match(['GET', 'POST'], '/create-payment-stripe-intent', [Account::class, 'create_payment_stripe_intent']);
Route::match(['GET', 'POST'], '/save-credit-card', [Account::class, 'save_credit_card']);
Route::match(['GET', 'POST'], '/delete-payment-method/{id}', [Account::class, 'delete_payment_method']);