<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'invoice_number', 'patient_id', 'appointment_id',
        'patient_name', 'patient_email', 'patient_phone',
        'items', 'subtotal', 'tax_percent', 'tax_amount',
        'discount', 'total', 'paid_amount', 'status',
        'due_date', 'paid_at', 'notes', 'created_by',
    ];

    protected $casts = [
        'items'      => 'array',
        'due_date'   => 'date',
        'paid_at'    => 'datetime',
        'subtotal'   => 'float',
        'tax_percent'=> 'float',
        'tax_amount' => 'float',
        'discount'   => 'float',
        'total'      => 'float',
        'paid_amount'=> 'float',
    ];

    public function patient()    { return $this->belongsTo(Patient::class); }
    public function createdBy()  { return $this->belongsTo(User::class, 'created_by'); }

    public function getStatusBadgeAttribute(): array
    {
        return match($this->status) {
            'paid'    => ['label' => 'Paid',    'class' => 'badge-paid'],
            'partial' => ['label' => 'Partial', 'class' => 'badge-partial'],
            default   => ['label' => 'Unpaid',  'class' => 'badge-unpaid'],
        };
    }

    public function getFormattedTotalAttribute(): string
    {
        return '₹' . number_format($this->total, 2);
    }

    public function getBalanceAttribute(): float
    {
        return max(0, $this->total - $this->paid_amount);
    }

    // Auto-generate invoice number
    public static function generateNumber(): string
    {
        $last = self::latest()->first();
        $next = $last ? ((int) substr($last->invoice_number, 4)) + 1 : 1;
        return 'INV-' . str_pad($next, 5, '0', STR_PAD_LEFT);
    }
}
