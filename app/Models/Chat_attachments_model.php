<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat_attachments_model extends Model
{
    use HasFactory;
    protected $table = 'chat_attachments';
    protected $fillable = [
        'chat_id',
        'name',
        'file',
    ];
    public function chat_row()
    {
        return $this->belongsTo(Requests_chat_model::class,'chat_id','id');
    }
}
