@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Physiotherapists</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('physiotherapists.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nuevo Physiotherapist
            </a>
        </div>
    </div>

    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>{{ __('messages.name') }}</th>
                    <th>Specialty</th>
                    <th>Número Colegiatura</th>
                    <th>{{ __('messages.phone') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($physiotherapists as $physiotherapist)
                <tr>
                    <td>{{ $physiotherapist->id }}</td>
                    <td>{{ $physiotherapist->user->name ?? 'N/A' }} {{ $physiotherapist->user->last_name ?? '' }}</td>
                    <td>{{ $physiotherapist->specialty->name ?? 'N/A' }}</td>
                    <td>{{ $physiotherapist->numero_colegiatura }}</td>
                    <td>{{ $physiotherapist->user->phone ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('physiotherapists.show', $physiotherapist->id) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('physiotherapists.edit', $physiotherapist->id) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('physiotherapists.destroy', $physiotherapist->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No hay physiotherapists registrados</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $physiotherapists->links() }}
    </div>
</div>
@endsection
