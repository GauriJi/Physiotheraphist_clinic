<?php

namespace App\Http\Controllers;

use App\Models\CitaPublica;
use App\Models\Physiotherapist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard según el role.
     */
    public function index()
    {
        $user = Auth::user();
        $role = $user->role ? $user->role->nombre_rol : null;

        // Route to specific dashboard based on role
        if ($role === 'patient') {
            return $this->dashboardPaciente($user);
        } elseif (in_array($role, ['physiotherapist', 'doctor'])) {
            return $this->dashboardFisioterapeuta($user);
        } elseif ($role === 'admin') {
            return $this->dashboardAdmin($user);
        }

        return $this->dashboardDefault($user);
    }

    /**
     * Dashboard para Patients
     */
    private function dashboardPaciente($user)
    {
        $stats = [
            'citas_totales' => CitaPublica::where('email', $user->email)->count(),
            'citas_proximas' => CitaPublica::where('email', $user->email)
                ->where('fecha_cita', '>=', today())
                ->where('status', '!=', 'cancelada')
                ->count(),
            'citas_completadas' => CitaPublica::where('email', $user->email)
                ->where('status', 'confirmada')
                ->count(),
            'citas_canceladas' => CitaPublica::where('email', $user->email)
                ->where('status', 'cancelada')
                ->count(),
        ];

        $role = 'patient';
        return view('dashboard', compact('stats', 'role'));
    }

    /**
     * Dashboard para Physiotherapists
     */
    private function dashboardFisioterapeuta($user)
    {
        // Try to find physiotherapist by user_id first, then by email
        $physiotherapist = Physiotherapist::where('user_id', $user->id)
            ->with('specialty')
            ->first();

        if (!$physiotherapist) {
            $physiotherapist = Physiotherapist::where('email', $user->email)
                ->with('specialty')
                ->first();
        }

        if (!$physiotherapist) {
            // Fallback – show dashboard with empty data
            $citasHoy         = collect();
            $cantidadPendientes = 0;
            $totalPacientesMes  = 0;
            $historialesRecientes = collect();
            $stats = ['citas_proximas' => 0];
            return view('doctor.dashboard', compact(
                'citasHoy', 'cantidadPendientes', 'totalPacientesMes',
                'historialesRecientes', 'stats'
            ));
        }

        $citasHoy = CitaPublica::where('physiotherapist_id', $physiotherapist->id)
            ->whereDate('fecha_cita', today())
            ->with('specialty')
            ->orderBy('hora_cita')
            ->get();

        $cantidadPendientes = CitaPublica::where('physiotherapist_id', $physiotherapist->id)
            ->where('status', 'pendiente')
            ->count();

        $totalPacientesMes = CitaPublica::where('physiotherapist_id', $physiotherapist->id)
            ->whereMonth('fecha_cita', now()->month)
            ->distinct('email')
            ->count('email');

        $stats = [
            'citas_proximas' => CitaPublica::where('physiotherapist_id', $physiotherapist->id)
                ->where('fecha_cita', '>', today())
                ->where('status', '!=', 'cancelada')
                ->count(),
        ];

        $historialesRecientes = collect();
        try {
            $historialesRecientes = \App\Models\MedicalHistory::where('physiotherapist_id', $physiotherapist->id)
                ->with('patient')
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {}

        return view('doctor.dashboard', compact(
            'physiotherapist',
            'citasHoy',
            'cantidadPendientes',
            'totalPacientesMes',
            'historialesRecientes',
            'stats'
        ));
    }

    /**
     * Dashboard para Administrador
     */
    private function dashboardAdmin($user)
    {
        $stats = [
            'citas_totales'          => CitaPublica::count(),
            'citas_hoy'              => CitaPublica::whereDate('fecha_cita', today())->count(),
            'citas_pendientes'       => CitaPublica::where('status', 'pendiente')->count(),
            'citas_confirmadas'      => CitaPublica::where('status', 'confirmada')->count(),
            'pacientes_unicos'       => \App\Models\Patient::count(),
            'fisioterapeutas_totales'=> Physiotherapist::count(),
            'revenue_total'          => \App\Models\Invoice::where('status', 'paid')->sum('total'),
            'unpaid_invoices'        => \App\Models\Invoice::where('status', 'unpaid')->count(),
        ];

        // Revenue last 7 days for chart
        $revenueChart = collect(range(6, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo);
            return [
                'date'    => $date->format('d M'),
                'revenue' => \App\Models\Invoice::where('status', 'paid')
                    ->whereDate('paid_at', $date)->sum('total'),
            ];
        });

        $role          = 'admin';
        $citasRecientes = CitaPublica::with(['physiotherapist', 'specialty'])
            ->orderByDesc('created_at')->limit(8)->get();

        $recentPatients = \App\Models\Patient::orderByDesc('created_at')->limit(5)->get();

        return view('admin.dashboard', compact(
            'stats', 'role', 'citasRecientes', 'revenueChart', 'recentPatients'
        ));
    }

    /**
     * Dashboard por defecto (sin role asignado)
     */
    private function dashboardDefault($user)
    {
        $stats = [];
        $role = 'default';
        return view('dashboard', compact('stats', 'role'));
    }
}
