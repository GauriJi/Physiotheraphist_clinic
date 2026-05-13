@extends('admin.layouts.sidebar')
@section('title', 'Manage Physiotherapists')
@section('page-title', 'Manage Physiotherapists')
@section('breadcrumb', 'Admin / Doctors')

@section('content')

<div style="margin-bottom: 1.5rem; display: flex; justify-content: flex-end;">
    <a href="{{ route('admin.doctors.create') }}" class="btn btn-success">➕ New Physiotherapist</a>
</div>

<div class="card">
    <div style="overflow-x:auto;">
        @if ($doctors->count() > 0)
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Specialty</th>
                        <th>License No.</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($doctors as $doctor)
                        <tr>
                            <td>
                                <div style="font-weight:600;color:#0f172a;">{{ $doctor->name }} {{ $doctor->last_name }}</div>
                            </td>
                            <td>{{ $doctor->email }}</td>
                            <td>{{ $doctor->phone }}</td>
                            <td>
                                <span class="badge" style="background:#eff6ff;color:#3b82f6;">
                                    {{ $doctor->specialty->name ?? '—' }}
                                </span>
                            </td>
                            <td>{{ $doctor->numero_colegiado }}</td>
                            <td style="text-align:right;">
                                <div style="display:flex; gap:0.4rem; justify-content:flex-end;">
                                    <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-primary btn-sm">✏️ Edit</a>
                                    <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this physiotherapist?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">🗑️ Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            @if($doctors->hasPages())
            <div style="padding:1rem 1.25rem; border-top:1px solid #e2e8f0;">
                {{ $doctors->links() }}
            </div>
            @endif
        @else
            <div style="text-align:center; padding:3rem;">
                <div style="font-size:48px; margin-bottom:1rem;">👨‍⚕️</div>
                <div style="font-size:18px; font-weight:700; color:#1f2937;">No Physiotherapists Found</div>
                <div style="color:#6b7280;">Add a new physiotherapist to get started.</div>
            </div>
        @endif
    </div>
</div>

@endsection
