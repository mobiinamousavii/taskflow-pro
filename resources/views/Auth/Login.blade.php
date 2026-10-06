<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود به حساب کاربری | TaskFlow Pro</title>
    <!-- فونت وزیرمتن -->
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" />

    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Vazirmatn', -apple-system, sans-serif;
            background-color: #fafafa;
            color: #334155;
            direction: rtl;
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .auth-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            width: 100%;
            max-width: 420px;
            padding: 36px 32px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }

        .auth-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .auth-header h2 {
            font-size: 1.5rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .auth-header p {
            font-size: 0.88rem;
            color: #64748b;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 0.88rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.9rem;
            outline: none;
            transition: all 0.2s;
            direction: ltr;
            text-align: left;
        }

        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.82rem;
            margin-bottom: 24px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #475569;
            cursor: pointer;
        }

        .forgot-link {
            color: #6366f1;
            font-weight: 600;
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .btn-submit {
            width: 100%;
            padding: 11px;
            font-size: 0.95rem;
            background: var(--primary);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-submit:hover {
            background: var(--primary-hover);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .auth-footer {
            text-align: center;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
            font-size: 0.88rem;
            color: #64748b;
        }

        .auth-footer a {
            color: #6366f1;
            font-weight: 700;
            text-decoration: none;
        }

        .error-msg {
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h2>خوش آمدید!</h2>
                <p>برای ادامه وارد حساب کاربری خود شوید</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- ایمیل -->
                <div class="form-group">
                    <label for="email">آدرس ایمیل</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
                    @error('email')
                    <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <!-- رمز عبور -->
                <div class="form-group">
                    <label for="password">رمز عبور</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                    @error('password')
                    <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <!-- مرا به خاطر بسپار و فراموشی رمز -->
                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" id="remember">
                        <span>مرا به خاطر بسپار</span>
                    </label>
                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">رمز عبور را فراموش کرده‌اید؟</a>
                    @endif
                </div>

                <button type="submit" class="btn-submit">ورود به حساب</button>
            </form>

            <div class="auth-footer">
                حساب کاربری ندارید؟ <a href="{{ route('register') }}">ثبت‌نام کنید</a>
            </div>
        </div>
    </div>

</body>

</html>