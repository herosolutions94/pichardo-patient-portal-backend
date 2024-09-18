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
}
