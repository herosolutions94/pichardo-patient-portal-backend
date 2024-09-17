<?php

use App\Http\Controllers\Ajax;
use App\Http\Controllers\admin\Blog;
use App\Http\Controllers\admin\Faqs;
use App\Http\Controllers\admin\Promocodes;
use App\Http\Controllers\admin\Index;
use App\Http\Controllers\admin\Pages;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\Contact;
use App\Http\Controllers\admin\Members;
use App\Http\Controllers\admin\Dashboard;
use App\Http\Controllers\admin\Categories;
use App\Http\Controllers\admin\Sitecontent;
use App\Http\Controllers\admin\Subscribers;
use App\Http\Controllers\admin\Testimonials;
use App\Http\Controllers\admin\Faq_categories;
use App\Http\Controllers\admin\ContentPages;
use App\Http\Controllers\admin\Blog_categories;
use App\Http\Controllers\admin\Top_searches;
use App\Http\Controllers\admin\Locations;
use App\Http\Controllers\admin\Withdraw_requests;
use App\Http\Controllers\admin\Listings;
use App\Http\Controllers\admin\Bookings;
use App\Http\Controllers\admin\Chat;
use App\Http\Controllers\admin\Mem_id_verifications;
use App\Http\Controllers\admin\Tickets;
use App\Http\Controllers\admin\Sub_admin;
use App\Http\Controllers\admin\Permissions;
use App\Http\Controllers\admin\Team;
use App\Http\Controllers\admin\Services;
use App\Http\Controllers\admin\Preferred_pharmacy;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/*==============================API POST  Routes =====================================*/
/*==============================Ajax Routes =====================================*/
// Route::post('newsletter', [Ajax::class,'newsletter']);
Route::get('get_states/{country_id}', [Ajax::class,'get_states']);
Route::get('json_object', [Ajax::class,'json_object']);
// Route::get('get_data', [Ajax::class,'get_data']);
Route::match(['GET','POST'], 'get_data', [Ajax::class,'get_data']);
Route::match(['GET','POST'], 'upload-editor-image', [Ajax::class,'upload_editor_image']);
Route::post('post_data', [Ajax::class,'post_data']);
Route::get('home_page', [ContentPages::class,'home_page']);
// Route::match(['GET','POST'], '/get_data', [Ajax::class,'get_data']);
/*==============================Admin Routes =====================================*/
Route::controller(Index::class)->group(function () {
    Route::get('/admin/register', 'register');
    Route::post('/admin/register', 'store');
});
Route::get('/admin/login', [Index::class,'admin_login'])->middleware('admin_logged_in');
Route::get('/admin/login', [Index::class,'admin_login'])->middleware('admin_logged_in');
Route::post('/admin/login', [Index::class,'login'])->middleware('admin_logged_in');
Route::get('/admin/logout', [Index::class,'logout']);

Route::middleware(['is_admin'])->group(function(){
    Route::get('/admin/dashboard', [Dashboard::class,'index']);
    Route::match(['GET','POST'], '/admin/change-password', [Dashboard::class,'change_password']);
    Route::get('/admin/site_settings', [Dashboard::class,'settings']);
    Route::post('/admin/settings', [Dashboard::class,'settings_update']);
    Route::get('/admin/sitecontent', [Sitecontent::class,'index']);


    /*==============================Sub Admin Module =====================================*/
    Route::get('/admin/sub-admin', [Sub_admin::class,'index']);
    Route::match(['GET','POST'], '/admin/sub-admin/add', [Sub_admin::class,'add']);
    Route::match(['GET','POST'], '/admin/sub-admin/edit/{id}', [Sub_admin::class,'edit']);
    Route::match(['GET','POST'], '/admin/sub-admin/permissions/{id}', [Sub_admin::class,'permissions']);
    Route::match(['GET','POST'], '/admin/sub-admin/delete/{id}', [Sub_admin::class,'delete']);
    /*==============================Permissions Module =====================================*/
    Route::get('/admin/permissions', [Permissions::class,'index']);
    Route::match(['GET','POST'], '/admin/permissions/add', [Permissions::class,'add']);
    Route::match(['GET','POST'], '/admin/permissions/edit/{id}', [Permissions::class,'edit']);
    Route::match(['GET','POST'], '/admin/permissions/delete/{id}', [Permissions::class,'delete']);
    /*==============================Locations Module =====================================*/
    Route::get('/admin/locations', [Locations::class,'index']);
    Route::match(['GET','POST'], '/admin/locations/add', [Locations::class,'add']);
    Route::match(['GET','POST'], '/admin/locations/edit/{id}', [Locations::class,'edit']);
    Route::match(['GET','POST'], '/admin/locations/delete/{id}', [Locations::class,'delete']);
    /*==============================Top Markets Module =====================================*/
    Route::get('/admin/top_markets', [Top_markets::class,'index']);
    Route::match(['GET','POST'], '/admin/top_markets/add', [Top_markets::class,'add']);
    Route::match(['GET','POST'], '/admin/top_markets/edit/{id}', [Top_markets::class,'edit']);
    Route::match(['GET','POST'], '/admin/top_markets/delete/{id}', [Top_markets::class,'delete']);
    /*==============================Services Module =====================================*/
    Route::get('/admin/services', [Services::class,'index']);
    Route::match(['GET','POST'], '/admin/services/add', [Services::class,'add']);
    Route::match(['GET','POST'], '/admin/services/edit/{id}', [Services::class,'edit']);
    Route::match(['GET','POST'], '/admin/services/delete/{id}', [Services::class,'delete']);
     /*==============================Services Module =====================================*/
     Route::get('/admin/preferred_pharmacy', [Preferred_pharmacy::class,'index']);
     Route::match(['GET','POST'], '/admin/preferred_pharmacy/add', [Preferred_pharmacy::class,'add']);
     Route::match(['GET','POST'], '/admin/preferred_pharmacy/edit/{id}', [Preferred_pharmacy::class,'edit']);
     Route::match(['GET','POST'], '/admin/preferred_pharmacy/delete/{id}', [Preferred_pharmacy::class,'delete']);

    /*==============================Testimonials Module =====================================*/
    Route::get('/admin/testimonials', [Testimonials::class,'index']);
    Route::match(['GET','POST'], '/admin/testimonials/add', [Testimonials::class,'add']);
    Route::match(['GET','POST'], '/admin/testimonials/edit/{id}', [Testimonials::class,'edit']);
    Route::match(['GET','POST'], '/admin/testimonials/delete/{id}', [Testimonials::class,'delete']);
    /*==============================Team Module =====================================*/
    Route::get('/admin/team', [Team::class,'index']);
    Route::match(['GET','POST'], '/admin/team/add', [Team::class,'add']);
    Route::match(['GET','POST'], '/admin/team/edit/{id}', [Team::class,'edit']);
    Route::match(['GET','POST'], '/admin/team/delete/{id}', [Team::class,'delete']);
     /*==============================Team Module =====================================*/
     Route::get('/admin/services', [Services::class,'index']);
     Route::match(['GET','POST'], '/admin/services/add', [Services::class,'add']);
     Route::match(['GET','POST'], '/admin/services/edit/{id}', [Services::class,'edit']);
     Route::match(['GET','POST'], '/admin/services/delete/{id}', [Services::class,'delete']);
    /*==============================Partners Module =====================================*/
    Route::get('/admin/partners', [Partners::class,'index']);
    Route::match(['GET','POST'], '/admin/partners/add', [Partners::class,'add']);
    Route::match(['GET','POST'], '/admin/partners/edit/{id}', [Partners::class,'edit']);
    Route::match(['GET','POST'], '/admin/partners/delete/{id}', [Partners::class,'delete']);
    /*==============================FAQ Categories Module =====================================*/
    Route::get('/admin/faq_categories', [Faq_categories::class,'index']);
    Route::match(['GET','POST'], '/admin/faq_categories/add', [Faq_categories::class,'add']);
    Route::match(['GET','POST'], '/admin/faq_categories/edit/{id}', [Faq_categories::class,'edit']);
    Route::match(['GET','POST'], '/admin/faq_categories/delete/{id}', [Faq_categories::class,'delete']);
    /*==============================FAQs =====================================*/
    Route::get('/admin/faqs', [Faqs::class,'index']);
    Route::match(['GET','POST'], '/admin/faqs/add', [Faqs::class,'add']);
    Route::match(['GET','POST'], '/admin/faqs/edit/{id}', [Faqs::class,'edit']);
    Route::match(['GET','POST'], '/admin/faqs/delete/{id}', [Faqs::class,'delete']);
    /*==============================Promocodes =====================================*/
    Route::get('/admin/promocodes', [Promocodes::class,'index']);
    Route::match(['GET','POST'], '/admin/promocodes/add', [Promocodes::class,'add']);
    Route::match(['GET','POST'], '/admin/promocodes/edit/{id}', [Promocodes::class,'edit']);
    Route::match(['GET','POST'], '/admin/promocodes/delete/{id}', [Promocodes::class,'delete']);
    /*==============================Categories Module =====================================*/
    Route::get('/admin/categories', [Categories::class,'index']);
    Route::match(['GET','POST'], '/admin/categories/add', [Categories::class,'add']);
    Route::match(['GET','POST'], '/admin/categories/orderAll', [Categories::class,'orderAll']);
    Route::match(['GET','POST'], '/admin/categories/edit/{id}', [Categories::class,'edit']);
    Route::match(['GET','POST'], '/admin/categories/delete/{id}', [Categories::class,'delete']);
    /*==============================BLOG Categories Module =====================================*/
    Route::get('/admin/blog_categories', [Blog_categories::class,'index']);
    Route::match(['GET','POST'], '/admin/blog_categories/add', [Blog_categories::class,'add']);
    Route::match(['GET','POST'], '/admin/blog_categories/edit/{id}', [Blog_categories::class,'edit']);
    Route::match(['GET','POST'], '/admin/blog_categories/delete/{id}', [Blog_categories::class,'delete']);
    /*==============================Top Searches Module =====================================*/
    Route::get('/admin/top_searches', [Top_searches::class,'index']);
    Route::match(['GET','POST'], '/admin/top_searches/add', [Top_searches::class,'add']);
    Route::match(['GET','POST'], '/admin/top_searches/orderAll', [Top_searches::class,'orderAll']);
    Route::match(['GET','POST'], '/admin/top_searches/edit/{id}', [Top_searches::class,'edit']);
    Route::match(['GET','POST'], '/admin/top_searches/delete/{id}', [Top_searches::class,'delete']);
    /*==============================Locations Module =====================================*/
    Route::get('/admin/locations', [Locations::class,'index']);
    Route::match(['GET','POST'], '/admin/locations/add', [Locations::class,'add']);
    Route::match(['GET','POST'], '/admin/locations/orderAll', [Locations::class,'orderAll']);
    Route::match(['GET','POST'], '/admin/locations/edit/{id}', [Locations::class,'edit']);
    Route::match(['GET','POST'], '/admin/locations/delete/{id}', [Locations::class,'delete']);
    /*==============================BLOG =====================================*/
    Route::get('/admin/blog', [Blog::class,'index']);
    Route::match(['GET','POST'], '/admin/blog/add', [Blog::class,'add']);
    Route::match(['GET','POST'], '/admin/blog/edit/{id}', [Blog::class,'edit']);
    Route::match(['GET','POST'], '/admin/blog/delete/{id}', [Blog::class,'delete']);
    /*==============================Website Textual Pages =====================================*/
    Route::match(['GET','POST'], '/admin/pages/home', [Pages::class,'home']);
    Route::match(['GET','POST'], '/admin/pages/services', [Pages::class,'services']);
    Route::match(['GET','POST'], '/admin/pages/help', [Pages::class,'help']);
    Route::match(['GET','POST'], '/admin/pages/blog', [Pages::class,'blog']);
    Route::match(['GET','POST'], '/admin/pages/about', [Pages::class,'about']);
    Route::match(['GET','POST'], '/admin/pages/contact', [Pages::class,'contact']);
    Route::match(['GET','POST'], '/admin/pages/privacy_policy', [Pages::class,'privacy_policy']);
    Route::match(['GET','POST'], '/admin/pages/terms_conditions', [Pages::class,'terms_conditions']);
    Route::match(['GET','POST'], '/admin/pages/signup', [Pages::class,'signup']);
    Route::match(['GET','POST'], '/admin/pages/login', [Pages::class,'login']);
    Route::match(['GET','POST'], '/admin/pages/forgot', [Pages::class,'forgot']);
    Route::match(['GET','POST'], '/admin/pages/reset', [Pages::class,'reset']);
    Route::match(['GET','POST'], '/admin/pages/thankyou', [Pages::class,'thankyou']);
    /*==============================Members =====================================*/
    Route::get('/admin/members', [Members::class,'index']);
    Route::match(['GET','POST'], '/admin/members/add', [Members::class,'add']);
    Route::match(['GET','POST'], '/admin/members/edit/{id}', [Members::class,'edit']);
    Route::match(['GET','POST'], '/admin/members/delete/{id}', [Members::class,'delete']);
    /*==============================Contact =====================================*/
    Route::get('/admin/contact', [Contact::class,'index']);
    Route::match(['GET','POST'], '/admin/contact/view/{id}', [Contact::class,'view']);
    Route::match(['GET','POST'], '/admin/contact/delete/{id}', [Contact::class,'delete']);
    /*==============================Withdraw Requests =====================================*/
    Route::get('/admin/withdraw_requests', [Withdraw_requests::class,'index']);
    Route::match(['GET','POST'], '/admin/withdraw_requests/view/{id}', [Withdraw_requests::class,'view']);
    Route::match(['GET','POST'], '/admin/withdraw_requests/delete/{id}', [Withdraw_requests::class,'delete']);
    Route::match(['GET','POST'], '/admin/withdraw_requests/approve/{id}', [Withdraw_requests::class,'approve']);
    /*==============================USER ID Requests =====================================*/
    Route::get('/admin/user_id_verifications', [Mem_id_verifications::class,'index']);
    Route::match(['GET','POST'], '/admin/user_id_verifications/view/{id}', [Mem_id_verifications::class,'view']);
    Route::match(['GET','POST'], '/admin/user_id_verifications/delete/{id}', [Mem_id_verifications::class,'delete']);
    Route::match(['GET','POST'], '/admin/user_id_verifications/verified/{id}', [Mem_id_verifications::class,'verified']);
    Route::match(['GET','POST'], '/admin/user_id_verifications/unverified/{id}', [Mem_id_verifications::class,'unverified']);
    /*==============================Tickets Requests =====================================*/
    Route::get('/admin/tickets', [Tickets::class,'index']);
    Route::match(['GET','POST'], '/admin/tickets/view/{id}', [Tickets::class,'view']);
    Route::match(['GET','POST'], '/admin/tickets/delete/{id}', [Tickets::class,'delete']);
    Route::match(['GET','POST'], '/admin/tickets/mark_as_in_progress/{id}', [Tickets::class,'mark_as_in_progress']);
    Route::match(['GET','POST'], '/admin/tickets/post-comment/{id}', [Tickets::class,'post_comment']);
    /*==============================Listings =====================================*/
    Route::get('/admin/listings', [Listings::class,'index']);
    Route::match(['GET','POST'], '/admin/listings/view/{id}', [Listings::class,'view']);
    Route::match(['GET','POST'], '/admin/listings/mark_as_featured', [Listings::class,'mark_as_featured']);
    Route::match(['GET','POST'], '/admin/listings/delete/{id}', [Listings::class,'delete']);
    /*==============================Bookings =====================================*/
    Route::get('/admin/bookings', [Bookings::class,'index']);
    Route::match(['GET','POST'], '/admin/bookings/view/{id}', [Bookings::class,'view']);
    Route::match(['GET','POST'], '/admin/bookings/delete/{id}', [Bookings::class,'delete']);
    /*==============================Chat =====================================*/
    Route::get('/admin/chat', [Chat::class,'index']);
    Route::match(['GET','POST'], '/admin/chat/view/{id}', [Chat::class,'view']);
    Route::match(['GET','POST'], '/admin/chat/delete/{id}', [Chat::class,'delete']);
    /*==============================Subscribers =====================================*/
    Route::get('/admin/subscribers', [Subscribers::class,'index']);
    Route::match(['GET','POST'], '/admin/subscribers/view/{id}', [Subscribers::class,'view']);
    Route::match(['GET','POST'], '/admin/subscribers/delete/{id}', [Subscribers::class,'delete']);

    /*==============================Property Ameneties Module =====================================*/
    Route::get('/admin/amenties', [Amenties::class,'index']);
    Route::match(['GET','POST'], '/admin/amenties/add', [Amenties::class,'add']);
    Route::match(['GET','POST'], '/admin/amenties/edit/{id}', [Amenties::class,'edit']);
    Route::match(['GET','POST'], '/admin/amenties/delete/{id}', [Amenties::class,'delete']);
    /*==============================Property Features Module =====================================*/
    Route::get('/admin/features', [Features::class,'index']);
    Route::match(['GET','POST'], '/admin/features/add', [Features::class,'add']);
    Route::match(['GET','POST'], '/admin/features/edit/{id}', [Features::class,'edit']);
    Route::match(['GET','POST'], '/admin/features/delete/{id}', [Features::class,'delete']);
    /*==============================Property Branches Module =====================================*/
    Route::get('/admin/branches', [Branches::class,'index']);
    Route::match(['GET','POST'], '/admin/branches/view/{id}', [Branches::class,'view']);
    Route::match(['GET','POST'], '/admin/branches/delete/{id}', [Branches::class,'delete']);
    /*==============================Property Floor Plans Module =====================================*/
    Route::get('/admin/floor_plans', [Floor_plans::class,'index']);
    Route::match(['GET','POST'], '/admin/floor_plans/view/{id}', [Floor_plans::class,'view']);
    Route::match(['GET','POST'], '/admin/floor_plans/delete/{id}', [Floor_plans::class,'delete']);
});