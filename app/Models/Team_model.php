<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team_model extends Model
{
    use HasFactory;
    protected $table = 'team';
    protected $fillable = [
        'name',
        'designation',
        'description',
        'image',
        'status',
    ];
}
