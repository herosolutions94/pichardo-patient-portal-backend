<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq_categories_model extends Model
{
    use HasFactory;
    protected $table = 'faq_categories';
    protected $fillable = [
        'name',
        'status',
        'slug'
    ];
    function faqs(){
        return $this->hasMany(Faq_model::class,'category','id')->where('status',1);
    }
}