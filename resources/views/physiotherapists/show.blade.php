@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Detalles del Physiotherapist</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('physiotherapists.edit', $physiotherapist->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('physiotherapists.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5>Información del Physiotherapist</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID:</strong> {{ $physiotherapist->id }}</p>
                    <p><strong>Nombre:</strong> {{ $physiotherapist->user->name ?? 'N/A' }} {{ $physiotherapist->user->last_name ?? '' }}</p>
                    <p><strong>Email:</strong> {{ $physiotherapist->user->email ?? 'N/A' }}</p>
                    <p><strong>Specialty:</strong> {{ $physiotherapist->specialty->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Número Colegiatura:</strong> {{ $physiotherapist->numero_colegiatura }}</p>
                    <p><strong>Teléfono:</strong> {{ $physiotherapist->user->phone ?? 'N/A' }}</p>
                    <p><strong>Registrado:</strong> {{ $physiotherapist->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Appointments ({{ $physiotherapist->appointments->count() }})</h5>
        </div>
        <div class="card-body">
            @if ($physiotherapist->appointments->count() > 0)
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>{{ __('messages.date') }}</th>
                        <th>{{ __('messages.time') }}</th>
                        <th>Patient</th>
                        <th>Motivo</th>
                        <th>{{ __('messages.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($physiotherapist->appointments as $appointment)
                    <tr>
                        <td>{{ $appointment->fecha_cita }}</td>
                        <td>{{ $appointment->hora_cita }}</td>
                        <td>{{ $appointment->patient->user->name ?? 'N/A' }}</td>
                        <td>{{ $appointment->reason }}</td>
                        <td><span class="badge bg-secondary">{{ $appointment->status }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="text-muted">No hay appointments registradas</p>
            @endif
        </div>
    </div>
</div>
@endsection
