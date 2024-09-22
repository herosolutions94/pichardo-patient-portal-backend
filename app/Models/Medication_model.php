<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication_model extends Model
{
    use HasFactory;
    protected $table = 'medications';
    protected $fillable = [
        'prescription_id',
        'medication',
        'dosage',
        'instructions',
    ];
    public function medicine()
    {
        return $this->belongsTo(Prescription_model::class,'prescription_id','id');
    }
}
