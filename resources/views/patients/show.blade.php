@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Detalles del Patient</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('patients.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5>Información del Patient</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID:</strong> {{ $patient->id }}</p>
                    <p><strong>User:</strong> {{ $patient->user->name ?? 'N/A' }} {{ $patient->user->last_name ?? '' }}</p>
                    <p><strong>Email:</strong> {{ $patient->user->email ?? 'N/A' }}</p>
                    <p><strong>Fecha de Nacimiento:</strong> {{ $patient->fecha_nacimiento }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Dirección:</strong> {{ $patient->address }}</p>
                    <p><strong>Teléfono:</strong> {{ $patient->phone }}</p>
                    <p><strong>Sexo:</strong> {{ $patient->sexo == 'M' ? __('messages.male') : __('messages.female') }}</p>
                    <p><strong>Registrado:</strong> {{ $patient->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5>Appointments ({{ $patient->appointments->count() }})</h5>
        </div>
        <div class="card-body">
            @if ($patient->appointments->count() > 0)
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>{{ __('messages.date') }}</th>
                        <th>{{ __('messages.time') }}</th>
                        <th>Physiotherapist</th>
                        <th>Motivo</th>
                        <th>{{ __('messages.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($patient->appointments as $appointment)
                    <tr>
                        <td>{{ $appointment->fecha_cita }}</td>
                        <td>{{ $appointment->hora_cita }}</td>
                        <td>{{ $appointment->physiotherapist->user->name ?? 'N/A' }}</td>
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

    <div class="card">
        <div class="card-header">
            <h5>Histories Clínicos ({{ $patient->histories->count() }})</h5>
        </div>
        <div class="card-body">
            @if ($patient->histories->count() > 0)
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>{{ __('messages.date') }}</th>
                        <th>Physiotherapist</th>
                        <th>{{ __('messages.diagnosis') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($patient->histories as $history)
                    <tr>
                        <td>{{ $history->created_at->format('d/m/Y') }}</td>
                        <td>{{ $history->physiotherapist->user->name ?? 'N/A' }}</td>
                        <td>{{ $history->diagnostico }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="text-muted">No hay histories clínicos registrados</p>
            @endif
        </div>
    </div>
</div>
@endsection
