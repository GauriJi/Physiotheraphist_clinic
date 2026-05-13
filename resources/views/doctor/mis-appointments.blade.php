@extends('admin.layouts.sidebar')
@section('title', 'All Appointments')
@section('page-title', 'All Appointments')
@section('breadcrumb', 'Doctor / Appointments')

@section('content')

{{-- Filters --}}
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-body">
        <form action="" method="GET" style="display:flex; gap:1rem; align-items:flex-end;">
            <div style="flex:1; max-width:250px;">
                <label class="form-label">Filter by Status</label>
                <select name="status" class="form-control" onchange="this.form.submit()">
                    <option value="">All Appointments</option>
                    <option value="pendiente" {{ request('status')=='pendiente'?'selected':'' }}>Pending</option>
                    <option value="confirmada" {{ request('status')=='confirmada'?'selected':'' }}>Confirmed</option>
                    <option value="completada" {{ request('status')=='completada'?'selected':'' }}>Completed</option>
                    <option value="cancelada" {{ request('status')=='cancelada'?'selected':'' }}>Cancelled</option>
                </select>
            </div>
            <div>
                <a href="{{ request()->url() }}" class="btn btn-ghost">Clear Filters</a>
            </div>
        </form>
    </div>
</div>

{{-- Appointments Table --}}
<div class="card">
    <div style="overflow-x:auto;">
        @if($appointments->count())
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Date & Time</th>
                        <th>Specialty</th>
                        <th>Reason for Visit</th>
                        <th>Status</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                        <tr>
                            <td>
                                <div style="font-weight:600; color:#0f172a;">{{ $appointment->names }} {{ $appointment->last_names }}</div>
                                <div style="font-size:12px; color:#64748b;">{{ $appointment->email }}</div>
                            </td>
                            <td>
                                <div style="font-weight:500;">{{ $appointment->fecha_cita->format('M d, Y') }}</div>
                                <div style="font-size:12px; color:#64748b;">{{ $appointment->hora_cita }}</div>
                            </td>
                            <td>
                                <span class="badge" style="background:#eff6ff;color:#3b82f6;">{{ $appointment->specialty->name ?? '—' }}</span>
                            </td>
                            <td>
                                <div style="max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $appointment->reason }}">
                                    {{ $appointment->reason }}
                                </div>
                            </td>
                            <td>
                                @php 
                                    $s = strtolower($appointment->status); 
                                    $statusEng = ['pendiente' => 'Pending', 'confirmada' => 'Confirmed', 'completada' => 'Completed', 'cancelada' => 'Cancelled'];
                                @endphp
                                <span class="badge badge-{{ $s === 'pendiente' ? 'pending' : ($s === 'confirmada' ? 'confirmed' : ($s === 'completada' ? 'completed' : 'cancelled')) }}">
                                    {{ $statusEng[$s] ?? ucfirst($s) }}
                                </span>
                            </td>
                            <td style="text-align:right;">
                                <div style="display:flex; gap:.4rem; justify-content:flex-end;">
                                    @if($appointment->status === 'pendiente')
                                        <form action="{{ route('doctor.confirmar-appointment', $appointment->id ?? 0) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">✓ Confirm</button>
                                        </form>
                                        <form action="{{ route('shared.appointments.cancelar', $appointment->id) }}" method="POST" onsubmit="return confirm('Cancel this appointment?');">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">✕ Cancel</button>
                                        </form>
                                    @else
                                        @if($appointment->status !== 'cancelada')
                                            <form action="{{ route('shared.appointments.cancelar', $appointment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                                                @csrf
                                                <button type="submit" class="btn btn-warning btn-sm" style="background:#f59e0b; color:white; border:none; padding:4px 8px; border-radius:4px; font-size:12px; cursor:pointer;">✕ Cancel</button>
                                            </form>
                                        @endif
                                    @endif

                                    @if($appointment->status !== 'completada' && $appointment->status !== 'cancelada')
                                        <a href="{{ route('shared.appointments.edit', $appointment->id) }}" class="btn btn-sm" style="background:#3b82f6; color:white; padding:4px 8px; border-radius:4px; font-size:12px; text-decoration:none;">🔄 Reschedule</a>
                                    @endif
                                    
                                    <form action="{{ route('shared.appointments.destroy', $appointment->id) }}" method="POST" onsubmit="return confirm('WARNING: This will permanently delete the appointment. Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="background:#ef4444; color:white; border:none; padding:4px 8px; border-radius:4px; font-size:12px; cursor:pointer;">🗑️ Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            @if($appointments->hasPages())
                <div style="padding:1rem 1.25rem; border-top:1px solid #e2e8f0;">
                    {{ $appointments->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-icon">📋</div>
                <div class="empty-title">No appointments found</div>
                <div class="empty-sub">There are no appointments matching your criteria.</div>
            </div>
        @endif
    </div>
</div>

@endsection
