@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Detalles del Role</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5>Información del Role</h5>
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> {{ $role->id }}</p>
            <p><strong>Nombre:</strong> {{ $role->nombre_rol }}</p>
            <p><strong>Descripción:</strong></p>
            <p>{{ $role->descripcion }}</p>
            <p><strong>Registrado:</strong> {{ $role->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Users Asignados ({{ $role->users->count() }})</h5>
        </div>
        <div class="card-body">
            @if ($role->users->count() > 0)
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>{{ __('messages.name') }}</th>
                        <th>Email</th>
                        <th>{{ __('messages.phone') }}</th>
                        <th>Registrado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($role->users as $user)
                    <tr>
                        <td>{{ $user->name }} {{ $user->last_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="text-muted">No hay users asignados a este role</p>
            @endif
        </div>
    </div>
</div>
@endsection
