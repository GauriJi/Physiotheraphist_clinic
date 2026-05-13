@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Detalles del User</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5>Información del User</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID:</strong> {{ $user->id }}</p>
                    <p><strong>Nombre:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Role:</strong> <span class="badge bg-primary">{{ $user->role->nombre_rol ?? 'Sin role' }}</span></p>
                    <p><strong>Registrado:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Última actualización:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Sección de physiotherapist eliminada para User --}}
</div>
@endsection
