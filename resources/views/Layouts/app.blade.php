 <!DOCTYPE html>
 <html lang="fa" dir="rtl">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>@yield('title', 'TaskFlow Pro | پلتفرم مدیریت پروژه')</title>
     <!-- فونت وزیرمتن -->
     <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" />

     <style>
         :root {
             --bg-main: #0f172a;
             --primary: #6366f1;
             --primary-hover: #4f46e5;
             --text-main: #334155;
             --border-color: #e2e8f0;
             --danger: #ef4444;
             --danger-hover: #dc2626;
         }

         * {
             box-sizing: border-box;
             margin: 0;
             padding: 0;
         }

         body {
             font-family: 'Vazirmatn', -apple-system, sans-serif;
             background-color: #fafafa;
             color: var(--text-main);
             direction: rtl;
         }

         a {
             text-decoration: none;
             color: inherit;
         }

         /* نوبار ثابت */
         .navbar {
             position: fixed;
             top: 0;
             left: 0;
             right: 0;
             height: 70px;
             background: rgba(255, 255, 255, 0.9);
             backdrop-filter: blur(10px);
             border-bottom: 1px solid var(--border-color);
             display: flex;
             align-items: center;
             justify-content: space-between;
             padding: 0 40px;
             z-index: 1000;
         }

         .nav-brand {
             display: flex;
             align-items: center;
             gap: 12px;
             font-weight: 800;
             font-size: 1.25rem;
             color: #0f172a;
         }

         .brand-logo {
             width: 36px;
             height: 36px;
             background: linear-gradient(135deg, #6366f1, #8b5cf6);
             border-radius: 10px;
             display: flex;
             align-items: center;
             justify-content: center;
             color: white;
             font-weight: bold;
         }

         .nav-links {
             display: flex;
             align-items: center;
             gap: 24px;
             list-style: none;
             font-size: 0.95rem;
             color: #475569;
             font-weight: 500;
         }

         .nav-links a:hover {
             color: var(--primary);
         }

         .nav-auth {
             display: flex;
             align-items: center;
             gap: 12px;
         }

         /* دکمه‌ها */
         .btn {
             padding: 8px 18px;
             border-radius: 8px;
             font-size: 0.9rem;
             font-weight: 600;
             transition: all 0.2s;
             cursor: pointer;
             display: inline-flex;
             align-items: center;
             gap: 6px;
         }

         .btn-outline {
             border: 1px solid #cbd5e1;
             color: #334155;
             background: #ffffff;
         }

         .btn-outline:hover {
             border-color: var(--primary);
             color: var(--primary);
         }

         .btn-primary {
             background: var(--primary);
             color: #ffffff;
             border: none;
         }

         .btn-primary:hover {
             background: var(--primary-hover);
             box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
         }

         .user-account-btn {
             background: #f1f5f9;
             color: #1e293b;
             border: 1px solid #e2e8f0;
             padding: 8px 16px;
             border-radius: 8px;
             font-weight: 600;
             font-size: 0.9rem;
             display: flex;
             align-items: center;
             gap: 8px;
         }

         .user-account-btn:hover {
             background: #e2e8f0;
         }

         .btn-logout {
             background: #fef2f2;
             color: var(--danger);
             border: 1px solid #fee2e2;
         }

         .btn-logout:hover {
             background: var(--danger);
             color: #ffffff;
             border-color: var(--danger);
         }

         main {
             margin-top: 70px;
         }
     </style>
     @stack('styles')


 </head>

 <body>

     <!-- نوبار اصلی و ثابت -->
     <nav class="navbar">
         <div style="display: flex; align-items: center; gap: 32px;">
             <a href="{{ url('/') }}" class="nav-brand">
                 <div class="brand-logo">T</div>
                 <span>TaskFlow</span>
             </a>
             <ul class="nav-links">
                 <li><a href="{{route('workspaces.create')}}">فضاهای کاری</a></li>
                 <li><a href="{{route('projects.create')}}">پروژه ها</a></li>
                 <li><a href="#pricing">تسک ها</a></li>
             </ul>
         </div>

         <div class="nav-auth">
             @guest
             <!-- وقتی کاربر لاگین نکرده است -->
             <a href="{{ route('login') }}" class="btn btn-outline">ورود</a>
             <a href="{{ route('register') }}" class="btn btn-primary">ثبت‌نام</a>
             @endguest

             @auth
             <!-- وقتی کاربر لاگین کرده است -->
             <a href="#" class="user-account-btn">
                 <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                 </svg>
                 <span>حساب کاربری</span>
             </a>

             <!-- دکمه خروج -->
             <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                 @csrf
                 <button type="submit" class="btn btn-logout">
                     <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                     </svg>
                     <span>خروج</span>
                 </button>
             </form>
             @endauth
         </div>
     </nav>

     <!-- محتوای صفحات -->
     <main>
         @yield('content')
     </main>

     @stack('scripts')
 </body>

 </html>