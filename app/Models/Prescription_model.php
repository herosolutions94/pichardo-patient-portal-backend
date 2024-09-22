<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription_model extends Model
{
    use HasFactory;
    protected $table = 'prescriptions';
    protected $fillable = [
        'mem_id',
        'request_id',
        'doctor_name',
        'doctor_note',
        'additional_note',
    ];
    public function medications()
    {
        return $this->hasMany(Medication_model::class, 'prescription_id', 'id');
    }

    public function member_row()
    {
        return $this->belongsTo(Member_model::class,'mem_id','id');
    }

    public function requests() {
        return $this->hasOne(Request_model::class, 'id', 'request_id');
    }
}
