<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TherapySession extends Model
{
    use HasFactory;

    protected $table = 'therapy_sessions';

    protected $fillable = [
        'therapy_plan_id', 'patient_id', 'session_number',
        'scheduled_date', 'scheduled_time', 'duration',
        'status', 'actual_date', 'original_date',
        'therapist_notes', 'marked_by',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'actual_date'    => 'date',
        'original_date'  => 'date',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function plan()
    {
        return $this->belongsTo(TherapyPlan::class, 'therapy_plan_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function markedBy()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }

    // ── Computed: Status badge ─────────────────────────────────────────────────

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'completed'   => ['label' => 'Completed',   'color' => '#10b981', 'bg' => '#f0fdf4', 'border' => '#bbf7d0', 'emoji' => '✅'],
            'missed'      => ['label' => 'Missed',       'color' => '#ef4444', 'bg' => '#fef2f2', 'border' => '#fecaca', 'emoji' => '❌'],
            'rescheduled' => ['label' => 'Rescheduled', 'color' => '#3b82f6', 'bg' => '#eff6ff', 'border' => '#bfdbfe', 'emoji' => '🔄'],
            'cancelled'   => ['label' => 'Cancelled',   'color' => '#6b7280', 'bg' => '#f9fafb', 'border' => '#e5e7eb', 'emoji' => '🚫'],
            default       => ['label' => 'Upcoming',    'color' => '#f59e0b', 'bg' => '#fffbeb', 'border' => '#fde68a', 'emoji' => '⏰'],
        };
    }

    // Calendar dot color (for JS calendar)
    public function getCalendarColorAttribute(): string
    {
        return match ($this->status) {
            'completed'   => '#10b981',
            'missed'      => '#ef4444',
            'rescheduled' => '#3b82f6',
            'cancelled'   => '#9ca3af',
            default       => '#f59e0b',
        };
    }

    // Is this session in the past and still upcoming? (auto-overdue)
    public function getIsOverdueAttribute(): bool
    {
        return $this->status === 'upcoming'
            && $this->scheduled_date->isPast()
            && !$this->scheduled_date->isToday();
    }
}
