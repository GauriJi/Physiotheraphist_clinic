@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Detalles de la Specialty</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('specialties.edit', $specialty->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('specialties.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5>Información</h5>
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> {{ $specialty->id }}</p>
            <p><strong>Nombre:</strong> {{ $specialty->name }}</p>
            <p><strong>Descripción:</strong></p>
            <p>{{ $specialty->descripcion }}</p>
            <p><strong>Registrado:</strong> {{ $specialty->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Physiotherapists ({{ $specialty->physiotherapists->count() }})</h5>
        </div>
        <div class="card-body">
            @if ($specialty->physiotherapists->count() > 0)
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>{{ __('messages.name') }}</th>
                        <th>Email</th>
                        <th>Número Colegiatura</th>
                        <th>{{ __('messages.phone') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($specialty->physiotherapists as $fisiote)
                    <tr>
                        <td>{{ $fisiote->user->name ?? 'N/A' }} {{ $fisiote->user->last_name ?? '' }}</td>
                        <td>{{ $fisiote->user->email ?? 'N/A' }}</td>
                        <td>{{ $fisiote->numero_colegiatura }}</td>
                        <td>{{ $fisiote->user->phone ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="text-muted">No hay physiotherapists en esta specialty</p>
            @endif
        </div>
    </div>
</div>
@endsection
