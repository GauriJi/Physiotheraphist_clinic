<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatientDocument extends Model
{
    use HasFactory;

    protected $table = 'patient_documents';

    protected $fillable = [
        'patient_id', 'appointment_id', 'title', 'type',
        'file_path', 'file_name', 'mime_type', 'uploaded_by', 'notes',
    ];

    public function patient()     { return $this->belongsTo(Patient::class); }
    public function uploader()    { return $this->belongsTo(User::class, 'uploaded_by'); }

    public function getFileUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    public function getIsImageAttribute(): bool
    {
        return in_array($this->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'xray'         => 'X-Ray',
            'mri'          => 'MRI Scan',
            'prescription' => 'Prescription',
            'report'       => 'Medical Report',
            'lab'          => 'Lab Result',
            default        => 'Other',
        };
    }
}
