<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat_attachments_model extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $fillable = [
        'chat_id',
        'name',
        'file',
    ];
}
