@extends('layouts.app', ['showNavbar' => false])


{{-- Dashboard styles moved to public/assets/css/home.css --}}

@section('content')
<div class="dashboard-wrapper">
        <!-- SIDEBAR -->
        <aside class="dashboard-sidebar">
            <div class="sidebar-profile">
                <div class="profile-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div class="profile-name">{{ Auth::user()->name }}</div>
                <div class="profile-role">
                    @if ($role === 'admin')
                        Administrator
                    @elseif ($role === 'physiotherapist')
                        Physiotherapist
                    @else
                        Patient
                    @endif
                </div>
            </div>

            <ul class="sidebar-menu">
                @if ($role === 'admin')
                    <li><a href="#" class="active">🏠 Dashboard</a></li>
                    <li><a href="{{ route('admin.users.index') }}">👥 Users</a></li>
                    <li><a href="{{ route('admin.doctors.index') }}">👨‍⚕️ Physiotherapists</a></li>
                    <li><a href="{{ route('admin.appointments.index') }}">📋 Appointments</a></li>
                    <li><a href="#">📊 Statistics</a></li>
                    <li><a href="{{ route('profile.edit') }}">👤 My Profile</a></li>
                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">🚪 Logout</a></li>
                @elseif ($role === 'physiotherapist')
                    <li><a href="#" class="active">🏠 Home</a></li>
                    <li><a href="{{ route('doctor.mis-appointments') }}">📅 My Appointments</a></li>
                    <li><a href="{{ route('doctor.mis-patients') }}">📋 Clinical Histories</a></li>
                    <li><a href="{{ route('doctor.mi-schedule') }}">🕒 My Schedule</a></li>
                    <li><a href="#">🔔 Notifications</a></li>
                    <li><a href="{{ route('profile.edit') }}">👤 My Profile</a></li>
                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">🚪 Logout</a></li>
                @else
                    <li><a href="#" class="active">🏠 Home</a></li>
                    <li><a href="{{ route('appointments.create') }}">📅 Book Appointment</a></li>
                    <li><a href="{{ route('profile.edit') }}">👤 My Profile</a></li>
                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">🚪 Logout</a></li>
                @endif
            </ul>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="dashboard-content">
            @if ($role === 'physiotherapist')
            <div class="page-header">
                <div>
                    <h1 class="page-title" style="font-size:2rem; font-weight:800; color:#0f172a; margin-bottom:.25rem;">
                        Welcome, Dr. {{ $physiotherapist->name }} {{ $physiotherapist->last_name }}
                    </h1>
                    <p class="page-subtitle" style="font-size:1.1rem; color:#0c457e; margin-bottom:0.5rem;">
                        Specialty: {{ $physiotherapist->specialty->name ?? 'No specialty assigned' }}<br>
                        Last session: {{ $ultimaSesion ?? 'No records' }}
                    </p>
                    <div style="margin-top:1.5rem; font-size:1.15rem; color:#0c457e; background:#e0f2fe; border-radius:10px; padding:1rem 1.5rem;">
                        You have <b>{{ $totalCitasHoy }}</b> appointments scheduled for today.<br>
                        Your next appointment is at <b>{{ $proximaCitaHora }}</b> with <b>{{ $proximaCitaPaciente }}</b>.
                    </div>
                </div>
            </div>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header"><div class="stat-label">Pending Appointments</div><div class="stat-icon warning">⏳</div></div>
                    <div class="stat-value">{{ $cantidadPendientes }}</div>
                    <div class="stat-change">To be attended</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header"><div class="stat-label">Patients seen this month</div><div class="stat-icon success">👥</div></div>
                    <div class="stat-value">{{ $totalPacientesMes }}</div>
                    <div class="stat-change">In the current month</div>
                </div>
            </div>
            <!-- Agenda del día -->
            <div class="content-section">
                <div class="section-header">
                    <div class="section-title">🗓️ Today's Schedule</div>
                </div>
                <table class="table" style="width:100%; margin-top:1rem;">
                    <thead>
                        <tr>
                            <th>{{ __('messages.time') }}</th>
                            <th>Patient</th>
                            <th>Reason</th>
                            <th>{{ __('messages.status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($citasHoy as $appointment)
                        <tr>
                            <td>{{ $appointment->hora_cita }}</td>
                            <td>{{ $appointment->names }} {{ $appointment->last_names }}</td>
                            <td>{{ $appointment->reason }}</td>
                            <td>{{ ucfirst($appointment->status) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Histories clínicos recientes -->
            <div class="content-section">
                <div class="section-header">
                    <div class="section-title">📋 Recent Clinical Histories</div>
                </div>
                <ul>
                    @foreach ($historialesRecientes as $history)
                        <li>
                            <b>{{ $history->patient->name }} {{ $history->patient->last_name }}</b> - {{ $history->descripcion }} <span style="color:#64748b;">({{ $history->created_at->format('d/m/Y') }})</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <!-- Notificaciones recientes -->
            <div class="content-section">
                <div class="section-header">
                    <div class="section-title">🔔 Recent Notifications</div>
                </div>
                <ul>
                    @foreach ($notificaciones as $noti)
                        <li>{{ $noti->mensaje }}</li>
                    @endforeach
                </ul>
            </div>
            @else
                <div class="page-header">
                    <div>
                        <h1 class="page-title">
                            @if ($role === 'patient')
                                Welcome, {{ Auth::user()->name }}
                            @elseif ($role === 'admin')
                                Admin Dashboard
                            @elseif ($role === 'physiotherapist')
                                Welcome, {{ Auth::user()->name }}
                            @else
                                Dashboard
                            @endif
                        </h1>
                        <p class="page-subtitle">
                            @if ($role === 'patient')
                                Manage your appointments and profile
                            @elseif ($role === 'admin')
                                Full clinic management
                            @endif
                        </p>
                    </div>
                    @if ($role === 'patient')
                        <a href="{{ route('appointments.create') }}" class="btn-primary-outline">➕ Book New Appointment</a>
                    @elseif ($role === 'admin')
                        <a href="#" class="btn-primary-outline">➕ Manage System</a>
                        <a href="{{ route('admin.users.index') }}" class="btn-primary-outline">⚙️ Management Panel</a>
                    @endif
                </div>
            @endif

            <!-- ESTADÍSTICAS SEGÚN ROL -->
            <div class="stats-grid">
                @if ($role === 'patient')
                    <!-- STATS PARA PACIENTES -->
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-label">Total Appointments</div>
                            <div class="stat-icon info">📊</div>
                        </div>
                        <div class="stat-value">{{ $stats['citas_totales'] ?? 0 }}</div>
                        <div class="stat-change">All your scheduled appointments</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-label">Upcoming Appointments</div>
                            <div class="stat-icon warning">📅</div>
                        </div>
                        <div class="stat-value">{{ $stats['citas_proximas'] ?? 0 }}</div>
                        <div class="stat-change">Pending appointments</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-label">Completed</div>
                            <div class="stat-icon success">✓</div>
                        </div>
                        <div class="stat-value">{{ $stats['citas_completadas'] ?? 0 }}</div>
                        <div class="stat-change">Finished appointments</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-label">Cancelled</div>
                            <div class="stat-icon danger">✕</div>
                        </div>
                        <div class="stat-value">{{ $stats['citas_canceladas'] ?? 0 }}</div>
                        <div class="stat-change">Cancelled appointments</div>
                    </div>

                @elseif ($role === 'doctor')
                    <!-- STATS PARA MÉDICOS -->
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-label">Total Appointments</div>
                            <div class="stat-icon info">📊</div>
                        </div>
                        <div class="stat-value">{{ $stats['citas_totales'] ?? 0 }}</div>
                        <div class="stat-change">In your history</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-label">Today's Appointments</div>
                            <div class="stat-icon warning">🔥</div>
                        </div>
                        <div class="stat-value">{{ $stats['citas_hoy'] ?? 0 }}</div>
                        <div class="stat-change">To be attended today</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-label">Upcoming</div>
                            <div class="stat-icon info">📅</div>
                        </div>
                        <div class="stat-value">{{ $stats['citas_proximas'] ?? 0 }}</div>
                        <div class="stat-change">Upcoming days</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-label">Unique Patients</div>
                            <div class="stat-icon success">👥</div>
                        </div>
                        <div class="stat-value">{{ $stats['pacientes_unicos'] ?? 0 }}</div>
                        <div class="stat-change">Attended</div>
                    </div>

                @elseif ($role === 'admin')
                    <!-- STATS PARA ADMIN -->
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-label">Total Appointments</div>
                            <div class="stat-icon info">📊</div>
                        </div>
                        <div class="stat-value">{{ $stats['citas_totales'] ?? 0 }}</div>
                        <div class="stat-change">In the system</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-label">Today's Appointments</div>
                            <div class="stat-icon warning">🔥</div>
                        </div>
                        <div class="stat-value">{{ $stats['citas_hoy'] ?? 0 }}</div>
                        <div class="stat-change">Scheduled for today</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-label">Pending</div>
                            <div class="stat-icon warning">⏳</div>
                        </div>
                        <div class="stat-value">{{ $stats['citas_pendientes'] ?? 0 }}</div>
                        <div class="stat-change">Awaiting confirmation</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-label">Confirmed</div>
                            <div class="stat-icon success">✓</div>
                        </div>
                        <div class="stat-value">{{ $stats['citas_confirmadas'] ?? 0 }}</div>
                        <div class="stat-change">Confirmed</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-label">Patients</div>
                            <div class="stat-icon info">👥</div>
                        </div>
                        <div class="stat-value">{{ $stats['pacientes_unicos'] ?? 0 }}</div>
                        <div class="stat-change">Unique</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-label">Physiotherapists</div>
                            <div class="stat-icon success">👨‍⚕️</div>
                        </div>
                        <div class="stat-value">{{ $stats['fisioterapeutas_totales'] ?? 0 }}</div>
                        <div class="stat-change">On staff</div>
                    </div>
                @endif
            </div>

            <!-- CONTENIDO ESPECÍFICO POR ROL -->
            @if ($role === 'patient')
                <!-- CITAS PROXIMAS PARA PACIENTES -->
                <div class="content-section">
                    <div class="section-header">
                        <div>
                            <div class="section-title">📅 Upcoming Appointments</div>
                            <p style="font-size: 13px; color: var(--gray-text); margin-top: 0.25rem;">
                                Your appointments scheduled for the coming days
                            </p>
                        </div>
                        <a href="/agendar-appointment" class="btn-small">➕ New Appointment</a>
                    </div>

                    @php
                        $citasProximas = \App\Models\CitaPublica::where('email', Auth::user()->email)
                            ->where('fecha_cita', '>=', today())
                            ->where('status', '!=', 'cancelada')
                            ->orderBy('fecha_cita')
                            ->limit(5)
                            ->get();
                    @endphp

                    @if ($citasProximas->count() > 0)
                        @foreach ($citasProximas as $appointment)
                            <div class="appointment-item">
                                <div class="appointment-date">
                                    <div class="appointment-day">{{ $appointment->fecha_cita->format('d') }}</div>
                                    <div class="appointment-month">{{ strtoupper($appointment->fecha_cita->format('M')) }}</div>
                                </div>
                                <div class="appointment-info">
                                    <div class="appointment-title">{{ $appointment->physiotherapist->name }} {{ $appointment->physiotherapist->last_name }}</div>
                                    <div class="appointment-details">
                                        <strong>{{ $appointment->specialty->name }}</strong> • {{ $appointment->hora_cita }}
                                    </div>
                                    <div class="appointment-details">{{ $appointment->reason }}</div>
                                    <span class="appointment-status {{ $appointment->status }}">{{ ucfirst($appointment->status) }}</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">📭</div>
                            <div class="empty-title">No appointments scheduled</div>
                            <div class="empty-text">Book your first appointment now!</div>
                            <a href="{{ route('appointments.create') }}" class="btn-small">Book Appointment</a>
                        </div>
                    @endif
                </div>

            @elseif ($role === 'doctor')
                <!-- CITAS DE HOY PARA MÉDICOS -->
                <div class="content-section">
                    <div class="section-header">
                        <div>
                            <div class="section-title">🔥 Today's Appointments</div>
                            <p style="font-size: 13px; color: var(--gray-text); margin-top: 0.25rem;">
                                Patients scheduled for today
                            </p>
                        </div>
                    </div>

                    @if (isset($citasHoy) && $citasHoy->count() > 0)
                        @foreach ($citasHoy as $appointment)
                            <div class="appointment-item">
                                <div class="appointment-date">
                                    <div class="appointment-day" style="color: var(--warning);">{{ $appointment->hora_cita }}</div>
                                    <div class="appointment-month">{{ __('messages.time') }}</div>
                                </div>
                                <div class="appointment-info">
                                    <div class="appointment-title">{{ $appointment->names }} {{ $appointment->last_names }}</div>
                                    <div class="appointment-details">
                                        📞 {{ $appointment->phone }} • 📧 {{ $appointment->email }}
                                    </div>
                                    <div class="appointment-details">
                                        <strong>{{ $appointment->specialty->name }}</strong> - {{ $appointment->reason }}
                                    </div>
                                    <span class="appointment-status {{ $appointment->status }}">{{ ucfirst($appointment->status) }}</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">😎</div>
                            <div class="empty-title">No appointments today</div>
                            <div class="empty-text">Enjoy your day off!</div>
                        </div>
                    @endif
                </div>

            @elseif ($role === 'admin')
                <!-- CITAS RECIENTES PARA ADMIN -->
                <div class="content-section">
                    <div class="section-header">
                        <div>
                            <div class="section-title">📋 Latest Booked Appointments</div>
                            <p style="font-size: 13px; color: var(--gray-text); margin-top: 0.25rem;">
                                Recent appointments in the system
                            </p>
                        </div>
                        <a href="#" class="btn-small">👁️ View All</a>
                    </div>

                    @if (isset($citasRecientes) && $citasRecientes->count() > 0)
                        @foreach ($citasRecientes as $appointment)
                            <div class="appointment-item">
                                <div class="appointment-date">
                                    <div class="appointment-day">{{ $appointment->fecha_cita->format('d') }}</div>
                                    <div class="appointment-month">{{ strtoupper($appointment->fecha_cita->format('M')) }}</div>
                                </div>
                                <div class="appointment-info">
                                    <div class="appointment-title">{{ $appointment->names }} {{ $appointment->last_names }}</div>
                                    <div class="appointment-details">
                                        👨‍⚕️ {{ $appointment->physiotherapist->name }} • 🏥 {{ $appointment->specialty->name }}
                                    </div>
                                    <div class="appointment-details">{{ $appointment->email }}</div>
                                    <span class="appointment-status {{ $appointment->status }}">{{ ucfirst($appointment->status) }}</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">📭</div>
                            <div class="empty-title">No appointments found</div>
                        </div>
                    @endif
                </div>

                <!-- GESTIÓN RÁPIDA PARA ADMIN -->
                <div class="content-section">
                    <div class="section-header">
                        <div class="section-title">⚙️ System Management</div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        <a href="{{ route('admin.users.index') }}" class="btn-users">
                            👥 Manage Users
                        </a>
                        <a href="{{ route('admin.doctors.index') }}" class="btn-doctors">
                            👨‍⚕️ Manage Physiotherapists
                        </a>
                        <a href="{{ route('admin.appointments.index') }}" class="btn-appointments">
                            📊 View Appointments
                        </a>
                        <a href="#" class="btn-config">
                            ⚙️ Settings
                        </a>
                    </div>
                </div>
            @endif

            <!-- ACCIONES RÁPIDAS -->
            @if ($role === 'patient')
            <div class="content-section">
                <div class="section-header">
                    <div class="section-title">⚡ Quick Actions</div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                    <a href="{{ route('appointments.create') }}" style="text-decoration: none; padding: 1.5rem; background: linear-gradient(135deg, rgba(0, 102, 204, 0.08), rgba(0, 212, 170, 0.08)); border: 2px solid var(--primary); border-radius: 10px; text-align: center; color: var(--primary); font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='linear-gradient(135deg, rgba(0, 102, 204, 0.15), rgba(0, 212, 170, 0.15))'" onmouseout="this.style.background='linear-gradient(135deg, rgba(0, 102, 204, 0.08), rgba(0, 212, 170, 0.95))'">
                        📅 Book New Appointment
                    </a>
                    <a href="{{ route('profile.edit') }}" style="text-decoration: none; padding: 1.5rem; background: linear-gradient(135deg, rgba(59, 130, 246, 0.08), rgba(248, 188, 37, 0.84)); border: 2px solid var(--info); border-radius: 10px; text-align: center; color: var(--info); font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(99, 102, 241, 0.15))'" onmouseout="this.style.background='linear-gradient(135deg, rgba(59, 130, 246, 0.08), rgba(99, 101, 241, 0.93))'">
                        👤 Update Profile
                    </a>
                    <a href="/" style="text-decoration: none; padding: 1.5rem; background: linear-gradient(135deg, rgba(16, 185, 129, 0.08), rgba(43, 255, 121, 0.8)); border: 2px solid var(--success); border-radius: 10px; text-align: center; color: var(--success); font-weight: 600; transition: all 0.2s;" onmouseover="this.style.background='linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(34, 197, 94, 0.15))'" onmouseout="this.style.background='linear-gradient(135deg, rgba(16, 185, 129, 0.08), rgba(34, 197, 94, 0.95))'">
                        🏠 Go to Home
                    </a>
                </div>
            </div>
            @endif
        </main>
    </div>
@endsection
