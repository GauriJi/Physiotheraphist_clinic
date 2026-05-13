<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalHistory extends Model
{
    use HasFactory;

    protected $table = 'medical_histories';

    protected $fillable = [
        'patient_id',
        'physiotherapist_id',
        'descripcion',
        'diagnostico',
        'tratamiento'
    ];

    // Patient
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Physiotherapist
    public function physiotherapist()
    {
        return $this->belongsTo(Physiotherapist::class);
    }
}
