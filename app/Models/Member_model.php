<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member_model extends Model
{
    use HasFactory;
    protected $table = 'members';
    protected $hidden = ['mem_password'];
    protected $fillable = [
        'mem_type',
        'mem_fname',
        'mem_lname',
        'mem_mname',
        'mem_email',
        'mem_phone',
        'mem_password',
        'mem_dob',
        'mem_address1',
        'preferred_pharmacy',
        'gender',
        'allergies',
        'surgical_history',
        'pregnancy_status',
        'smoking_history',
        'mem_address2',
        'mem_city',
        'mem_state',
        'mem_zip',
        'mem_bio',
        'mem_image',
        'identification_photo',
        'mem_status',
        'mem_verified',
        'mem_email_verified',
        'mem_phone_verified',
        'otp',
        'mem_country',
        'mem_fullname',
        'mem_business',
        'mem_domain_name',
        'otp_phone',
        'otp_expire',
        'landlordId',
        'renterId',
        'email_change',
        'phone_change',
        'googleId',
        'dob',
        'verification_expiry_date',
        "verification_status",
        "super_admin",
        "mem_employee",
        "permissions",
        "mem_display_name",
        "is_deactivated",
        "deactivated_reason",
        "mem_id_verified",
        "mem_username",
        "mem_buisness_phone",
        "is_profile_completed",
        "longitude",
        "latitude",
        "mem_address_place_id"
    ];
    // function id_verification($id){
    //     return $this->hasOne(Mem_id_verifications_model::class,'mem_id','id')->where('id',$id)->get()->first();
    // }
    // function sender_messages(){
    //     return $this->hasMany(Msgs_model::class,'sender','id');
    // }
    // function payment_methods(){
    //     return $this->hasMany(Mem_payment_methods_model::class,'mem_id','id');
    // }
    // function receiver_messages(){
    //     return $this->hasMany(Msgs_model::class,'receiver','id');
    // }
    // function permissions(){
    //     return $this->hasMany(Mem_permissions_model::class,'mem_id','id');
    // }
    // function emp_branches(){
    //     return $this->hasMany(Mem_Branches_model::class,'mem_id','id');
    // }
    // public function branch_row()
    // {
    //     return $this->belongsTo(Branches_model::class,'branch_id','id');
    // }
    // public function msgs()
    // {
    //     return $this->hasMany(Msgs_model::class, 'message_by', 'id');
    // }
    // public function listings()
    // {
    //     return $this->hasMany(Listings_model::class, 'mem_id', 'id');
    // }
    // public function earnings()
    // {
    //     return $this->hasMany(Earnings_model::class, 'mem_id', 'id');
    // }

    public function getAvailableBalance()
    {
        $totalCredits = $this->earnings()
            ->whereIn('status', ['cleared', 'withdrawn'])
            ->where('type', 'credit')
            ->sum('amount');

        $totalDebits = $this->earnings()
            ->where('status', 'cleared')
            ->where('type', 'debit')
            ->sum('amount');

        return $totalCredits - $totalDebits;
    }
    public function getPayouts()
    {
        $totalDebits = $this->earnings()
            ->where('status', 'cleared')
            ->where('type', 'debit')
            ->sum('amount');

        return $totalDebits;
    }
    public function averageRating()
    {
        $ratings= $this->listings()
                    ->join('msg_requests', 'listings.id', '=', 'msg_requests.listing_id')
                    ->join('bookings', 'msg_requests.id', '=', 'bookings.request_id')
                    ->join('booking_reviews', 'bookings.id', '=', 'booking_reviews.booking_id')
                    ->selectRaw('AVG(tbl_booking_reviews.rating) as average_rating')
                    // ->groupBy('members.id')
                    ->first();
        if(!empty($ratings)){
            return number_format($ratings->average_rating,2);
        }
        return 0;
    }
    public function total_reviews()
    {
        $ratings= $this->listings()
                    ->join('msg_requests', 'listings.id', '=', 'msg_requests.listing_id')
                    ->join('bookings', 'msg_requests.id', '=', 'bookings.request_id')
                    ->join('booking_reviews', 'bookings.id', '=', 'booking_reviews.booking_id')
                    // ->selectRaw('COUNT(tbl_booking_reviews.*) as total')
                    // ->groupBy('members.id')
                    ->count();
        
        return $ratings;
    }
    public function all_reviews()
    {
        $ratings = $this->listings()
                        ->join('msg_requests', 'listings.id', '=', 'msg_requests.listing_id')
                        ->join('bookings', 'msg_requests.id', '=', 'bookings.request_id')
                        ->join('booking_reviews', 'bookings.id', '=', 'booking_reviews.booking_id')
                        ->join('members', 'booking_reviews.mem_id', '=', 'members.id') // Adjust field names as necessary
                        ->get([
                            'booking_reviews.*', 
                            'members.id as member_id', 
                            'members.mem_fullname as member_name', 
                            'members.mem_image as member_image', 
                            'members.mem_email as member_email'
                        ]); 
        
        return $ratings;
    }

    
}