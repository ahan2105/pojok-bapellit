<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Pojok Bapelit</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background: #f5f5f5;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: #ffffff;
            position: relative;
            overflow: hidden;
        }

        /* Black accent blocks */
        .login-wrapper::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -50px;
            width: 300px;
            height: 300px;
            background: #1a1a1a;
            border-radius: 40px;
            opacity: 0.8;
            z-index: 1;
        }

        .login-wrapper::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -100px;
            width: 250px;
            height: 250px;
            background: #2a2a2a;
            border-radius: 50%;
            opacity: 0.7;
            z-index: 1;
        }

        /* Landscape Background */
        .landscape-bg {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .login-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            max-width: 900px;
            width: 100%;
            background: white;
            border-radius: 12px;
            box-shadow: 
                0 20px 60px rgba(0, 0, 0, 0.08),
                inset -15px 0 20px rgba(0, 0, 0, 0.03),
                inset 15px 0 20px rgba(0, 0, 0, 0.01);
            overflow: hidden;
            position: relative;
            z-index: 10;
        }

        /* Left Side - Illustration */
        .login-illustration {
            background: #ffffff;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 500px;
            border-right: 1px solid #e0e0e0;
            box-shadow: inset -20px 0 30px rgba(0, 0, 0, 0.05);
        }

        .bird-container {
            width: 320px;
            height: 320px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .bird-svg {
            width: 100%;
            height: 100%;
        }

        /* Remove dots - no longer needed */
        .dots-container {
            display: none;
        }

        /* Bird Animations */
        @keyframes wingFlap {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(-8deg); }
        }

        @keyframes bobbing {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }

        @keyframes floatLeft {
            0%, 100% { transform: translateX(0px) translateY(0px); }
            50% { transform: translateX(-10px) translateY(-8px); }
        }

        @keyframes floatRight {
            0%, 100% { transform: translateX(0px) translateY(0px); }
            50% { transform: translateX(10px) translateY(-8px); }
        }

        @keyframes swayLeft {
            0%, 100% { transform: rotate(0deg) translateY(0px); }
            50% { transform: rotate(2deg) translateY(-6px); }
        }

        @keyframes swayRight {
            0%, 100% { transform: rotate(0deg) translateY(0px); }
            50% { transform: rotate(-2deg) translateY(-6px); }
        }

        .bird-wings {
            animation: wingFlap 0.6s ease-in-out infinite;
            transform-origin: center;
        }

        .bird-body {
            animation: bobbing 2s ease-in-out infinite;
        }

        .flower-left {
            animation: floatLeft 3s ease-in-out infinite;
        }

        .flower-right {
            animation: floatRight 3.2s ease-in-out infinite;
        }

        .flower-top {
            animation: swayLeft 2.8s ease-in-out infinite;
        }

        .flower-bottom {
            animation: swayRight 3.4s ease-in-out infinite;
        }

        /* Right Side - Form */
        .login-form-section {
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            margin-bottom: 32px;
            text-align: center;
        }

        .form-title {
            font-size: 32px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .form-subtitle {
            font-size: 14px;
            color: #999;
            font-weight: 400;
        }

        /* Alert */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 13px;
        }

        .alert-success {
            background: #f0f9ff;
            border: 1px solid #d0e8ff;
            color: #0c4a6e;
        }

        /* Form Group */
        .form-group {
            margin-bottom: 24px;
            animation: slideIn 0.5s ease-out forwards;
            opacity: 0;
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .form-group:nth-child(3) { animation-delay: 0.3s; }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #666;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-input {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            font-family: 'Figtree', sans-serif;
            background: #fafafa;
            color: #1a1a1a;
            transition: all 0.3s ease;
        }

        .form-input::placeholder {
            color: #999;
        }

        .form-input:focus {
            outline: none;
            border-color: #999;
            background: white;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.03);
        }

        .input-group {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
            background: none;
            border: none;
            padding: 4px;
            display: flex;
            align-items: center;
        }

        .toggle-password:hover {
            color: #1a1a1a;
        }

        /* Error Message */
        .error-message {
            color: #d32f2f;
            font-size: 12px;
            margin-top: 6px;
            font-weight: 500;
        }

        /* Forgot Password */
        .forgot-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 24px;
        }

        .forgot-link {
            font-size: 12px;
            color: #999;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .forgot-link:hover {
            color: #1a1a1a;
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 11px 24px;
            background: #4a4a4a;
            color: white;
            border: none;
            border-radius: 24px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            animation: slideIn 0.5s ease-out 0.4s both;
        }

        .submit-btn:hover {
            background: #1a1a1a;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
            }

            .login-illustration {
                display: none;
            }

            .login-form-section {
                padding: 40px 30px;
                order: 1;
            }

            .bird-container {
                width: 200px;
                height: 200px;
            }

            .form-title {
                font-size: 24px;
            }

            .form-input {
                font-size: 16px;
            }
        }

        @media (max-width: 600px) {
            .login-wrapper {
                padding: 12px;
            }

            .login-container {
                border-radius: 14px;
            }

            .login-illustration {
                min-height: 280px;
                padding: 28px 16px;
            }

            .login-form-section {
                padding: 28px 20px;
            }

            .bird-container {
                width: 220px;
                height: 220px;
            }

            .form-title {
                font-size: 21px;
                margin-bottom: 6px;
            }

            .form-subtitle {
                font-size: 12px;
            }

            .form-group {
                margin-bottom: 18px;
            }

            .form-label {
                font-size: 11px;
            }

            .form-input {
                padding: 10px 12px;
                font-size: 16px;
            }

            .forgot-link {
                font-size: 12px;
            }

            .submit-btn {
                padding: 10px 20px;
                font-size: 13px;
            }
        }

        @media (max-width: 480px) {
            .login-wrapper {
                padding: 8px;
                min-height: 100vh;
            }

            .login-container {
                max-width: 100%;
                border-radius: 12px;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            }

            .login-illustration {
                min-height: 240px;
                padding: 20px 12px;
            }

            .login-form-section {
                padding: 24px 16px;
            }

            .bird-container {
                width: 180px;
                height: 180px;
            }

            .form-header {
                margin-bottom: 20px;
            }

            .form-title {
                font-size: 19px;
                margin-bottom: 4px;
                letter-spacing: -0.3px;
            }

            .form-subtitle {
                font-size: 11px;
            }

            .form-group {
                margin-bottom: 15px;
            }

            .form-label {
                font-size: 10px;
                margin-bottom: 5px;
            }

            .form-input {
                padding: 9px 11px;
                font-size: 16px;
                border-radius: 5px;
            }

            .input-group {
                position: relative;
            }

            .toggle-password {
                right: 8px;
                width: 32px;
                height: 32px;
            }

            .error-message {
                font-size: 11px;
                margin-top: 4px;
            }

            .forgot-container {
                margin-bottom: 15px;
                text-align: center;
            }

            .forgot-link {
                font-size: 11px;
            }

            .checkbox-group {
                margin-bottom: 15px;
            }

            .checkbox-input {
                width: 16px;
                height: 16px;
            }

            .checkbox-label {
                margin-left: 6px;
                font-size: 11px;
            }

            .submit-btn {
                padding: 10px 18px;
                font-size: 12px;
                letter-spacing: 0.2px;
            }

            .alert {
                padding: 10px 12px;
                font-size: 12px;
                margin-bottom: 15px;
            }
        }

        @media (max-width: 380px) {
            .login-wrapper {
                padding: 6px;
            }

            .login-form-section {
                padding: 20px 14px;
            }

            .login-illustration {
                padding: 16px 10px;
            }

            .bird-container {
                width: 150px;
                height: 150px;
            }

            .form-title {
                font-size: 18px;
            }

            .form-input {
                font-size: 16px;
                padding: 8px 10px;
            }

            .submit-btn {
                padding: 9px 16px;
                font-size: 11px;
            }
        }

        /* Bird Animations */
        @keyframes wingFlap {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(-8deg); }
        }

        @keyframes bobbing {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }

        .bird-wings {
            animation: wingFlap 0.6s ease-in-out infinite;
            transform-origin: center;
        }

        .bird-body {
            animation: bobbing 2s ease-in-out infinite;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-container">
            
            <!-- Left Side: Illustration -->
            <div class="login-illustration">
                <div class="bird-container">
                    <svg class="bird-svg" viewBox="0 0 300 300" xmlns="http://www.w3.org/2000/svg">
                        <!-- LARGE LEFT FLOWER (animated) -->
                        <g class="flower-left">
                            <!-- Petals -->
                            <circle cx="50" cy="100" r="18" fill="#1DA1F2" opacity="0.9"/>
                            <circle cx="65" cy="115" r="18" fill="#1E88E5" opacity="0.9"/>
                            <circle cx="65" cy="85" r="18" fill="#1565C0" opacity="0.9"/>
                            <circle cx="50" cy="130" r="18" fill="#1976D2" opacity="0.9"/>
                            <circle cx="35" cy="115" r="18" fill="#2196F3" opacity="0.9"/>
                            <!-- Center -->
                            <circle cx="50" cy="107" r="14" fill="#64B5F6"/>
                        </g>

                        <!-- LARGE TOP FLOWER (animated) -->
                        <g class="flower-top">
                            <!-- Petals -->
                            <circle cx="150" cy="50" r="16" fill="#1565C0" opacity="0.95"/>
                            <circle cx="165" cy="60" r="16" fill="#1DA1F2" opacity="0.95"/>
                            <circle cx="165" cy="40" r="16" fill="#0D47A1" opacity="0.95"/>
                            <circle cx="150" cy="75" r="16" fill="#1E88E5" opacity="0.95"/>
                            <circle cx="135" cy="60" r="16" fill="#1976D2" opacity="0.95"/>
                            <!-- Center -->
                            <circle cx="150" cy="60" r="12" fill="#64B5F6"/>
                        </g>

                        <!-- LARGE RIGHT FLOWER (animated) -->
                        <g class="flower-right">
                            <!-- Petals -->
                            <circle cx="250" cy="110" r="18" fill="#1E88E5" opacity="0.9"/>
                            <circle cx="235" cy="125" r="18" fill="#1DA1F2" opacity="0.9"/>
                            <circle cx="235" cy="95" r="18" fill="#2196F3" opacity="0.9"/>
                            <circle cx="250" cy="140" r="18" fill="#1565C0" opacity="0.9"/>
                            <circle cx="265" cy="125" r="18" fill="#42A5F5" opacity="0.9"/>
                            <!-- Center -->
                            <circle cx="250" cy="117" r="14" fill="#64B5F6"/>
                        </g>

                        <!-- BOTTOM FLOWER LEFT (animated) -->
                        <g class="flower-bottom">
                            <!-- Petals -->
                            <circle cx="80" cy="240" r="15" fill="#0D47A1" opacity="0.85"/>
                            <circle cx="95" cy="250" r="15" fill="#1565C0" opacity="0.85"/>
                            <circle cx="95" cy="230" r="15" fill="#1976D2" opacity="0.85"/>
                            <circle cx="80" cy="265" r="15" fill="#1DA1F2" opacity="0.85"/>
                            <circle cx="65" cy="250" r="15" fill="#0D47A1" opacity="0.85"/>
                            <!-- Center -->
                            <circle cx="80" cy="248" r="11" fill="#42A5F5"/>
                        </g>

                        <!-- BOTTOM FLOWER RIGHT (animated) -->
                        <g class="flower-left">
                            <!-- Petals -->
                            <circle cx="220" cy="245" r="15" fill="#1E88E5" opacity="0.85"/>
                            <circle cx="205" cy="255" r="15" fill="#1DA1F2" opacity="0.85"/>
                            <circle cx="205" cy="235" r="15" fill="#2196F3" opacity="0.85"/>
                            <circle cx="220" cy="270" r="15" fill="#1976D2" opacity="0.85"/>
                            <circle cx="235" cy="255" r="15" fill="#1565C0" opacity="0.85"/>
                            <!-- Center -->
                            <circle cx="220" cy="253" r="11" fill="#64B5F6"/>
                        </g>

                        <!-- Decorative leaves -->
                        <g opacity="0.7">
                            <path d="M 40 80 Q 25 70 20 85 Q 30 95 40 80" stroke="#1a1a1a" fill="#2a2a2a" stroke-width="2"/>
                            <path d="M 260 95 Q 275 85 280 100 Q 270 110 260 95" stroke="#1a1a1a" fill="#2a2a2a" stroke-width="2"/>
                            <path d="M 30 140 Q 15 135 10 150 Q 25 160 30 140" stroke="#1a1a1a" fill="#2a2a2a" stroke-width="2"/>
                            <path d="M 270 150 Q 285 145 290 160 Q 275 170 270 150" stroke="#1a1a1a" fill="#2a2a2a" stroke-width="2"/>
                        </g>

                        <!-- Black accent circles behind bird -->
                        <circle cx="120" cy="150" r="40" fill="#1a1a1a" opacity="0.3"/>
                        <circle cx="180" cy="170" r="35" fill="#1a1a1a" opacity="0.25"/>
                        <rect x="130" y="110" width="40" height="40" fill="#1a1a1a" opacity="0.2" rx="8"/>

                        <!-- Bird Body - Twitter Blue -->
                        <g class="bird-body">
                            <!-- Main body - more streamlined -->
                            <ellipse cx="150" cy="160" rx="38" ry="45" fill="#1DA1F2"/>
                            
                            <!-- Breast -->
                            <ellipse cx="150" cy="165" rx="28" ry="35" fill="#1DA1F2"/>
                            
                            <!-- Head - rounder like Twitter bird -->
                            <circle cx="168" cy="135" r="24" fill="#1DA1F2"/>
                            
                            <!-- Eye -->
                            <circle cx="176" cy="130" r="5" fill="#1a1a1a"/>
                            <circle cx="177" cy="129" r="2" fill="white"/>
                            
                            <!-- Beak - pointed like Twitter -->
                            <path d="M 192 135 L 215 135 L 192 138 Z" fill="#1DA1F2"/>
                            
                            <!-- Wings - pointed and stylized -->
                            <g class="bird-wings">
                                <path d="M 135 155 Q 100 140 85 165 Q 100 175 135 168 Z" fill="#1DA1F2" stroke="#1DA1F2" stroke-width="1"/>
                                <path d="M 135 155 Q 100 140 85 165 Q 100 175 135 168 Z" fill="#1DA1F2" opacity="0.6"/>
                                <!-- Wing detail -->
                                <path d="M 125 160 Q 95 150 80 170" stroke="#1DA1F2" fill="none" stroke-width="1.5"/>
                            </g>
                            
                            <!-- Tail - elegant and pointed -->
                            <path d="M 105 160 Q 60 150 50 180 Q 65 185 105 170 Z" fill="#1DA1F2" stroke="#1DA1F2" stroke-width="1"/>
                            <path d="M 100 165 Q 65 155 55 175" stroke="#1DA1F2" fill="none" stroke-width="1"/>
                        </g>
                    </svg>
                </div>
            </div>

            <!-- Right Side: Form -->
            <div class="login-form-section">
                <div class="form-header">
                    <h1 class="form-title">Pojok Bapelit</h1>
                    <p class="form-subtitle">Welcome to Pojok Bapelit</p>
                </div>

                <!-- Alert -->
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email/Username -->
                    <div class="form-group">
                        <label for="login" class="form-label">username or Email</label>
                        <input 
                            id="login" 
                            type="text" 
                            name="login" 
                            value="{{ old('login') }}" 
                            required 
                            autofocus
                            placeholder="masukan email atau username anda"
                            class="form-input" />
                        @error('login')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                required
                                placeholder="masukan password anda"
                                class="form-input"
                                style="padding-right: 40px;" />
                            <button type="button" class="toggle-password" onclick="togglePassword()">
                                <svg id="eyeIcon" width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Forgot Password -->
                    <div class="forgot-container">
                        <span class="forgot-link" style="cursor: default;">
                            Forgot password? Contact admin
                        </span>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="submit-btn">Login</button>
                </form>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function togglePassword() {
            const pwd = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.innerHTML = '<path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28l.46.46A11.804 11.804 0 001 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3M7.53 9.8l1.55 1.55c-.05.21-.08.42-.08.65 0 1.66 1.34 3 3 3 .24 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zM11.84 9.02l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/>';
            } else {
                pwd.type = 'password';
                icon.innerHTML = '<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>';
            }
        }

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                html: `<div style="text-align: left; font-size: 14px;">
                    @foreach ($errors->all() as $error)
                        <p style="margin: 8px 0;">• {{ $error }}</p>
                    @endforeach
                </div>`,
                confirmButtonColor: '#4a4a4a'
            });
        @endif

        document.querySelector('form').addEventListener('submit', function() {
            const btn = this.querySelector('.submit-btn');
            btn.disabled = true;
            btn.textContent = 'Processing...';
        });
    </script>
</body>
</html>