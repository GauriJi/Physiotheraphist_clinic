@extends('admin.layouts.sidebar')
@section('title','Invoices')
@section('page-title','Billing & Invoices')
@section('breadcrumb','Admin / Invoices')

@section('content')

{{-- STATS --}}
<div class="grid-3" style="margin-bottom:1.4rem;">
    <div class="stat-card green">
        <div class="stat-icon green">💰</div>
        <div class="stat-value">₹{{ number_format($totalRevenue, 0) }}</div>
        <div class="stat-label">Total Revenue Collected</div>
    </div>
    <div class="stat-card rose">
        <div class="stat-icon rose">⏳</div>
        <div class="stat-value">{{ $unpaidCount }}</div>
        <div class="stat-label">Unpaid Invoices</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon blue">🧾</div>
        <div class="stat-value">{{ $invoices->total() }}</div>
        <div class="stat-label">Total Invoices</div>
    </div>
</div>

{{-- FILTER + CREATE --}}
<div class="card" style="margin-bottom:1.25rem;">
    <div class="card-body" style="padding:1rem 1.25rem;">
        <form method="GET" style="display:flex;gap:.75rem;flex-wrap:wrap;align-items:flex-end;">
            <div style="flex:1;min-width:200px;">
                <label class="form-label">Search</label>
                <input name="search" value="{{ request('search') }}" class="form-control" placeholder="Invoice # or patient name…">
            </div>
            <div>
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="">All</option>
                    <option value="unpaid"  {{ request('status')==='unpaid'?'selected':'' }}>Unpaid</option>
                    <option value="partial" {{ request('status')==='partial'?'selected':'' }}>Partial</option>
                    <option value="paid"    {{ request('status')==='paid'?'selected':'' }}>Paid</option>
                </select>
            </div>
            <button class="btn btn-primary" type="submit">🔍 Filter</button>
            <a href="{{ route('admin.invoices.index') }}" class="btn btn-ghost">Reset</a>
            <a href="{{ route('admin.invoices.create') }}" class="btn btn-success" style="margin-left:auto;">➕ New Invoice</a>
        </form>
    </div>
</div>

{{-- TABLE --}}
<div class="card">
    <div style="overflow-x:auto;">
        @if($invoices->count() > 0)
        <table class="tbl">
            <thead>
                <tr><th>Invoice #</th><th>Patient</th><th>Date</th><th>Total</th><th>Paid</th><th>Balance</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @foreach($invoices as $inv)
                @php $badge = $inv->status_badge; @endphp
                <tr>
                    <td style="font-weight:700;color:#3b82f6;">{{ $inv->invoice_number }}</td>
                    <td>
                        <div style="font-weight:600;">{{ $inv->patient_name }}</div>
                        <div style="font-size:11.5px;color:#64748b;">{{ $inv->patient_email }}</div>
                    </td>
                    <td style="font-size:12.5px;">{{ $inv->created_at->format('d M Y') }}</td>
                    <td style="font-weight:700;">₹{{ number_format($inv->total, 2) }}</td>
                    <td style="color:#059669;font-weight:600;">₹{{ number_format($inv->paid_amount, 2) }}</td>
                    <td style="color:#ef4444;font-weight:600;">₹{{ number_format($inv->balance, 2) }}</td>
                    <td><span class="badge {{ $badge['class'] }}">{{ $badge['label'] }}</span></td>
                    <td>
                        <div style="display:flex;gap:.4rem;">
                            <a href="{{ route('admin.invoices.show', $inv) }}" class="btn btn-ghost btn-sm">👁 View</a>
                            <form action="{{ route('admin.invoices.destroy', $inv) }}" method="POST" onsubmit="return confirm('Delete invoice?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">🗑</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="padding:1rem 1.25rem;">{{ $invoices->links() }}</div>
        @else
            <div class="empty-state">
                <div class="empty-icon">🧾</div>
                <div class="empty-title">No invoices found</div>
                <a href="{{ route('admin.invoices.create') }}" class="btn btn-success" style="margin-top:1rem;">➕ Create Invoice</a>
            </div>
        @endif
    </div>
</div>

@endsection
