@extends('admin.layouts.sidebar')
@section('title','Create Invoice')
@section('page-title','Create Invoice')
@section('breadcrumb','Admin / Invoices / New')

@section('content')
<div style="max-width:820px;">

<form action="{{ route('admin.invoices.store') }}" method="POST" id="invoiceForm">
@csrf

{{-- PATIENT --}}
<div class="card" style="margin-bottom:1.25rem;">
    <div class="card-header"><div class="card-title">👥 Select Patient</div></div>
    <div class="card-body">
        <div class="form-group">
            <label class="form-label">Patient *</label>
            <select name="patient_id" class="form-control" required>
                <option value="">— Select Patient —</option>
                @foreach($patients as $p)
                    <option value="{{ $p->id }}" {{ (request('patient_id')==$p->id || old('patient_id')==$p->id)?'selected':'' }}>
                        {{ $p->full_name }}{{ $p->patient_uid ? ' [' . $p->patient_uid . ']' : '' }} — {{ $p->email ?? $p->phone }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

{{-- LINE ITEMS --}}
<div class="card" style="margin-bottom:1.25rem;">
    <div class="card-header">
        <div class="card-title">📋 Treatment Items</div>
        <button type="button" class="btn btn-ghost btn-sm" onclick="addItem()">➕ Add Item</button>
    </div>
    <div class="card-body">
        <table style="width:100%;border-collapse:collapse;" id="itemsTable">
            <thead>
                <tr>
                    <th style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;padding:.5rem;text-align:left;">Description</th>
                    <th style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;padding:.5rem;text-align:center;width:80px;">Qty</th>
                    <th style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;padding:.5rem;text-align:center;width:120px;">Rate (₹)</th>
                    <th style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;padding:.5rem;text-align:center;width:120px;">Amount (₹)</th>
                    <th style="width:40px;"></th>
                </tr>
            </thead>
            <tbody id="itemsBody">
                <tr class="item-row">
                    <td style="padding:.4rem;"><input name="items[0][desc]" class="form-control" placeholder="Physiotherapy Session" required></td>
                    <td style="padding:.4rem;"><input name="items[0][qty]" type="number" min="1" value="1" class="form-control item-qty" style="text-align:center;" oninput="calcRow(this)"></td>
                    <td style="padding:.4rem;"><input name="items[0][rate]" type="number" min="0" step="0.01" value="0" class="form-control item-rate" style="text-align:center;" oninput="calcRow(this)"></td>
                    <td style="padding:.4rem;text-align:center;font-weight:700;color:#0f172a;" class="item-amount">₹0.00</td>
                    <td style="padding:.4rem;text-align:center;"><button type="button" onclick="removeItem(this)" style="background:none;border:none;color:#ef4444;font-size:16px;cursor:pointer;">✕</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- TOTALS --}}
<div class="card" style="margin-bottom:1.25rem;">
    <div class="card-header"><div class="card-title">🧮 Totals & Notes</div></div>
    <div class="card-body">
        <div class="grid-3">
            <div class="form-group">
                <label class="form-label">Tax (%)</label>
                <input name="tax_percent" type="number" min="0" max="100" step="0.01" value="{{ old('tax_percent',0) }}" class="form-control" id="taxPercent" oninput="calcTotals()">
            </div>
            <div class="form-group">
                <label class="form-label">Discount (₹)</label>
                <input name="discount" type="number" min="0" step="0.01" value="{{ old('discount',0) }}" class="form-control" id="discount" oninput="calcTotals()">
            </div>
            <div class="form-group">
                <label class="form-label">Due Date</label>
                <input name="due_date" type="date" value="{{ old('due_date') }}" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Notes (optional)</label>
            <textarea name="notes" class="form-control" rows="2" placeholder="Payment instructions, bank details…">{{ old('notes') }}</textarea>
        </div>
        {{-- Summary box --}}
        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:1rem;max-width:300px;margin-top:.5rem;">
            <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:.4rem;color:#475569;"><span>Subtotal</span><span id="showSubtotal">₹0.00</span></div>
            <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:.4rem;color:#475569;"><span>Tax</span><span id="showTax">₹0.00</span></div>
            <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:.4rem;color:#ef4444;"><span>Discount</span><span id="showDiscount">₹0.00</span></div>
            <div style="display:flex;justify-content:space-between;font-size:16px;font-weight:800;color:#0f172a;border-top:1px solid #e2e8f0;padding-top:.5rem;"><span>Total</span><span id="showTotal">₹0.00</span></div>
        </div>
    </div>
</div>

<div style="display:flex;gap:.75rem;">
    <button type="submit" class="btn btn-success">💾 Create Invoice</button>
    <a href="{{ route('admin.invoices.index') }}" class="btn btn-ghost">← Cancel</a>
</div>

</form>
</div>
@endsection

@push('scripts')
<script>
let rowIndex = 1;

function addItem() {
    const tbody = document.getElementById('itemsBody');
    const i = rowIndex++;
    const tr = document.createElement('tr');
    tr.className = 'item-row';
    tr.innerHTML = `
        <td style="padding:.4rem;"><input name="items[${i}][desc]" class="form-control" placeholder="Description" required></td>
        <td style="padding:.4rem;"><input name="items[${i}][qty]" type="number" min="1" value="1" class="form-control item-qty" style="text-align:center;" oninput="calcRow(this)"></td>
        <td style="padding:.4rem;"><input name="items[${i}][rate]" type="number" min="0" step="0.01" value="0" class="form-control item-rate" style="text-align:center;" oninput="calcRow(this)"></td>
        <td style="padding:.4rem;text-align:center;font-weight:700;color:#0f172a;" class="item-amount">₹0.00</td>
        <td style="padding:.4rem;text-align:center;"><button type="button" onclick="removeItem(this)" style="background:none;border:none;color:#ef4444;font-size:16px;cursor:pointer;">✕</button></td>
    `;
    tbody.appendChild(tr);
}

function removeItem(btn) {
    const rows = document.querySelectorAll('.item-row');
    if (rows.length <= 1) return;
    btn.closest('tr').remove();
    calcTotals();
}

function calcRow(input) {
    const row = input.closest('tr');
    const qty  = parseFloat(row.querySelector('.item-qty').value)  || 0;
    const rate = parseFloat(row.querySelector('.item-rate').value) || 0;
    row.querySelector('.item-amount').textContent = '₹' + (qty * rate).toFixed(2);
    calcTotals();
}

function calcTotals() {
    let sub = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const qty  = parseFloat(row.querySelector('.item-qty').value)  || 0;
        const rate = parseFloat(row.querySelector('.item-rate').value) || 0;
        sub += qty * rate;
    });
    const taxP  = parseFloat(document.getElementById('taxPercent').value) || 0;
    const disc  = parseFloat(document.getElementById('discount').value)   || 0;
    const tax   = sub * taxP / 100;
    const total = Math.max(0, sub + tax - disc);
    document.getElementById('showSubtotal').textContent = '₹' + sub.toFixed(2);
    document.getElementById('showTax').textContent      = '₹' + tax.toFixed(2);
    document.getElementById('showDiscount').textContent = '₹' + disc.toFixed(2);
    document.getElementById('showTotal').textContent    = '₹' + total.toFixed(2);
}
</script>
@endpush
