<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial_model extends Model
{
    use HasFactory;
    protected $table = 'testimonials';
    protected $fillable = [
        'name',
        'designation', // Add designation field
        'message',
        'image',
        'status',
    ];
}
