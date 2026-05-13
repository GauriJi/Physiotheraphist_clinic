@extends('admin.layouts.sidebar')
@section('title', 'My Availability')
@section('page-title', 'My Schedule')
@section('breadcrumb', 'Doctor / Availability')

@section('content')

<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <div class="card-title">📅 Weekly Availability</div>
            <div style="font-size: 13px; color: #64748b; margin-top: 2px;">
                {{ now()->startOfWeek()->format('M d') }} - {{ now()->endOfWeek()->format('M d, Y') }}
            </div>
        </div>
        <button onclick="window.print()" class="btn btn-ghost">
            🖨️ Print Schedule
        </button>
    </div>
    <div style="overflow-x:auto;">
        <table class="tbl" style="border-collapse: collapse; min-width: 800px;">
            <thead>
                <tr>
                    <th style="width: 80px; text-align: center; border-right: 1px solid #e2e8f0;">Time</th>
                    @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $dayLabel)
                        <th style="text-align: center; border-right: 1px solid #e2e8f0;">{{ $dayLabel }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                    $horas = ['08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00'];
                    $diasEspanol = ['lunes','martes','miercoles','jueves','viernes','sabado','domingo'];
                    $colores = ['#eff6ff', '#f5f3ff', '#f0fdf4', '#fffbeb', '#fef2f2', '#f3f4f6', '#ecfdf5'];
                    $textColores = ['#3b82f6', '#8b5cf6', '#10b981', '#f59e0b', '#ef4444', '#64748b', '#059669'];
                @endphp

                @foreach($horas as $time)
                    <tr>
                        <td style="text-align: center; border-right: 1px solid #e2e8f0; font-weight: 700; color: #64748b; font-size: 12px;">
                            {{ $time }}
                        </td>

                        @foreach($diasEspanol as $index => $dia)
                            @php
                                $schedule = $horariosPorDia[$dia]->firstWhere('hora_inicio', $time);
                                $bg = $colores[$index % count($colores)];
                                $tc = $textColores[$index % count($textColores)];
                            @endphp
                            <td style="border-right: 1px solid #e2e8f0; padding: 0.5rem; height: 65px; vertical-align: top;">
                                @if($schedule && $schedule->disponible)
                                    <div style="background: {{ $bg }}; color: {{ $tc }}; border: 1px solid {{ $tc }}33; border-radius: 6px; padding: 0.4rem; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
                                        <div style="font-size: 10px; font-weight: 700; text-transform: uppercase;">Available</div>
                                        <div style="font-size: 12px; font-weight: 800;">
                                            {{ \Carbon\Carbon::parse($schedule->hora_inicio)->format('H:i') }}
                                        </div>
                                    </div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
    @media print {
        .adm-sidebar { display: none !important; }
        .adm-main { margin-left: 0 !important; }
        .adm-topbar { display: none !important; }
        .btn { display: none !important; }
        body { background: white !important; }
        .card { border: none !important; box-shadow: none !important; }
    }
</style>

@endsection
