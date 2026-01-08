<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>

    <!-- SAME CSS AS LOGIN -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        span.invalid-feedback {
            color: red;
            font-size: 12px;
        }

        .forgot-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .back-login {
            margin-top: 15px;
            text-align: center;
        }

        .back-login a {
            font-size: 14px;
            color: #6a1b9a;
            text-decoration: none;
            font-weight: 500;
        }

        .back-login a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
<div class="container">

    <!-- NO FLIP, DIRECT LOGIN SIDE -->
    <div class="cover">
        <div class="front">
            <img src="{{ asset('public/admin/images/frontImg.jpg') }}" alt="">
            <div class="text">
                <span class="text-1">Secure your account<br>with a new password</span>
                <span class="text-2">Reset & continue</span>
            </div>
        </div>
    </div>

    <div class="forms">
        <div class="form-content">

            <!-- RESET PASSWORD FORM -->
            <div class="login-form">
                <div class="forgot-title">Reset Password</div>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email -->
                    <div class="input-box">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email"
                               value="{{ $email ?? old('email') }}"
                               placeholder="Enter email address" required>

                        @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="input-box">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password"
                               placeholder="Enter new password" required>

                        @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="input-box">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password_confirmation"
                               placeholder="Confirm new password" required>
                    </div>

                    <!-- Submit -->
                    <div class="button input-box">
                        <input type="submit" value="Reset Password">
                    </div>

                    <!-- Back to login -->
                    <div class="back-login">
                        <a href="{{ route('login') }}">← Back to Login</a>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
</body>
</html>
