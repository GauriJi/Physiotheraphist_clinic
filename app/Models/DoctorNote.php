<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DoctorNote extends Model
{
    use HasFactory;

    protected $table = 'doctor_notes';

    protected $fillable = [
        'appointment_id', 'patient_id', 'physiotherapist_id',
        'notes', 'exercises', 'progress', 'next_session',
        'session_status', 'created_by',
    ];

    public function patient()        { return $this->belongsTo(Patient::class); }
    public function physiotherapist(){ return $this->belongsTo(Physiotherapist::class); }
    public function createdBy()      { return $this->belongsTo(User::class, 'created_by'); }

    public function getStatusBadgeAttribute(): array
    {
        return match($this->session_status) {
            'improving'  => ['label' => 'Improving',  'class' => 'text-green-600',  'emoji' => '📈'],
            'stable'     => ['label' => 'Stable',     'class' => 'text-blue-600',   'emoji' => '➡️'],
            'worsening'  => ['label' => 'Worsening',  'class' => 'text-red-600',    'emoji' => '📉'],
            'recovered'  => ['label' => 'Recovered',  'class' => 'text-teal-600',   'emoji' => '✅'],
            default      => ['label' => 'Stable',     'class' => 'text-blue-600',   'emoji' => '➡️'],
        };
    }
}
