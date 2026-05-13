@extends('layouts.app')
@php($showNavbar = false)
@section('content')
<div style="max-width: 800px; margin: 0 auto; padding-top: 2rem;">

    <h1>➕ Create User (Admin)</h1>

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

    @if(session('success'))
        <div style="background:#dcfce7; border:1px solid #bbf7d0; padding:.75rem; margin: .75rem 0; border-radius:6px; color:#065f46;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background:#fff1f2; border:1px solid #fecaca; padding:.75rem; margin: .75rem 0; border-radius:6px; color:#991b1b;">
            <ul style="margin:0; padding-left:1.25rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

        <div style="margin-bottom: 1rem;">
            <label>{{ __('messages.name') }}</label>
            <input type="text" name="name" value="{{ old('name') }}" required style="width:100%; padding: .5rem;">
            @error('name') <div style="color:#b91c1c; margin-top:.25rem;">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom: 1rem;">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required style="width:100%; padding: .5rem;">
            @error('email') <div style="color:#b91c1c; margin-top:.25rem;">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom: 1rem;">
            <label>Password</label>
            <input type="password" name="password" required style="width:100%; padding: .5rem;">
            @error('password') <div style="color:#b91c1c; margin-top:.25rem;">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom: 1rem;">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" required style="width:100%; padding: .5rem;">
        </div>

        <div style="margin-bottom: 1rem;">
            <label>Role</label>
            <select name="role_id" required style="width:100%; padding: .5rem;">
                <option value="">-- Select --</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ ucfirst($role->nombre_rol) }}</option>
                @endforeach
            </select>
            @error('role_id') <div style="color:#b91c1c; margin-top:.25rem;">{{ $message }}</div> @enderror
        </div>

        <div style="display:flex; gap: .5rem;">
            <a href="{{ route('admin.users.index') }}" class="btn" style="padding: .5rem 1rem; background:#ef4444;">Cancel</a>
            <button type="submit" style="padding: .5rem 1rem; background:#0066cc; color:white; border:none;">Create User</button>
        </div>
    </form>
</div>
@endsection
