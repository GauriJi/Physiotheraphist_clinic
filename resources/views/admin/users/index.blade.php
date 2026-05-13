@extends('admin.layouts.sidebar')
@section('title', 'Manage Users')
@section('page-title', 'Manage Users')
@section('breadcrumb', 'Admin / Users')

@section('content')

<div style="margin-bottom: 1.5rem; display: flex; justify-content: flex-end;">
    <a href="{{ route('admin.users.create') }}" class="btn btn-success">➕ New User</a>
</div>

<div class="card">
    <div style="overflow-x:auto;">
        @if ($users->count() > 0)
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Registered Date</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>
                                <div style="font-weight:600;color:#0f172a;">{{ $user->name }}</div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @php
                                    $rol = strtolower($user->role->nombre_rol ?? 'patient');
                                    $color = '#3b82f6'; $bg = '#eff6ff'; // Default / Patient
                                    if($rol === 'admin') { $color = '#ef4444'; $bg = '#fef2f2'; }
                                    if($rol === 'doctor' || $rol === 'physiotherapist') { $color = '#10b981'; $bg = '#ecfdf5'; }
                                @endphp
                                <span class="badge" style="background:{{ $bg }};color:{{ $color }};">
                                    {{ ucfirst($rol) }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td style="text-align:right;">
                                <div style="display:flex; gap:0.4rem; justify-content:flex-end;">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary btn-sm">✏️ Edit</a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">🗑️ Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="text-align:center; padding:3rem;">
                <div style="font-size:48px; margin-bottom:1rem;">👥</div>
                <div style="font-size:18px; font-weight:700; color:#1f2937;">No Users Found</div>
                <div style="color:#6b7280;">Add a new user to get started.</div>
            </div>
        @endif
    </div>
</div>

@endsection
