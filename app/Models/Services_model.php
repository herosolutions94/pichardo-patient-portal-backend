<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services_model extends Model
{
    use HasFactory;
    protected $table = 'services';
    protected $fillable = [
        'name',
        'description',
        'image',
        'status',
    ];
}
