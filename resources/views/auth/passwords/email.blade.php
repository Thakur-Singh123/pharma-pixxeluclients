<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Forgot Password</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <style>
            * {
                box-sizing: border-box;
                font-family: 'Segoe UI', sans-serif;
            }
            body {
                background: linear-gradient(135deg, #7c3aed, #6d28d9);
                min-height: 100vh;
                margin: 0;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .back-top {
                position: fixed;
                top: 24px;
                right: 30px;
            }
            .back-top a {
                color: #fff;
                font-size: 12px;
                text-decoration: none;
                font-weight: 500;
                opacity: 0.9;
            }
            .back-top a:hover {
                text-decoration: underline;
                opacity: 1;
            }
            .forgot-card {
                background: #fff;
                width: 440px;
                padding: 40px 34px;
                border-radius: 16px;
                box-shadow: 0 30px 60px rgba(0,0,0,0.18);
            }
            .forgot-heading {
                font-size: 26px;
                font-weight: 700;
                color: #111827;
                margin-bottom: 6px;
            }
            .forgot-tagline {
                font-size: 15px;
                color: #6b7280;
                margin-bottom: 26px;
                line-height: 1.6;
            }
            .alert-success {
                padding: 12px 15px;
                border-radius: 8px;
                font-size: 14px;
                text-align: center;
                color: green;
            }
            .input-box {
                position: relative;
                margin-bottom: 22px;
            }
            .input-box i {
                position: absolute;
                top: 50%;
                left: 14px;
                transform: translateY(-50%);
                color: #7c3aed;
                font-size: 14px;
            }
            .input-box input {
                width: 100%;
                height: 46px;
                padding: 0 14px 0 42px;
                border: 1px solid #e5e7eb;
                border-radius: 10px;
                font-size: 14px;
                outline: none;
            }
            .input-box input:focus {
                border-color: #7c3aed;
            }
            .invalid-feedback {
                color: red;
                font-size: 12px;
                margin-top: 6px;
                display: block;
                padding-left: 42px;
            }
            .submit-btn {
                width: 100%;
                height: 46px;
                background: linear-gradient(135deg, #7c3aed, #6d28d9);
                border: none;
                border-radius: 10px;
                color: #fff;
                font-size: 15px;
                font-weight: 600;
                cursor: pointer;
                transition: 0.2s;
            }
            .submit-btn:hover {
                opacity: 0.9;
            }
        </style>
    </head>
    <body>
        <div class="back-top">
            <a href="{{ route('login') }}">← Back to Login</a>
        </div>
        <div class="forgot-card">
            <div class="forgot-heading">Forgot Password</div>
            <div class="forgot-tagline">
                No worries! Enter your registered email address and we’ll send you a secure password reset link.
            </div>
            @if (session('status'))
            <div class="alert-success">
                {{ session('status') }}
            </div>
            @endif
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="input-box">
                    <i class="fas fa-envelope"></i>
                    <input type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Enter your email address"
                        required autofocus>
                    @error('email')
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                <button type="submit" class="submit-btn">
                    Send Reset Link
                </button>
            </form>
        </div>
    </body>
</html>