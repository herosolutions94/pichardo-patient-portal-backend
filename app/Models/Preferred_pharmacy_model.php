<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preferred_pharmacy_model extends Model
{
    use HasFactory;
    protected $table = 'preferred_pharmacy';
    protected $fillable = [
        'name',
        'status',
    ];
}
