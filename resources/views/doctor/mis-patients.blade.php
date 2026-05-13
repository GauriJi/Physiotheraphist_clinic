@extends('admin.layouts.sidebar')
@section('title', 'My Patients')
@section('page-title', 'My Patients')
@section('breadcrumb', 'Doctor / My Patients')

@section('content')

{{-- Stats Row --}}
<div class="grid-3" style="margin-bottom: 1.5rem;">
    <div class="stat-card blue">
        <div class="stat-icon blue">👥</div>
        <div class="stat-value">{{ $estadisticas['total_pacientes'] }}</div>
        <div class="stat-label">Total Unique Patients</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon green">✅</div>
        <div class="stat-value">{{ $estadisticas['citas_completadas'] }}</div>
        <div class="stat-label">Completed Sessions</div>
    </div>
    <div class="stat-card amber">
        <div class="stat-icon amber">📅</div>
        <div class="stat-value">{{ $estadisticas['citas_proximas'] }}</div>
        <div class="stat-label">Upcoming Appointments</div>
    </div>
</div>

<div class="card">
    <div style="overflow-x:auto;">
        @if ($patients->count() > 0)
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Contact</th>
                        <th style="text-align:center;">Total Visits</th>
                        <th style="text-align:center;">Completed</th>
                        <th style="text-align:center;">Upcoming</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($patients as $p)
                        <tr>
                            <td>
                                @if($p['id'])
                                    <a href="{{ route('doctor.patients.show', $p['id']) }}" style="text-decoration: none;">
                                        <div style="display:flex; align-items:center; gap:.75rem;">
                                            <div style="width:36px; height:36px; border-radius:50%; background:linear-gradient(135deg,#3b82f6,#06b6d4); color:white; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px; box-shadow: 0 2px 4px rgba(59,130,246,0.3);">
                                                {{ strtoupper(substr($p['name'], 0, 1)) }}
                                            </div>
                                            <div style="font-weight:600; color:#3b82f6; transition: color 0.2s;" onmouseover="this.style.color='#2563eb'; this.style.textDecoration='underline';" onmouseout="this.style.color='#3b82f6'; this.style.textDecoration='none';">{{ $p['name'] }} {{ $p['last_name'] }}</div>
                                        </div>
                                    </a>
                                @else
                                    <div style="display:flex; align-items:center; gap:.75rem;">
                                        <div style="width:36px; height:36px; border-radius:50%; background:linear-gradient(135deg,#3b82f6,#06b6d4); color:white; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px;">
                                            {{ strtoupper(substr($p['name'], 0, 1)) }}
                                        </div>
                                        <div style="font-weight:600; color:#0f172a;">{{ $p['name'] }} {{ $p['last_name'] }}</div>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="font-size:13px; color:#374151; margin-bottom:2px;">{{ $p['email'] }}</div>
                                <div style="font-size:12px; color:#64748b;">{{ $p['phone'] }}</div>
                            </td>
                            <td style="text-align:center;">
                                <span class="badge" style="background:#f1f5f9; color:#475569;">{{ $p['citas_totales'] }}</span>
                            </td>
                            <td style="text-align:center;">
                                <span class="badge badge-completed">{{ $p['citas_completadas'] }}</span>
                            </td>
                            <td style="text-align:center;">
                                <span class="badge badge-pending">{{ $p['citas_proximas'] }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <div class="empty-icon">👥</div>
                <div class="empty-title">No patients yet</div>
                <div class="empty-sub">You have not attended any patients.</div>
            </div>
        @endif
    </div>
</div>
@endsection
