<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CitaPublica extends Model
{
    use HasFactory;

    protected $table = 'public_appointments';

    protected $fillable = [
        'id_card',
        'names',
        'last_names',
        'email',
        'phone',
        'specialty_id',
        'physiotherapist_id',
        'fecha_cita',
        'hora_cita',
        'reason',
        'status',
        'doctor_notes',
    ];

    protected $casts = [
        'fecha_cita' => 'date',
    ];

    // Relación con specialty
    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    // Relación con physiotherapist
    public function physiotherapist()
    {
        return $this->belongsTo(Physiotherapist::class);
    }
}
