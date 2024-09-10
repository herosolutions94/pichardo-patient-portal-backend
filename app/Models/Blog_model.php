<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog_model extends Model
{
    use HasFactory;
    protected $table = 'blog';
    protected $fillable = [
        'title',
        'category',
        'meta_title',
        'meta_description',
        'meta_keywords',
        // 'tags',
        'detail',
        'image',
        'status',
        'featured',
        'popular',
        'slug',
    ];
    public function category_row()
    {
        return $this->belongsTo(Blog_categories_model::class,'category','id');
    }

}