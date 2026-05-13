<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – PhysioCare</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url('{{ asset("images/clinic_bg_premium.png") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
        }
        
        /* Darker overlay for better contrast */
        .overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.4) 0%, rgba(13, 148, 136, 0.2) 100%);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 1;
        }

        .auth-card {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 440px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 24px;
            padding: 3rem 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.4) inset;
            margin: 20px;
        }

        .brand {
            text-align: center;
            margin-bottom: 2rem;
        }
        .brand-logo {
            width: 72px;
            height: 72px;
            object-fit: contain;
            margin-bottom: 1rem;
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }
        .brand h1 {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.025em;
            margin-bottom: 0.5rem;
        }
        .brand p {
            font-size: 14px;
            color: #475569;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 1.25rem;
            position: relative;
        }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.5rem;
        }
        
        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-icon {
            position: absolute;
            left: 1rem;
            color: #94a3b8;
            font-size: 16px;
            transition: color 0.2s;
        }
        .form-control {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.75rem;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 12px;
            font-size: 14px;
            color: #1e293b;
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        .form-control:focus {
            outline: none;
            border-color: #0d9488;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.15);
        }
        .form-control:focus + .input-icon {
            color: #0d9488;
        }

        .auth-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
            margin-top: 0.5rem;
        }
        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 13.5px;
            color: #475569;
            cursor: pointer;
        }
        .remember-me input {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            border-color: #cbd5e1;
            accent-color: #0d9488;
            cursor: pointer;
        }
        .forgot-pwd {
            font-size: 13.5px;
            color: #0d9488;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }
        .forgot-pwd:hover {
            color: #115e59;
            text-decoration: underline;
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #0d9488 0%, #0891b2 100%);
            color: white;
            border: none;
            padding: 0.85rem;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 14px 0 rgba(13, 148, 136, 0.39);
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(13, 148, 136, 0.4);
        }

        .auth-footer {
            text-align: center;
            margin-top: 2rem;
            font-size: 14px;
            color: #475569;
        }
        .auth-footer a {
            color: #0d9488;
            font-weight: 600;
            text-decoration: none;
        }
        .auth-footer a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 13.5px;
            font-weight: 500;
            display: flex;
            gap: 0.5rem;
            align-items: flex-start;
        }
        .alert-danger {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }
        .alert-icon {
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    
    <div class="auth-card">
        <div class="brand">
            <img src="{{ asset('images/physiocare_logo_premium.png') }}" alt="PhysioCare Logo" class="brand-logo">
            <h1>Welcome to PhysioCare</h1>
            <p>Your Journey to Better Movement Starts Here.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="alert-icon"><i class="fa-solid fa-circle-exclamation"></i></div>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success">
                <div class="alert-icon"><i class="fa-solid fa-circle-check"></i></div>
                <div>{{ session('status') }}</div>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <div class="input-wrapper">
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="name@example.com">
                    <i class="fa-regular fa-envelope input-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" class="form-control" required placeholder="••••••••">
                    <i class="fa-solid fa-lock input-icon"></i>
                </div>
            </div>

            <div class="auth-actions">
                <label class="remember-me">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span>Remember me</span>
                </label>
                <a href="{{ route('password.request') }}" class="forgot-pwd">Forgot password?</a>
            </div>

            <button type="submit" class="btn-submit">
                Sign In
            </button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="{{ route('register') }}">Create an account</a>
        </div>
    </div>
</body>
</html>
