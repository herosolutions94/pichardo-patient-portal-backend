<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Ajax;
use App\Http\Controllers\Account;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContentPages;
use App\Http\Controllers\User_auth;
// use App\Http\Controllers\Listing;
// use App\Http\Controllers\Chat;
// use App\Http\Controllers\Booking;
// use App\Http\Controllers\Tickets;

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
Route::match(['GET', 'POST'], '/thankyou-page', [ContentPages::class, 'thankyou_content']);
Route::match(['GET', 'POST'], '/how-it-works-page', [ContentPages::class, 'how_it_works_page']);
Route::match(['GET', 'POST'], '/about-page', [ContentPages::class, 'about_page']);
Route::match(['GET', 'POST'], '/contact-page', [ContentPages::class, 'contact_page']);
Route::match(['GET', 'POST'], '/help-page', [ContentPages::class, 'help_page']);
Route::match(['GET', 'POST'], '/help-search-page', [ContentPages::class, 'help_search_page']);
Route::match(['GET', 'POST'], '/blog-page', [ContentPages::class, 'blog_page']);
Route::match(['GET', 'POST'], '/blog-details-page/{slug}', [ContentPages::class, 'blog_details_page']);
Route::match(['GET', 'POST'], '/help-details-page/{slug}', [ContentPages::class, 'help_details_page']);
Route::match(['GET', 'POST'], '/category-help-page/{slug}', [ContentPages::class, 'category_help_page']);
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

/*==============================Listing Routes =====================================*/
// Route::post('/edit-listing/{id}', [App\Http\Controllers\Listing::class, 'edit_listing']);
// Route::post('/single-listing/{id}', [App\Http\Controllers\Listing::class, 'single_listing']);
// Route::post('/delete-listing/{id}', [App\Http\Controllers\Listing::class, 'delete_listing']);
// Route::post('/add-listing', [App\Http\Controllers\Listing::class, 'add_listing']);
// Route::post('/listings', [App\Http\Controllers\Listing::class, 'listings']);
// Route::post('/check-rental-item-availability', [App\Http\Controllers\Listing::class, 'checkRentalItemAvailability']);
/*==============================Tickets Routes =====================================*/
// Route::post('/edit-ticket/{id}', [App\Http\Controllers\Tickets::class, 'edit_ticket']);
// Route::post('/close-ticket/{id}', [App\Http\Controllers\Tickets::class, 'close_ticket']);
// Route::post('/post-ticket-comment/{id}', [App\Http\Controllers\Tickets::class, 'post_comment']);
// Route::post('/single-ticket/{id}', [App\Http\Controllers\Tickets::class, 'single_ticket']);
// Route::post('/delete-ticket/{id}', [App\Http\Controllers\Tickets::class, 'delete_ticket']);
// Route::post('/add-ticket', [App\Http\Controllers\Tickets::class, 'add_ticket']);
// Route::post('/tickets', [App\Http\Controllers\Tickets::class, 'ticket']);

/*==============================Explore Search Routes =====================================*/
// Route::match(['GET','POST'], '/explore-search', [Listing::class,'explore_search']);
// Route::match(['GET','POST'], '/explore-search-content', [Listing::class,'explore_search_content']);
// Route::post('/explore-single-listing-details/{slug}', [App\Http\Controllers\Listing::class, 'explore_listing_details_page']);
// Route::post('/listing-user-profile/{username}', [App\Http\Controllers\Listing::class, 'listing_user_profile']);
// Route::post('/send-msg-owner', [App\Http\Controllers\Listing::class, 'send_msg_owner']);

/*==============================Chat Routes =====================================*/
// Route::match(['GET','POST'], '/inbox-conversations', [Chat::class,'get_conversations']);
// Route::match(['GET','POST'], '/confirm-buyer-request', [Chat::class,'confirm_buyer_request']);
// Route::post('/inbox-conversations/{conversation_id}', [App\Http\Controllers\Chat::class, 'get_conversations']);
// Route::match(['GET','POST'], '/booking-extension-request', [Chat::class,'booking_extension_request']);
// Route::match(['GET','POST'], '/request-extension/{id}', [Chat::class,'request_extension']);



/*==============================Account Routes =====================================*/
Route::post('/update-profile', [App\Http\Controllers\Account::class, 'update_profile']);
Route::post('/update-password', [App\Http\Controllers\Account::class, 'update_password']);
Route::post('/deactivate-account', [App\Http\Controllers\Account::class, 'deactivate_account']);
Route::post('/user-dashboard', [App\Http\Controllers\Account::class, 'user_dashboard']);
Route::post('/create-request', [App\Http\Controllers\Account::class, 'user_request']);
Route::post('/user-requests', [App\Http\Controllers\Account::class, 'user_all_request']);

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

/*==============================Booking Routes =====================================*/
// Route::post('/get-rental-request/{id}', [App\Http\Controllers\Booking::class, 'get_rental_request']);
// Route::match(['GET','POST'], '/save-booking', [Booking::class,'save_booking']);
// Route::match(['GET','POST'], '/save-review', [Booking::class,'save_review']);
// Route::match(['GET','POST'], '/extend-booking', [Booking::class,'extend_booking']);
// Route::match(['GET','POST'], '/get-bookings', [Booking::class,'get_bookings']);
// Route::match(['GET','POST'], '/get-booking-details/{id}', [Booking::class,'get_booking_details']);
// Route::match(['GET','POST'], '/change-booking-status/{id}', [Booking::class,'change_booking_status']);
// Route::match(['GET','POST'], '/booking-extension-details/{id}', [Booking::class,'booking_extension_details']);
// Route::match(['GET','POST'], '/booking-item-pickup-request/{id}', [Booking::class,'request_item_pickup']);
// Route::match(['GET','POST'], '/booking-item-return-request/{id}', [Booking::class,'request_item_return']);

/*==============================Payment Routes =====================================*/
// Route::match(['GET','POST'], '/create-payment-intent', [Booking::class,'create_payment_intent']);
// Route::match(['GET','POST'], '/create-extension-payment-intent', [Booking::class,'create_extension_payment_intent']);
// Route::match(['GET','POST'], '/check-promo-code', [Booking::class,'check_promo_code']);