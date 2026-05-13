@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Detalles del History Clínico</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('histories.edit', $history->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('histories.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Información del History</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>ID:</strong> {{ $history->id }}</p>
                    <p><strong>Patient:</strong> {{ $history->patient->user->name ?? 'N/A' }} {{ $history->patient->user->last_name ?? '' }}</p>
                    <p><strong>Email Patient:</strong> {{ $history->patient->user->email ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Physiotherapist:</strong> {{ $history->physiotherapist->user->name ?? 'N/A' }} {{ $history->physiotherapist->user->last_name ?? '' }}</p>
                    <p><strong>Specialty:</strong> {{ $history->physiotherapist->specialty->name ?? 'N/A' }}</p>
                    <p><strong>Registrado:</strong> {{ $history->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            <hr>
            <p><strong>Descripción:</strong></p>
            <p>{{ $history->descripcion }}</p>
            <hr>
            <p><strong>Diagnóstico:</strong></p>
            <p>{{ $history->diagnostico }}</p>
            <hr>
            <p><strong>Tratamiento:</strong></p>
            <p>{{ $history->tratamiento }}</p>
        </div>
    </div>
</div>
@endsection
