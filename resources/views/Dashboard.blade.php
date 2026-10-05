  @extends('layouts.app')
 <!DOCTYPE html>
 <html lang="fa" dir="rtl">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>داشبورد مدیریت | TaskFlow Pro</title>
     <!-- Tailwind CSS -->
     <script src="https://cdn.tailwindcss.com"></script>
     <!-- Alpine.js برای مدیریت حالت منوها -->
     <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
     <!-- Vazirmatn Font -->
     <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />
     <style>
         body {
             font-family: 'Vazirmatn', sans-serif;
         }

         [x-cloak] {
             display: none !important;
         }
     </style>
 </head>

 <body class="bg-slate-50 text-slate-800 antialiased">

     <div class="min-h-screen flex flex-col md:flex-row">

         <!-- Sidebar / پنل سمت راست -->
         <aside class="w-full md:w-64 bg-white border-l border-slate-200 flex-shrink-0 flex flex-col justify-between shadow-sm">
             <div>
                 <!-- Brand / لوگو -->
                 <div class="h-16 flex items-center justify-between px-6 border-b border-slate-100">
                     <span class="text-xl font-bold text-blue-600">TaskFlow Pro</span>
                     <span class="text-xs bg-blue-50 text-blue-600 px-2.5 py-1 rounded-full font-medium">نسخه ۱.۰</span>
                 </div>

                 <!-- Navigation Links -->
                 <nav class="p-4 space-y-1">

                     <!-- اصلی -->
                     <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg bg-blue-50 text-blue-600">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                         </svg>
                         داشبورد اصلی
                     </a>

                     <!-- آکاردئون فضای کاری (Workspace) -->
                     <div x-data="{ open: false }" class="space-y-1">
                         <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 rounded-lg transition-colors">
                             <div class="flex items-center gap-3">
                                 <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                 </svg>
                                 <span>فضای کاری</span>
                             </div>
                             <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                             </svg>
                         </button>
                         <div x-show="open" x-cloak style="display: none;" class="pr-9 pl-2 space-y-1 text-sm text-slate-500">
                             <a href="{{route('workspaces.index')}}" class="block py-2 px-3 hover:text-blue-600 rounded-md hover:bg-slate-50">لیست فضاهای کاری</a>
                             <a href="{{route('workspaces.create')}}" class="block py-2 px-3 hover:text-blue-600 rounded-md hover:bg-slate-50">ایجاد فضای کاری جدید</a>
                             <a href="#" class="block py-2 px-3 hover:text-blue-600 rounded-md hover:bg-slate-50">مدیریت اعضا و دسترسی‌ها</a>
                         </div>
                     </div>
                     <!-- آکاردئون پروژه‌ها (Projects) -->
                     <div x-data="{ open: false }" class="space-y-1">
                         <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 rounded-lg transition-colors">
                             <div class="flex items-center gap-3">
                                 <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                 </svg>
                                 <span>پروژه‌ها</span>
                             </div>
                             <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                             </svg>
                         </button>
                         <div x-show="open" x-cloak style="display: none;" class="pr-9 pl-2 space-y-1 text-sm text-slate-500">
                             <a href="{{route('projects.index')}}" class="block py-2 px-3 hover:text-blue-600 rounded-md hover:bg-slate-50">لیست پروژه‌ها</a>
                             <a href="#" class="block py-2 px-3 hover:text-blue-600 rounded-md hover:bg-slate-50">تعریف پروژه جدید</a>
                             <a href="#" class="block py-2 px-3 hover:text-blue-600 rounded-md hover:bg-slate-50">بورد کانبان (Kanban)</a>
                         </div>
                     </div>

                     <!-- تسک‌ها -->
                     <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 rounded-lg transition-colors">
                         <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                         </svg>
                         تسک‌های من
                     </a>

                 </nav>
             </div>

             <!-- بخش پروفایل کاربر در پایین سایدبار -->
             <div class="p-4 border-t border-slate-100 flex items-center justify-between">
                 <div class="flex items-center gap-3">
                     <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">
                         {{ mb_substr(auth()->user()->name ?? 'ک', 0, 1) }}
                     </div>
                     <div>
                         <p class="text-sm font-semibold text-slate-700">{{ auth()->user()->name ?? 'کاربر' }}</p>
                         <p class="text-xs text-slate-400">توسعه‌دهنده</p>
                     </div>
                 </div>
                 <form method="POST" action="{{ route('logout') }}">
                     @csrf
                     <button type="submit" class="text-slate-400 hover:text-rose-600 p-1 rounded-md hover:bg-slate-50" title="خروج از حساب">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                         </svg>
                     </button>
                 </form>
             </div>
         </aside>

         <!-- Main Content Area / محتوای اصلی -->
         <main class="flex-1 flex flex-col min-w-0 overflow-hidden">

             <!-- Top Header -->
             <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6">
                 <h1 class="text-lg font-bold text-slate-800">خوش آمدید!</h1>
                 <div class="flex items-center gap-4">
                     <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium shadow-sm transition-colors flex items-center gap-2">
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                         </svg>
                         ایجاد تسک جدید
                     </button>
                 </div>
             </header>

             <!-- Main Body -->
             <div class="p-6 overflow-y-auto space-y-6">

                 <!-- کارت‌های خلاصه وضعیت (آماری) -->
                 <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                     <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                         <div>
                             <p class="text-xs font-medium text-slate-400 mb-1">تسک‌های تکمیل شده</p>
                             <h3 class="text-2xl font-bold text-slate-800">0</h3>
                         </div>
                         <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                             </svg>
                         </div>
                     </div>

                     <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                         <div>
                             <p class="text-xs font-medium text-slate-400 mb-1">تسک‌های در جریان</p>
                             <h3 class="text-2xl font-bold text-slate-800">0</h3>
                         </div>
                         <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                             </svg>
                         </div>
                     </div>

                     <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                         <div>
                             <p class="text-xs font-medium text-slate-400 mb-1">در انتظار انجام</p>
                             <h3 class="text-2xl font-bold text-slate-800">0</h3>
                         </div>
                         <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                             </svg>
                         </div>
                     </div>
                 </div>

                 <!-- بورد یا جدول تسک‌ها / وضعیت کانبان -->
                 <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm p-6">
                     <div class="flex items-center justify-between mb-6">
                         <h2 class="font-bold text-slate-800">بورد تسک‌ها (Kanban)</h2>
                         <span class="text-xs text-slate-400">آخرین بروزرسانی: امروز</span>
                     </div>

                     <!-- بخش بورد -->
                     <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                         <!-- ستون ۱ -->
                         <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                             <h4 class="text-sm font-semibold text-slate-700 mb-3 flex items-center justify-between">
                                 <span>📌 در انتظار انجام</span>
                                 <span class="bg-slate-200 text-slate-600 text-xs px-2 py-0.5 rounded-full">0</span>
                             </h4>
                             <div class="text-center py-8 text-xs text-slate-400 border-2 border-dashed border-slate-200 rounded-lg">
                                 تسکی در این ستون نیست
                             </div>
                         </div>

                         <!-- ستون ۲ -->
                         <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                             <h4 class="text-sm font-semibold text-slate-700 mb-3 flex items-center justify-between">
                                 <span>⚡ در حال انجام</span>
                                 <span class="bg-slate-200 text-slate-600 text-xs px-2 py-0.5 rounded-full">0</span>
                             </h4>
                             <div class="text-center py-8 text-xs text-slate-400 border-2 border-dashed border-slate-200 rounded-lg">
                                 تسکی در حال انجام نیست
                             </div>
                         </div>

                         <!-- ستون ۳ -->
                         <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                             <h4 class="text-sm font-semibold text-slate-700 mb-3 flex items-center justify-between">
                                 <span>✅ انجام شده</span>
                                 <span class="bg-slate-200 text-slate-600 text-xs px-2 py-0.5 rounded-full">0</span>
                             </h4>
                             <div class="text-center py-8 text-xs text-slate-400 border-2 border-dashed border-slate-200 rounded-lg">
                                 تسک تکمیل‌شده‌ای وجود ندارد
                             </div>
                         </div>
                     </div>
                 </div>

             </div>
         </main>

     </div>

 </body>

 </html>