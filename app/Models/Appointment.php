<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointments';

    protected $fillable = [
        'patient_id',
        'physiotherapist_id',
        'fecha_cita',
        'hora_cita',
        'reason',
        'status'
    ];

    // Relación con patient
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Relación con physiotherapist
    public function physiotherapist()
    {
        return $this->belongsTo(Physiotherapist::class);
    }
}
