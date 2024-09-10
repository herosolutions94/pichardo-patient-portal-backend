<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq_model extends Model
{
    use HasFactory;
    protected $table = 'faqs';
    protected $fillable = [
        'question',
        'answer',
        'status',
        'category',
        'author'
    ];
    public function faq_category_row()
    {
        return $this->belongsTo(Faq_categories_model::class,'category','id');
    }
}