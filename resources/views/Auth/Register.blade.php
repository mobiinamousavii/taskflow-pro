 <!DOCTYPE html>
 <html lang="fa" dir="rtl">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>ساخت حساب کاربری جدید | TaskFlow Pro</title>
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
             max-width: 460px;
             padding: 36px 32px;
             box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
         }

         .auth-header {
             text-align: center;
             margin-bottom: 28px;
         }

         .auth-header h1 {
             font-size: 1.6rem;
             font-weight: 800;
             color: #0f172a;
             margin-bottom: 6px;
         }

         .auth-header p {
             font-size: 0.9rem;
             color: #64748b;
         }

         .form-group {
             margin-bottom: 18px;
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
         }

         .form-control.ltr {
             direction: ltr;
             text-align: left;
         }

         .form-control:focus {
             border-color: #6366f1;
             box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
         }

         .btn-submit {
             width: 100%;
             padding: 11px;
             font-size: 0.95rem;
             justify-content: center;
             margin-top: 10px;
             background: var(--primary);
             color: #ffffff;
             border: none;
             border-radius: 8px;
             font-weight: 600;
             cursor: pointer;
         }

         .btn-submit:hover {
             background: var(--primary-hover);
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
                 <h1>TaskFlow Pro</h1>
                 <p>حساب کاربری جدید خود را بسازید</p>
             </div>

             <form method="POST" action="{{ route('register') }}" autocomplete="off">
                 @csrf

                 <!-- نام و نام خانوادگی -->
                 <div class="form-group">
                     <label for="name">نام و نام خانوادگی</label>
                     <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" autocomplete="name" required autofocus>
                     @error('name')
                     <div class="error-msg">{{ $message }}</div>
                     @enderror
                 </div>
                 <!-- نام کاربری -->
                 <div class="form-group">
                     <label for="username">نام کاربری</label>
                     <input type="text" id="username" name="username" class="form-control ltr" value="{{ old('username') }}" autocomplete="off" required>
                     @error('username')
                     <div class="error-msg">{{ $message }}</div>
                     @enderror
                 </div>

                 <!-- ایمیل -->
                 <div class="form-group">
                     <label for="email">ایمیل</label>
                     <input type="email" id="email" name="email" class="form-control ltr" value="{{ old('email') }}" autocomplete="email" placeholder="name@example.com" required>
                     @error('email')
                     <div class="error-msg">{{ $message }}</div>
                     @enderror
                 </div>

                 <!-- شماره همراه (اختیاری) -->
                 <div class="form-group">
                     <label for="phone">شماره همراه (اختیاری)</label>
                     <input type="tel" id="phone" name="phone" class="form-control ltr" value="{{ old('phone') }}" autocomplete="tel" placeholder="09123456789">
                     @error('phone')
                     <div class="error-msg">{{ $message }}</div>
                     @enderror
                 </div>

                 <!-- رمز عبور -->
                 <div class="form-group">
                     <label for="password">رمز عبور</label>
                     <input type="password" id="password" name="password" class="form-control ltr" autocomplete="new-password" placeholder="••••••••" required>
                     @error('password')
                     <div class="error-msg">{{ $message }}</div>
                     @enderror
                 </div>

                 <!-- تکرار رمز عبور -->
                 <div class="form-group">
                     <label for="password_confirmation">تکرار رمز عبور</label>
                     <input type="password" id="password_confirmation" name="password_confirmation" class="form-control ltr" autocomplete="new-password" placeholder="••••••••" required>
                 </div>

                 <button type="submit" class="btn-submit">ایجاد حساب کاربری</button>
             </form>

             <div class="auth-footer">
                 قبلاً ثبت‌نام کرده‌اید؟ <a href="{{ route('login') }}">وارد شوید</a>
             </div>
         </div>
     </div>

 </body>

 </html>