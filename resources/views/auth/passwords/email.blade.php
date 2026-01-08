<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>

    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            background: linear-gradient(135deg, #7c3aed, #6d28d9);
            font-family: 'Segoe UI', sans-serif;
        }

        /* TOP RIGHT BACK */
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

        /* CENTER WRAPPER */
        .forgot-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* CARD */
        .forgot-card {
            background: #fff;
            width: 440px;
            padding: 40px 34px;
            border-radius: 16px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.18);
        }

        /* HEADING */
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
            line-height: 1.5;
        }

        /* INPUT */
        .input-box {
            position: relative;
            margin-bottom: 22px;
        }

        .input-box i {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            color: #7c3aed;
            font-size: 14px;
        }

        .input-box input {
            width: 100%;
            height: 46px;
            padding: 0 14px 0 36px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
        }

        .input-box input:focus {
            border-color: #7c3aed;
        }

        /* BUTTON */
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
 .alert-success {
    font-size: 14px;
    margin-bottom: 16px;
    color: green;
    padding: 0px 35px;
}
        /* ERROR */
.invalid-feedback {
    color: red;
    font-size: 12px;
    margin-top: 4px;
    display: block;
    padding: 0px 50px;
}   
    </style>
</head>

<body>

<div class="back-top">
    <a href="{{ route('login') }}">← Back to Login</a>
</div>

<div class="forgot-wrapper">
    <div class="forgot-card">
        <div class="forgot-tagline">
            Forgot your password? No problem.<br>
            Just enter your registered email address and we’ll send you a secure reset link.
        </div>

        @if (session('status'))
            <div class="alert alert-success">
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
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="submit-btn">
                Send Reset Link
            </button>
        </form>

    </div>
</div>

</body>
</html>
