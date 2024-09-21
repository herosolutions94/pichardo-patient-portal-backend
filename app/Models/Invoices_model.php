<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices_model extends Model
{
    use HasFactory;
    protected $table = 'invoices';
    protected $fillable = [
        'request_id',
        'amount',
        'status',
        'fname',
        'lname',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'payment_method',
        'payment_method_id',
        'payment_intent',
        'customer_id',
    ];
    public function member_row()
    {
        return $this->belongsTo(Request_model::class,'request_id','id');
    }
}
