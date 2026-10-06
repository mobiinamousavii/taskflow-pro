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
             --primary-light: #eef2ff;
             --text-main: #334155;
             --text-muted: #64748b;
             --border-color: #e2e8f0;
             --danger: #ef4444;
             --danger-hover: #dc2626;
             --sidebar-width: 260px;
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

         /* نوبار ثابت بالا */
         .navbar {
             position: fixed;
             top: 0;
             left: 0;
             right: 0;
             height: 70px;
             background: rgba(255, 255, 255, 0.95);
             backdrop-filter: blur(10px);
             border-bottom: 1px solid var(--border-color);
             display: flex;
             align-items: center;
             justify-content: space-between;
             padding: 0 30px;
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

         /* لایه‌بندی اصلی (سایدبار + محتوا) */
         .layout-container {
             display: flex;
             margin-top: 70px;
             min-height: calc(100vh - 70px);
         }

         /* سایدبار سمت راست */
         .sidebar {
             width: var(--sidebar-width);
             background: #ffffff;
             border-left: 1px solid var(--border-color);
             position: fixed;
             top: 70px;
             right: 0;
             bottom: 0;
             overflow-y: auto;
             padding: 20px 15px;
             display: flex;
             flex-direction: column;
             justify-content: space-between;
             z-index: 900;
         }

         .sidebar-menu {
             display: flex;
             flex-direction: column;
             gap: 6px;
         }

         .sidebar-item {
             display: flex;
             align-items: center;
             justify-content: space-between;
             padding: 10px 14px;
             color: #475569;
             font-size: 0.92rem;
             font-weight: 600;
             border-radius: 8px;
             cursor: pointer;
             transition: all 0.2s;
         }

         .sidebar-item:hover,
         .sidebar-item.active {
             background: var(--primary-light);
             color: var(--primary);
         }

         /* زیرمنوهای آکاردئونی */
         .submenu {
             display: none;
             flex-direction: column;
             gap: 4px;
             padding-right: 18px;
             margin-top: 4px;
         }

         .submenu.open {
             display: flex;
         }

         .submenu-item {
             display: block;
             padding: 8px 12px;
             font-size: 0.85rem;
             color: var(--text-muted);
             border-radius: 6px;
             transition: all 0.2s;
         }

         .submenu-item:hover,
         .submenu-item.active {
             color: var(--primary);
             background: var(--primary-light);
         }

         .arrow {
             font-size: 0.75rem;
             transition: transform 0.2s ease;
         }

         .arrow.rotate {
             transform: rotate(180deg);
         }

         /* محتوای اصلی */
         main {
             flex: 1;
             margin-right: var(--sidebar-width);
             padding: 30px;
             min-width: 0;
         }

         @media (max-width: 768px) {
             .sidebar {
                 display: none;
             }

             main {
                 margin-right: 0;
             }
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
                 <span>TaskFlow Pro</span>
             </a>
         </div>

         <div class="nav-auth">
             @guest
             <a href="{{ route('login') }}" class="btn btn-outline">ورود</a>
             <a href="{{ route('register') }}" class="btn btn-primary">ثبت‌نام</a>
             @endguest

             @auth
             <a href="{{ route('dashboard') }}" class="user-account-btn">
                 <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                 </svg>
                 <span>حساب کاربری ({{ auth()->user()->name ?? 'کاربر' }})</span>
             </a>

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
     <div class="layout-container">
         <!-- سایدبار راست با آکاردئون کشویی -->
         <aside class="sidebar">
             <div class="sidebar-menu">
                 <a href="{{ route('dashboard') }}" class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                     <span>📌 داشبورد اصلی</span>
                 </a>

                 <!-- آکاردئون فضاهای کاری -->
                 <div>
                     <div class="sidebar-item js-accordion">
                         <span>🏢 فضاهای کاری</span>
                         <span class="arrow">▼</span>
                     </div>
                     <div class="submenu {{ request()->routeIs('workspaces.*') ? 'open' : '' }}">
                         <a href="{{ route('workspaces.index') }}" class="submenu-item {{ request()->routeIs('workspaces.index') ? 'active' : '' }}">لیست فضاهای کاری</a>
                         <a href="{{ route('workspaces.create') }}" class="submenu-item {{ request()->routeIs('workspaces.create') ? 'active' : '' }}">ایجاد فضای کاری جدید</a>
                     </div>
                 </div>

                 <!-- آکاردئون پروژه‌ها -->
                 <div>
                     <div class="sidebar-item js-accordion">
                         <span>📂 پروژه‌ها</span>
                         <span class="arrow">▼</span>
                     </div>
                     <div class="submenu {{ request()->routeIs('projects.*') ? 'open' : '' }}">
                         <a href="{{ route('projects.index') }}" class="submenu-item {{ request()->routeIs('projects.index') ? 'active' : '' }}">لیست پروژه‌ها</a>
                         <a href="{{ route('projects.create') }}" class="submenu-item {{ request()->routeIs('projects.create') ? 'active' : '' }}">تعریف پروژه جدید</a>
                     </div>
                 </div>

                 <!-- آکاردئون تسک‌ها -->
                 <div>
                     <div class="sidebar-item js-accordion">
                         <span>📋 تسک‌ها</span>
                         <span class="arrow">▼</span>
                     </div>
                     <div class="submenu {{ request()->routeIs('tasks.*') ? 'open' : '' }}">
                         <a href="{{ route('tasks.index') }}" class="submenu-item {{ request()->routeIs('tasks.index') ? 'active' : '' }}">تسک‌های من</a>
                         <a href="{{ route('tasks.create') }}" class="submenu-item {{ request()->routeIs('tasks.create') ? 'active' : '' }}">ایجاد تسک جدید</a>
                     </div>
                 </div>
             </div>
         </aside>

         <!-- محتوای صفحات -->
         <main>
             @yield('content')
         </main>
     </div>

     <!-- اسکریپت آکاردئون‌ها -->
     <script>
         document.addEventListener('DOMContentLoaded', function() {
             const accordions = document.querySelectorAll('.js-accordion');
             accordions.forEach(acc => {
                 acc.addEventListener('click', function() {
                     const submenu = this.nextElementSibling;
                     const arrow = this.querySelector('.arrow');
                     if (submenu) submenu.classList.toggle('open');
                     if (arrow) arrow.classList.toggle('rotate');
                 });
             });
         });
     </script>

     @stack('scripts')
 </body>

 </html>