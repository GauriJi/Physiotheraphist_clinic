@extends('admin.layouts.sidebar')
@section('title', 'My Profile')
@section('page-title', 'My Profile')
@section('breadcrumb', 'Account / Profile')

@section('content')

<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header">
        <div class="card-title">📝 Personal Information</div>
    </div>
    <div class="card-body">
        @if (session('status') === 'profile-updated')
            <div class="alert alert-success">
                ✅ Profile updated successfully.
            </div>
        @endif
        
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')
            
            <div class="grid-2" style="margin-bottom: 1.5rem;">
                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}" required>
                    @error('name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', Auth::user()->email) }}" required>
                    @error('email')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">💾 Save Changes</button>
        </form>
    </div>
</div>

<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header">
        <div class="card-title">🔐 Change Password</div>
    </div>
    <div class="card-body">
        @if (session('status') === 'password-updated')
            <div class="alert alert-success">
                ✅ Password updated successfully.
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('PUT')
            
            <div class="form-group" style="max-width: 400px;">
                <label for="update_password_current_password" class="form-label">Current Password</label>
                <input type="password" id="update_password_current_password" name="current_password" class="form-control" required>
                @error('current_password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group" style="max-width: 400px;">
                <label for="update_password_password" class="form-label">New Password</label>
                <input type="password" id="update_password_password" name="password" class="form-control" required>
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group" style="max-width: 400px; margin-bottom: 1.5rem;">
                <label for="update_password_password_confirmation" class="form-label">Confirm New Password</label>
                <input type="password" id="update_password_password_confirmation" name="password_confirmation" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-success">🔄 Update Password</button>
        </form>
    </div>
</div>

<div class="card" style="border-color: #fecaca;">
    <div class="card-header" style="background: #fef2f2; border-bottom-color: #fecaca;">
        <div class="card-title" style="color: #991b1b;">⚠️ Danger Zone</div>
    </div>
    <div class="card-body">
        <p style="color: #475569; margin-bottom: 1.5rem; font-size: 14px;">
            Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
        </p>
        
        <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you want to permanently delete your account? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            
            <div class="form-group" style="max-width: 400px;">
                <label for="password" class="form-label" style="color: #991b1b;">Confirm Password to Delete</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required style="border-color: #fca5a5;">
                @error('userDeletion.password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-danger">🗑️ Delete Account</button>
        </form>
    </div>
</div>

@endsection
