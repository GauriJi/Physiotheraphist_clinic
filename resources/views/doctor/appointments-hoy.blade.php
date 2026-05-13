@extends('admin.layouts.sidebar')
@section('title', "Today's Schedule")
@section('page-title', "Today's Schedule")
@section('breadcrumb', 'Doctor / Today')

@section('content')

<div class="grid-3" style="margin-bottom: 1.5rem;">
    <div class="stat-card blue">
        <div class="stat-icon blue">⚡</div>
        <div class="stat-value">{{ $citasHoy->count() }}</div>
        <div class="stat-label">Appointments Today</div>
    </div>
    <div class="stat-card amber">
        <div class="stat-icon amber">📅</div>
        <div class="stat-value">{{ $estadisticas['proximas'] ?? 0 }}</div>
        <div class="stat-label">Upcoming Appointments</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon green">👥</div>
        <div class="stat-value">{{ $estadisticas['pacientes_unicos'] ?? 0 }}</div>
        <div class="stat-label">Unique Patients</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">🔥 Today's Patients</div>
    </div>
    <div style="overflow-x:auto;">
        @if ($citasHoy->count() > 0)
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Patient</th>
                        <th>Contact</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($citasHoy as $appointment)
                        <tr>
                            <td>
                                <div style="font-weight:700; color:#3b82f6; font-size:15px;">{{ $appointment->hora_cita }}</div>
                            </td>
                            <td>
                                <div style="font-weight:600; color:#0f172a;">{{ $appointment->names }} {{ $appointment->last_names }}</div>
                                <div style="font-size:12px; color:#64748b;">{{ $appointment->specialty->name ?? '' }}</div>
                            </td>
                            <td>
                                <div style="font-size:13px; color:#374151; margin-bottom:2px;">{{ $appointment->email }}</div>
                                <div style="font-size:12px; color:#64748b;">{{ $appointment->phone }}</div>
                            </td>
                            <td>
                                <div style="max-width:180px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; font-size:13px;" title="{{ $appointment->reason }}">
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
                                    @if ($appointment->status === 'pendiente')
                                        <form action="{{ route('doctor.confirmar-appointment', $appointment->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">✓ Confirm</button>
                                        </form>
                                        <form action="{{ route('shared.appointments.cancelar', $appointment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
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
        @else
            <div class="empty-state">
                <div class="empty-icon">☕</div>
                <div class="empty-title">No appointments for today</div>
                <div class="empty-sub">You have a free schedule today!</div>
            </div>
        @endif
    </div>
</div>

@endsection
