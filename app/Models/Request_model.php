<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request_model extends Model
{
    use HasFactory;
    protected $table = 'requests';
    protected $fillable = [
        'mem_id',
        'preferred_pharmacy',
        'address',
        'subject',
        'symptoms',
        'requested_medication',
        'document',
        // 'image',
        'status',
    ];
    public function messages()
    {
        return $this->hasMany(Requests_chat_model::class, 'request_id', 'id');
    }
    public function invoice()
    {
        return $this->hasOne(Invoices_model::class, 'request_id', 'id');
    }
    public function member_row()
    {
        return $this->belongsTo(Member_model::class,'mem_id','id');
    }
}
