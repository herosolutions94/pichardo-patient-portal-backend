<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permissions_model extends Model
{
    use HasFactory;
    protected $table = 'permissions';
    protected $fillable = [
        'permission',
    ];
    public function subPermissions()
    {
        return $this->hasMany(Permissions_admin_model::class, 'permission_id', 'id');
    }
}