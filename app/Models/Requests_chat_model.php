<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requests_chat_model extends Model
{
    use HasFactory;
    protected $table = 'requests_chat';
    protected $fillable = [
        'request_id',
        'msg',
        'msg_by',
        'status',
        'sender_id',
        'receiver_id',
    ];
}
