<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permissions_admin_model extends Model
{
    use HasFactory;
    protected $table = 'permissions_admin';
    protected $fillable = [
        'admin_id',
        'permission_id',
    ];
    public function permission_row()
    {
        return $this->belongsTo(Permissions_model::class,'permission_id','id');
    }
    public function sub_admin_row()
    {
        return $this->belongsTo(Admin::class,'admin_id','id');
    }
}