@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Detalles de la Appointment</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('appointments.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Información de la Appointment</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID:</strong> {{ $appointment->id }}</p>
                    <p><strong>Patient:</strong> {{ $appointment->patient->user->name ?? 'N/A' }} {{ $appointment->patient->user->last_name ?? '' }}</p>
                    <p><strong>Email Patient:</strong> {{ $appointment->patient->user->email ?? 'N/A' }}</p>
                    <p><strong>Teléfono:</strong> {{ $appointment->patient->user->phone ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Physiotherapist:</strong> {{ $appointment->physiotherapist->user->name ?? 'N/A' }} {{ $appointment->physiotherapist->user->last_name ?? '' }}</p>
                    <p><strong>Specialty:</strong> {{ $appointment->physiotherapist->specialty->name ?? 'N/A' }}</p>
                    <p><strong>Número Colegiatura:</strong> {{ $appointment->physiotherapist->numero_colegiatura }}</p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Fecha:</strong> {{ $appointment->fecha_cita }}</p>
                    <p><strong>Hora:</strong> {{ $appointment->hora_cita }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Estado:</strong>
                        @if ($appointment->status == 'pendiente')
                            <span class="badge bg-warning">{{ __('messages.pending') }}</span>
                        @elseif ($appointment->status == 'completada')
                            <span class="badge bg-success">{{ __('messages.completed') }}</span>
                        @else
                            <span class="badge bg-danger">{{ __('messages.cancelled') }}</span>
                        @endif
                    </p>
                </div>
            </div>
            <hr>
            <p><strong>Motivo:</strong></p>
            <p>{{ $appointment->reason }}</p>
            <p><strong>Registrada:</strong> {{ $appointment->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</div>
@endsection
