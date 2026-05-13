<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\TherapyPlan;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'patients';

    protected $fillable = [
        'patient_uid',
        'name', 'last_name', 'id_card', 'phone', 'email',
        'fecha_nacimiento', 'address', 'sexo',
        'photo', 'blood_group', 'emergency_contact', 'emergency_phone',
        // Therapy Plan
        'therapy_plan_name', 'therapy_diagnosis', 'therapy_goal',
        'therapy_start_date', 'therapy_end_date',
        // Session Counters
        'sessions_purchased', 'sessions_completed', 'missed_days', 'last_visit_date',
    ];

    protected $casts = [
        'fecha_nacimiento'   => 'date',
        'therapy_start_date' => 'date',
        'therapy_end_date'   => 'date',
        'last_visit_date'    => 'date',
    ];

    // Auto-generate patient_uid on create
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Patient $patient) {
            if (empty($patient->patient_uid)) {
                // Use max id + 1 for new UID (safe before save)
                $max = static::max('id') ?? 0;
                $patient->patient_uid = 'PC-' . str_pad($max + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    // Relationships
    public function appointments() { return $this->hasMany(Appointment::class); }
    public function histories()    { return $this->hasMany(MedicalHistory::class); }
    public function documents()    { return $this->hasMany(PatientDocument::class)->orderByDesc('created_at'); }
    public function invoices()     { return $this->hasMany(Invoice::class)->orderByDesc('created_at'); }
    public function notes()        { return $this->hasMany(DoctorNote::class)->orderByDesc('created_at'); }
    public function attendances()  { return $this->hasMany(PatientAttendance::class)->orderByDesc('visit_date'); }
    public function therapyPlans() { return $this->hasMany(TherapyPlan::class)->orderByDesc('created_at'); }

    public function activeTherapyPlan(): ?TherapyPlan
    {
        return $this->therapyPlans()->where('status', 'active')->latest()->first();
    }

    // Computed: sessions remaining
    public function getSessionsRemainingAttribute(): int
    {
        return max(0, ($this->sessions_purchased ?? 0) - ($this->sessions_completed ?? 0));
    }

    // Full name helper
    public function getFullNameAttribute(): string
    {
        return trim($this->name . ' ' . $this->last_name);
    }

    // Age from date of birth
    public function getAgeAttribute(): ?int
    {
        return $this->fecha_nacimiento
            ? \Carbon\Carbon::parse($this->fecha_nacimiento)->age
            : null;
    }

    // Photo URL — falls back to UI Avatars
    public function getPhotoUrlAttribute(): string
    {
        return $this->photo
            ? asset('storage/' . $this->photo)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name ?: 'P') . '&background=3b82f6&color=fff&size=128';
    }
}
