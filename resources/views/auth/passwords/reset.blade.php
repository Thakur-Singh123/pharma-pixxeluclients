<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Reset Password</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Font Awesome -->
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
            /*BACK*/
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
            }
            /* CARD */
            .reset-card {
                background: #fff;
                width: 440px;
                padding: 40px 34px;
                border-radius: 16px;
                box-shadow: 0 30px 60px rgba(0,0,0,0.18);
            }
            .reset-heading {
                font-size: 26px;
                font-weight: 700;
                color: #111827;
                margin-bottom: 6px;
            }
            .reset-tagline {
                font-size: 15px;
                color: #6b7280;
                margin-bottom: 26px;
                line-height: 1.5;
            }
            .input-box {
                position: relative;
                margin-bottom: 22px;
            }
            .input-box i.fa-envelope,
            .input-box i.fa-lock {
                position: absolute;
                top: 50%;
                left: 14px;
                transform: translateY(-50%);
                color: #7c3aed;
                font-size: 14px;
            }
            .input-box .eye {
            position: absolute;
            top: 50%;
            right: 14px;
            left: auto;              
            transform: translateY(-50%);
            color: #7c3aed;
            font-size: 14px;
            cursor: pointer;
            z-index: 5;
            }
            .input-box input {
                width: 100%;
                height: 48px;
                padding-left: 42px;
                padding-right: 42px;    
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
                margin-top: 8px;
                padding-left: 8px;
                display: block;
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
            }
            .submit-btn:hover {
                opacity: 0.9;
            }
            .back-login {
                margin-top: 18px;
                text-align: center;
            }
            .back-login a {
                text-decoration: none;
                font-size: 14px;
                color: #6a1b9a;
                font-weight: 500;
            }
            .back-login a:hover {
                text-decoration: underline;
            }
            .readonly-input {
                background-color: #f3f4f6;   
                color: #6b7280;
                cursor: not-allowed;
            }
        </style>
   </head>
    <body>
        <div class="back-top">
            <a href="{{ route('login') }}">← Back to Login</a>
        </div>
        <div class="reset-card">
            <div class="reset-heading">Reset Password</div>
            <div class="reset-tagline">
                Secure your account by setting a new password.
            </div>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="input-box">
                    <i class="fas fa-envelope"></i>
                    <input type="email"
                        name="email"
                        value="{{ $email ?? old('email') }}"
                        placeholder="Email address"
                        readonly
                        class="readonly-input">
                    @error('email')
                        <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="input-box">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password"
                        name="password"
                        placeholder="New password"
                        required>
                    <i class="fas fa-eye eye" onclick="togglePassword('password', this)"></i>
                    @error('password')
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                    @enderror
                </div>
                <div class="input-box">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="confirm_password"
                        name="password_confirmation"
                        placeholder="Confirm new password"
                        required>
                    <i class="fas fa-eye eye" onclick="togglePassword('confirm_password', this)"></i>
                </div>
                <button type="submit" class="submit-btn">
                    Reset Password
                </button>
            </form>
        </div>
        <script>
            function togglePassword(id, icon) {
                const input = document.getElementById(id);
                if (input.type === "password") {
                    input.type = "text";
                    icon.classList.remove("fa-eye");
                    icon.classList.add("fa-eye-slash");
                } else {
                    input.type = "password";
                    icon.classList.remove("fa-eye-slash");
                    icon.classList.add("fa-eye");
                }
            }
        </script>
    </body>
</html>