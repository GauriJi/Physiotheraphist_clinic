<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatientAttendance extends Model
{
    use HasFactory;

    protected $table = 'patient_attendances';

    protected $fillable = [
        'patient_id', 'visit_date', 'session_number',
        'status', 'notes', 'marked_by',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function markedBy()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }

    public function getStatusBadgeAttribute(): array
    {
        return $this->status === 'present'
            ? ['label' => 'Present', 'class' => 'badge-confirmed', 'emoji' => '✅']
            : ['label' => 'Absent',  'class' => 'badge-cancelled',  'emoji' => '❌'];
    }
}
