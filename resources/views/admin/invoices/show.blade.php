@extends('admin.layouts.sidebar')
@section('title', $invoice->invoice_number)
@section('page-title', 'Invoice ' . $invoice->invoice_number)
@section('breadcrumb', 'Admin / Invoices / ' . $invoice->invoice_number)

@section('content')

@php $badge = $invoice->status_badge; @endphp

{{-- ACTIONS BAR --}}
<div style="display:flex;gap:.75rem;margin-bottom:1.25rem;flex-wrap:wrap;">
    <button onclick="printInvoice()" class="btn btn-primary">🖨 Print / Save as PDF</button>
    <a href="{{ route('admin.invoices.index') }}" class="btn btn-ghost">← Back</a>
    @if($invoice->status !== 'paid')
        <button onclick="document.getElementById('payModal').classList.add('open')" class="btn btn-success">💳 Record Payment</button>
    @endif
    <form action="{{ route('admin.invoices.destroy', $invoice) }}" method="POST" onsubmit="return confirm('Delete this invoice?')" style="margin-left:auto;">
        @csrf @method('DELETE')
        <button class="btn btn-danger">🗑 Delete</button>
    </form>
</div>

{{-- PRINTABLE INVOICE --}}
<div id="invoicePrint" class="card" style="max-width:780px;padding:2rem;">

    {{-- HEADER --}}
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <div style="font-size:26px;font-weight:900;color:#0f172a;">PhysioCare</div>
            <div style="font-size:13px;color:#64748b;margin-top:3px;">Physiotherapy Clinic</div>
        </div>
        <div style="text-align:right;">
            <div style="font-size:22px;font-weight:800;color:#3b82f6;">{{ $invoice->invoice_number }}</div>
            <div style="font-size:13px;color:#64748b;">Date: {{ $invoice->created_at->format('d M Y') }}</div>
            @if($invoice->due_date)
                <div style="font-size:13px;color:#ef4444;">Due: {{ $invoice->due_date->format('d M Y') }}</div>
            @endif
            <span class="badge {{ $badge['class'] }}" style="margin-top:.4rem;">{{ $badge['label'] }}</span>
        </div>
    </div>

    {{-- BILL TO --}}
    <div style="background:#f8fafc;border-radius:10px;padding:1rem 1.25rem;margin-bottom:1.75rem;">
        <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;margin-bottom:.4rem;">Bill To</div>
        <div style="font-size:16px;font-weight:700;color:#0f172a;">{{ $invoice->patient_name }}</div>
        @if($invoice->patient && $invoice->patient->patient_uid)
        <div style="font-size:11.5px;font-weight:800;color:#6366f1;font-family:monospace;margin-top:2px;">Patient ID: {{ $invoice->patient->patient_uid }}</div>
        @endif
        @if($invoice->patient_email) <div style="font-size:13px;color:#64748b;">{{ $invoice->patient_email }}</div> @endif
        @if($invoice->patient_phone) <div style="font-size:13px;color:#64748b;">{{ $invoice->patient_phone }}</div> @endif
    </div>

    {{-- LINE ITEMS --}}
    <table style="width:100%;border-collapse:collapse;margin-bottom:1.5rem;">
        <thead>
            <tr style="border-bottom:2px solid #e2e8f0;">
                <th style="text-align:left;padding:.6rem;font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;">Description</th>
                <th style="text-align:center;padding:.6rem;font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;width:70px;">Qty</th>
                <th style="text-align:right;padding:.6rem;font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;width:110px;">Rate</th>
                <th style="text-align:right;padding:.6rem;font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;width:110px;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:.7rem .6rem;font-size:14px;color:#374151;">{{ $item['description'] }}</td>
                <td style="padding:.7rem .6rem;text-align:center;font-size:14px;color:#374151;">{{ $item['quantity'] }}</td>
                <td style="padding:.7rem .6rem;text-align:right;font-size:14px;color:#374151;">₹{{ number_format($item['rate'], 2) }}</td>
                <td style="padding:.7rem .6rem;text-align:right;font-size:14px;font-weight:600;color:#0f172a;">₹{{ number_format($item['amount'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- TOTALS --}}
    <div style="display:flex;justify-content:flex-end;">
        <div style="min-width:260px;">
            <div style="display:flex;justify-content:space-between;padding:.4rem 0;font-size:13px;color:#475569;border-bottom:1px solid #f1f5f9;">
                <span>Subtotal</span><span>₹{{ number_format($invoice->subtotal, 2) }}</span>
            </div>
            @if($invoice->tax_percent > 0)
            <div style="display:flex;justify-content:space-between;padding:.4rem 0;font-size:13px;color:#475569;border-bottom:1px solid #f1f5f9;">
                <span>Tax ({{ $invoice->tax_percent }}%)</span><span>₹{{ number_format($invoice->tax_amount, 2) }}</span>
            </div>
            @endif
            @if($invoice->discount > 0)
            <div style="display:flex;justify-content:space-between;padding:.4rem 0;font-size:13px;color:#ef4444;border-bottom:1px solid #f1f5f9;">
                <span>Discount</span><span>- ₹{{ number_format($invoice->discount, 2) }}</span>
            </div>
            @endif
            <div style="display:flex;justify-content:space-between;padding:.6rem 0;font-size:18px;font-weight:900;color:#0f172a;border-top:2px solid #0f172a;">
                <span>Total</span><span>₹{{ number_format($invoice->total, 2) }}</span>
            </div>
            @if($invoice->paid_amount > 0)
            <div style="display:flex;justify-content:space-between;padding:.4rem 0;font-size:13px;color:#059669;">
                <span>Paid</span><span>₹{{ number_format($invoice->paid_amount, 2) }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:.4rem 0;font-size:14px;font-weight:700;color:#ef4444;">
                <span>Balance Due</span><span>₹{{ number_format($invoice->balance, 2) }}</span>
            </div>
            @endif
        </div>
    </div>

    @if($invoice->notes)
    <div style="margin-top:1.5rem;background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:.75rem 1rem;font-size:13px;color:#92400e;">
        <strong>Notes:</strong> {{ $invoice->notes }}
    </div>
    @endif

    <div style="margin-top:2rem;text-align:center;font-size:12px;color:#94a3b8;">
        Thank you for choosing PhysioCare. For queries contact us at admin@physiocare.com
    </div>
</div>

{{-- PAYMENT MODAL --}}
<div class="modal-overlay" id="payModal">
    <div class="modal-box">
        <div class="modal-title">💳 Record Payment</div>
        <form action="{{ route('admin.invoices.pay', $invoice) }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Amount Paid (₹)</label>
                <input name="paid_amount" type="number" step="0.01" min="0" max="{{ $invoice->total }}" value="{{ $invoice->paid_amount > 0 ? $invoice->paid_amount : $invoice->total }}" class="form-control" required>
                <div class="form-text">Invoice total: ₹{{ number_format($invoice->total, 2) }}</div>
            </div>
            <div style="display:flex;gap:.75rem;margin-top:1rem;">
                <button type="submit" class="btn btn-success">✅ Save Payment</button>
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('payModal').classList.remove('open')">Cancel</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('styles')
<style>
@media print {
    .adm-sidebar, .adm-topbar, .modal-overlay, button, a.btn { display: none !important; }
    .adm-main { margin-left: 0 !important; }
    .adm-content { padding: 0 !important; }
    #invoicePrint { box-shadow: none !important; border: none !important; }
}
</style>
@endpush

@push('scripts')
<script>
function printInvoice() {
    window.print();
}
</script>
@endpush
