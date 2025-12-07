<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - School SIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
            background: #1a1f3a;
            position: relative;
            overflow: hidden;
        }

        .main-wrapper {
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        .login-container {
            display: flex;
            flex: 1;
            min-height: 0;
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        /* Left Section - Branding */
        .branding-section {
            flex: 1.4;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: flex-start;
            padding: 4rem 5rem;
            color: white;
            position: relative;
            overflow-y: auto;
            min-width: 0;
        }

        .branding-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('{{ asset("images/Mask group.svg") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.4;
            filter: brightness(1.6) contrast(1.3);
            z-index: 0;
        }

        .branding-section > * {
            position: relative;
            z-index: 1;
        }

        .logo-container {
            display: flex;
            align-items: center;
            margin-bottom: 6rem;
        }

        .logo-image {
            width: 120px;
            height: 120px;
            margin-right: 22px;
            object-fit: contain;
        }

        .logo-text {
            font-size: 48px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .branding-content {
            max-width: 600px;
        }

        .branding-heading {
            font-size: 80px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 2.5rem;
            letter-spacing: -1px;
        }

        .branding-subtext {
            font-size: 28px;
            opacity: 0.9;
            line-height: 1.6;
        }

        /* Right Section - Login Form */
        .form-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4rem;
            background: #1a1f3a;
            overflow-y: auto;
            min-width: 0;
        }

        .login-form-card {
            width: 100%;
            max-width: 650px;
            background: white;
            border-radius: 12px;
            padding: 5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .form-group {
            margin-bottom: 3rem;
        }

        .form-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 1.25rem;
            display: block;
            font-size: 20px;
        }

        .form-control {
            width: 100%;
            padding: 22px 26px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 20px;
            transition: all 0.2s;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-control::placeholder {
            color: #999;
        }

        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 3.5rem;
        }

        .signup-link {
            color: #666;
            text-decoration: none;
            font-size: 20px;
        }

        .signup-link:hover {
            color: #667eea;
        }

        .btn-login {
            background: #5b9bd5;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 22px 56px;
            font-weight: 600;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-login:hover {
            background: #4a8bc4;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(91, 155, 213, 0.3);
        }

        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 1.5rem;
        }

        .copyright {
            text-align: center;
            color: white;
            font-size: 16px;
            z-index: 2;
        }

        /* Responsive Design */
        @media (max-width: 968px) {
            .login-container {
                flex-direction: column;
                overflow-y: auto;
            }

            .branding-section {
                padding: 2rem;
                min-height: 0;
                flex-shrink: 0;
            }

            .branding-heading {
                font-size: 36px;
            }

            .form-section {
                padding: 2rem;
                flex-shrink: 0;
            }
        }

        @media (max-width: 576px) {
            .branding-heading {
                font-size: 28px;
            }

            .branding-subtext {
                font-size: 16px;
            }

            .login-form-card {
                padding: 2rem;
            }

            .form-footer {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .btn-login {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="main-wrapper">
        <div class="login-container">
            <!-- Left Section - Branding -->
            <div class="branding-section">
                <div>
                <div class="logo-container">
                    <img src="{{ asset('assets/img/logo.svg') }}" alt="Logo" class="logo-image">
                    <span class="logo-text">School SIS</span>
                </div>
                <div class="branding-content">
                    <h1 class="branding-heading">Login into your account</h1>
                        <p class="branding-subtext">Welcome to our Student Information System!</p>
                    </div>
                </div>
            </div>

            <!-- Right Section - Login Form -->
            <div class="form-section">
                <div class="login-form-card">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="username" class="form-label">Email</label>
                            <input type="text" 
                                   class="form-control @error('username') is-invalid @enderror" 
                                   id="username"
                                   name="username" 
                                   placeholder="name@example.com"
                                   value="{{ old('username') }}"
                                   required>
                            @error('username')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password"
                                   name="password" 
                                   placeholder="Your password"
                                   required>
                            @error('password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-footer">
                            <a href="{{ route('register') }}" class="signup-link">Don't have an account? Sign up</a>
                            <button type="submit" class="btn-login">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="copyright">
            © 2025 Monther Tuwati. All Rights Reserved.
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>
