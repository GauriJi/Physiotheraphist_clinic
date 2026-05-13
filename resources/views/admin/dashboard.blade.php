@extends('admin.layouts.sidebar')
@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')
@section('breadcrumb', 'Admin / Dashboard')

@section('content')

{{-- STATS ROW --}}
<div class="grid-4" style="margin-bottom:1.5rem;">
    <div class="stat-card blue">
        <div class="stat-icon blue">👥</div>
        <div class="stat-value">{{ $stats['pacientes_unicos'] }}</div>
        <div class="stat-label">Total Patients</div>
    </div>
    <div class="stat-card amber">
        <div class="stat-icon amber">📅</div>
        <div class="stat-value">{{ $stats['citas_hoy'] }}</div>
        <div class="stat-label">Today's Appointments</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon green">💰</div>
        <div class="stat-value">₹{{ number_format($stats['revenue_total'], 0) }}</div>
        <div class="stat-label">Total Revenue Collected</div>
    </div>
    <div class="stat-card rose">
        <div class="stat-icon rose">⏳</div>
        <div class="stat-value">{{ $stats['unpaid_invoices'] }}</div>
        <div class="stat-label">Unpaid Invoices</div>
    </div>
</div>

{{-- SECOND ROW --}}
<div class="grid-4" style="margin-bottom:1.75rem;">
    <div class="stat-card purple">
        <div class="stat-icon purple">🩺</div>
        <div class="stat-value">{{ $stats['fisioterapeutas_totales'] }}</div>
        <div class="stat-label">Physiotherapists</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon blue">📋</div>
        <div class="stat-value">{{ $stats['citas_totales'] }}</div>
        <div class="stat-label">Total Appointments</div>
    </div>
    <div class="stat-card amber">
        <div class="stat-icon amber">⏰</div>
        <div class="stat-value">{{ $stats['citas_pendientes'] }}</div>
        <div class="stat-label">Pending Appointments</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon green">✅</div>
        <div class="stat-value">{{ $stats['citas_confirmadas'] }}</div>
        <div class="stat-label">Confirmed Appointments</div>
    </div>
</div>

{{-- CHART + RECENT PATIENTS --}}
<div style="display:grid; grid-template-columns:1fr 340px; gap:1.4rem; margin-bottom:1.4rem;">

    {{-- Revenue Chart --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">📊 Revenue — Last 7 Days</div>
        </div>
        <div class="card-body">
            <canvas id="revenueChart" height="80"></canvas>
        </div>
    </div>

    {{-- Recent Patients --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">👥 Recent Patients</div>
            <a href="{{ route('admin.patients.index') }}" class="btn btn-ghost btn-sm">View All</a>
        </div>
        <div class="card-body" style="padding:0;">
            @forelse($recentPatients as $p)
                <a href="{{ route('admin.patients.show', $p) }}" style="display:flex; align-items:center; gap:.75rem; padding:.8rem 1.2rem; border-bottom:1px solid #f1f5f9; text-decoration:none; transition:background .15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
                    <img src="{{ $p->photo_url }}" alt="" style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                    <div>
                        <div style="font-size:13px;font-weight:600;color:#0f172a;">{{ $p->full_name }}</div>
                        <div style="font-size:11.5px;color:#64748b;">{{ $p->email ?? 'No email' }}</div>
                    </div>
                </a>
            @empty
                <div class="empty-state"><div class="empty-icon">👥</div><div class="empty-sub">No patients yet</div></div>
            @endforelse
        </div>
    </div>
</div>

{{-- RECENT APPOINTMENTS --}}
<div class="card" style="margin-bottom:1.4rem;">
    <div class="card-header">
        <div class="card-title">📋 Recent Appointments</div>
        <div style="display:flex;gap:.5rem;">
            <a href="{{ route('admin.appointments.index') }}" class="btn btn-ghost btn-sm">View All</a>
            <a href="{{ route('admin.patients.create') }}" class="btn btn-primary btn-sm">➕ Add Patient</a>
        </div>
    </div>
    <div style="overflow-x:auto;">
        @if($citasRecientes->count() > 0)
        <table class="tbl">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Specialty</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($citasRecientes as $appt)
                <tr>
                    <td>
                        <div style="font-weight:600;">{{ $appt->names }} {{ $appt->last_names }}</div>
                        <div style="font-size:11.5px;color:#64748b;">{{ $appt->email }}</div>
                    </td>
                    <td>{{ $appt->physiotherapist->name ?? '—' }}</td>
                    <td>{{ $appt->specialty->name ?? '—' }}</td>
                    <td>{{ $appt->fecha_cita->format('d M Y') }}</td>
                    <td>{{ $appt->hora_cita }}</td>
                    <td>
                        @php $s = strtolower($appt->status); @endphp
                        <span class="badge badge-{{ $s === 'pendiente' ? 'pending' : ($s === 'confirmada' ? 'confirmed' : ($s === 'completada' ? 'completed' : 'cancelled')) }}">
                            {{ ucfirst($appt->status) }}
                        </span>
                    </td>
                    <td>
                        @if($appt->status === 'pendiente')
                        <form action="{{ route('admin.appointments.confirmar', $appt->id) }}" method="POST" style="display:inline">
                            @csrf
                            <button class="btn btn-success btn-sm">✓ Confirm</button>
                        </form>
                        @else
                            <span style="color:#94a3b8;font-size:12px;">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <div class="empty-state"><div class="empty-icon">📅</div><div class="empty-title">No appointments yet</div></div>
        @endif
    </div>
</div>

{{-- QUICK ACTIONS --}}
<div class="grid-4">
    <a href="{{ route('admin.patients.create') }}" class="card" style="text-decoration:none;padding:1.25rem;text-align:center;transition:all .2s;" onmouseover="this.style.borderColor='#3b82f6'" onmouseout="this.style.borderColor='#e2e8f0'">
        <div style="font-size:32px;margin-bottom:.5rem;">👥</div>
        <div style="font-weight:700;color:#0f172a;font-size:14px;">Add Patient</div>
        <div style="font-size:12px;color:#64748b;margin-top:3px;">Register new patient</div>
    </a>
    <a href="{{ route('admin.invoices.create') }}" class="card" style="text-decoration:none;padding:1.25rem;text-align:center;transition:all .2s;" onmouseover="this.style.borderColor='#10b981'" onmouseout="this.style.borderColor='#e2e8f0'">
        <div style="font-size:32px;margin-bottom:.5rem;">💰</div>
        <div style="font-weight:700;color:#0f172a;font-size:14px;">Create Invoice</div>
        <div style="font-size:12px;color:#64748b;margin-top:3px;">Generate a new bill</div>
    </a>
    <a href="{{ route('admin.appointments.index') }}" class="card" style="text-decoration:none;padding:1.25rem;text-align:center;transition:all .2s;" onmouseover="this.style.borderColor='#f59e0b'" onmouseout="this.style.borderColor='#e2e8f0'">
        <div style="font-size:32px;margin-bottom:.5rem;">📅</div>
        <div style="font-weight:700;color:#0f172a;font-size:14px;">Appointments</div>
        <div style="font-size:12px;color:#64748b;margin-top:3px;">Manage all appointments</div>
    </a>
    <a href="{{ route('admin.doctors.index') }}" class="card" style="text-decoration:none;padding:1.25rem;text-align:center;transition:all .2s;" onmouseover="this.style.borderColor='#8b5cf6'" onmouseout="this.style.borderColor='#e2e8f0'">
        <div style="font-size:32px;margin-bottom:.5rem;">👨‍⚕️</div>
        <div style="font-weight:700;color:#0f172a;font-size:14px;">Physiotherapists</div>
        <div style="font-size:12px;color:#64748b;margin-top:3px;">Manage doctors</div>
    </a>
</div>

@endsection

@push('scripts')
<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($revenueChart->pluck('date')) !!},
        datasets: [{
            label: 'Revenue (₹)',
            data: {!! json_encode($revenueChart->pluck('revenue')) !!},
            backgroundColor: 'rgba(59,130,246,0.15)',
            borderColor: '#3b82f6',
            borderWidth: 2,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { callback: v => '₹' + v, font: { size: 11 } }, grid: { color: '#f1f5f9' } },
            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});
</script>
@endpush
