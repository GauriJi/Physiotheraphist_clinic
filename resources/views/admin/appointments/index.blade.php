@extends('admin.layouts.sidebar')
@section('title','Appointments')
@section('page-title','Manage Appointments')
@section('breadcrumb','Admin / Appointments')

@section('content')

{{-- FILTERS + CREATE BUTTON --}}
<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-body" style="padding:1rem 1.25rem;">
        <form action="{{ route('admin.appointments.index') }}" method="GET" style="display:flex;gap:.75rem;flex-wrap:wrap;align-items:flex-end;">
            <div style="flex:1;min-width:200px;">
                <label class="form-label">Search by Status</label>
                <select name="status" class="form-control">
                    <option value="">All Statuses</option>
                    <option value="pendiente" {{ request('status') === 'pendiente' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmada" {{ request('status') === 'confirmada' ? 'selected' : '' }}>Confirmed</option>
                    <option value="completada" {{ request('status') === 'completada' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelada" {{ request('status') === 'cancelada' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div style="flex:1;min-width:200px;">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div>
                <button type="submit" class="btn btn-primary">🔍 Filter</button>
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-ghost">Reset</a>
            </div>
            <div style="margin-left:auto;">
                <a href="{{ route('admin.appointments.create') }}" class="btn btn-success">➕ New Appointment</a>
            </div>
        </form>
    </div>
</div>

{{-- APPOINTMENTS TABLE --}}
<div class="card">
    <div style="overflow-x:auto;">
        @if ($appointments->count() > 0)
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Physiotherapist</th>
                        <th>Specialty</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appt)
                        <tr>
                            <td>
                                <div style="font-weight:600;color:#0f172a;">{{ $appt->names }} {{ $appt->last_names }}</div>
                                <div style="font-size:11.5px;color:#64748b;">{{ $appt->email }}</div>
                                @php
                                    $linkedPatient = \App\Models\Patient::where('email', $appt->email)->first();
                                @endphp
                                @if($linkedPatient)
                                <a href="{{ route('admin.patients.show', $linkedPatient) }}" style="font-size:11px;color:#6366f1;font-weight:700;font-family:monospace;text-decoration:none;" title="View patient profile & mark attendance">
                                    {{ $linkedPatient->patient_uid ?? '🔗 Profile' }}
                                </a>
                                @endif
                            </td>
                            <td>{{ $appt->physiotherapist->name ?? '—' }}</td>
                            <td>{{ $appt->specialty->name ?? '—' }}</td>
                            <td>
                                <div style="font-weight:500;">{{ $appt->fecha_cita->format('d M Y') }}</div>
                                <div style="font-size:12px;color:#64748b;">{{ $appt->hora_cita }}</div>
                            </td>
                            <td>
                                @php 
                                    $s = strtolower($appt->status); 
                                    $statusEng = ['pendiente' => 'Pending', 'confirmada' => 'Confirmed', 'completada' => 'Completed', 'cancelada' => 'Cancelled'];
                                @endphp
                                <span class="badge badge-{{ $s === 'pendiente' ? 'pending' : ($s === 'confirmada' ? 'confirmed' : ($s === 'completada' ? 'completed' : 'cancelled')) }}">
                                    {{ $statusEng[$s] ?? ucfirst($s) }}
                                </span>
                            </td>
                            <td style="text-align:right;">
                                <div style="display:flex;gap:.4rem;justify-content:flex-end;">
                                    @if($appt->status === 'pendiente')
                                        <form action="{{ route('admin.appointments.confirmar', $appt->id) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-success btn-sm">✓ Confirm</button>
                                        </form>
                                        <form action="{{ route('shared.appointments.cancelar', $appt->id) }}" method="POST" onsubmit="return confirm('Cancel this appointment?');">
                                            @csrf
                                            <button class="btn btn-danger btn-sm">✕ Cancel</button>
                                        </form>
                                    @else
                                        @if($appt->status !== 'cancelada')
                                            <form action="{{ route('shared.appointments.cancelar', $appt->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                                                @csrf
                                                <button type="submit" class="btn btn-warning btn-sm" style="background:#f59e0b; color:white; border:none; padding:4px 8px; border-radius:4px; font-size:12px; cursor:pointer;">✕ Cancel</button>
                                            </form>
                                        @endif
                                    @endif

                                    @if($appt->status !== 'completada' && $appt->status !== 'cancelada')
                                        <a href="{{ route('shared.appointments.edit', $appt->id) }}" class="btn btn-sm" style="background:#3b82f6; color:white; padding:4px 8px; border-radius:4px; font-size:12px; text-decoration:none;">🔄 Reschedule</a>
                                    @endif
                                    
                                    <form action="{{ route('shared.appointments.destroy', $appt->id) }}" method="POST" onsubmit="return confirm('WARNING: This will permanently delete the appointment. Are you sure?');">
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
            <div style="padding:1rem 1.25rem;">
                {{ $appointments->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">📅</div>
                <div class="empty-title">No appointments found</div>
                <div class="empty-sub">There are no appointments matching your criteria.</div>
            </div>
        @endif
    </div>
</div>
@endsection
