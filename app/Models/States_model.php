<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class States_model extends Model
{
    use HasFactory;
    protected $table = 'states';
    protected $fillable = [
        'name',
        'country_id',
    ];
    function locations(){
        return $this->hasMany(Locations_model::class,'state','id');
    }
}