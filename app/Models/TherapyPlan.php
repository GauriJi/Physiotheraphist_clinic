<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class TherapyPlan extends Model
{
    use HasFactory;

    protected $table = 'therapy_plans';

    protected $fillable = [
        'patient_id', 'physiotherapist_id',
        'plan_name', 'diagnosis', 'goal', 'notes',
        'total_sessions', 'sessions_frequency', 'skip_sundays',
        'session_time', 'session_duration',
        'start_date', 'end_date', 'status',
    ];

    protected $casts = [
        'start_date'    => 'date',
        'end_date'      => 'date',
        'skip_sundays'  => 'boolean',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function physiotherapist()
    {
        return $this->belongsTo(Physiotherapist::class, 'physiotherapist_id');
    }

    public function sessions()
    {
        return $this->hasMany(TherapySession::class)->orderBy('session_number');
    }

    // ── Computed Attributes ───────────────────────────────────────────────────

    public function getCompletedCountAttribute(): int
    {
        return $this->sessions()->where('status', 'completed')->count();
    }

    public function getMissedCountAttribute(): int
    {
        return $this->sessions()->where('status', 'missed')->count();
    }

    public function getCancelledCountAttribute(): int
    {
        return $this->sessions()->where('status', 'cancelled')->count();
    }

    public function getRescheduledCountAttribute(): int
    {
        return $this->sessions()->where('status', 'rescheduled')->count();
    }

    public function getUpcomingCountAttribute(): int
    {
        return $this->sessions()->where('status', 'upcoming')->count();
    }

    public function getProgressPercentAttribute(): int
    {
        if ($this->total_sessions <= 0) return 0;
        return (int) round(($this->completed_count / $this->total_sessions) * 100);
    }

    public function getRemainingCountAttribute(): int
    {
        return max(0, $this->total_sessions - $this->completed_count);
    }

    public function nextSession(): ?TherapySession
    {
        return $this->sessions()
            ->where('status', 'upcoming')
            ->orderBy('scheduled_date')
            ->first();
    }

    // ── Static Helper: Calculate next working date ─────────────────────────────

    /**
     * Advance a Carbon date by $frequencyDays, optionally skipping Sundays.
     */
    public static function nextSessionDate(Carbon $from, int $frequencyDays, bool $skipSundays): Carbon
    {
        $date = $from->copy()->addDays($frequencyDays);
        if ($skipSundays) {
            while ($date->isSunday()) {
                $date->addDay();
            }
        }
        return $date;
    }

    /**
     * Find the next available date after a given date that doesn't already
     * have a session in this plan, and isn't a Sunday (if skip_sundays).
     */
    public function nextAvailableDate(Carbon $after): Carbon
    {
        $existing = $this->sessions()
            ->pluck('scheduled_date')
            ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))
            ->toArray();

        $date = $after->copy()->addDay();
        if ($this->skip_sundays) {
            while ($date->isSunday()) $date->addDay();
        }
        while (in_array($date->format('Y-m-d'), $existing)) {
            $date->addDay();
            if ($this->skip_sundays) {
                while ($date->isSunday()) $date->addDay();
            }
        }
        return $date;
    }

    /**
     * Generate all session dates for a plan (used in store).
     */
    public static function generateSessionDates(Carbon $startDate, int $total, int $freqDays, bool $skipSundays): array
    {
        $dates = [];
        $date  = $startDate->copy();

        if ($skipSundays) {
            while ($date->isSunday()) $date->addDay();
        }

        for ($i = 0; $i < $total; $i++) {
            $dates[] = $date->copy();
            $date->addDays($freqDays);
            if ($skipSundays) {
                while ($date->isSunday()) $date->addDay();
            }
        }
        return $dates;
    }
}
