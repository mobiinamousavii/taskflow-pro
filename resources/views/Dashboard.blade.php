  @extends('layouts.app')
 <!DOCTYPE html>
 <html lang="fa" dir="rtl">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>داشبورد مدیریت پروژه</title>
     <script src="https://cdn.tailwindcss.com"></script>
     <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />
     <style>
         body {
             font-family: 'Vazirmatn', sans-serif;
         }
     </style>
 </head>

 <body class="bg-gray-100 flex h-screen overflow-hidden">

     <!-- Sidebar -->
     <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col justify-between p-4">
         <div>
             <div class="flex items-center gap-3 px-2 py-4 border-b border-slate-800">
                 <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold">T</div>
                 <span class="text-xl font-bold text-white">TaskFlow Pro</span>
             </div>

             <nav class="mt-6 space-y-2">
                 <a href="{{route('workspaces.index')}}" class="flex items-center gap-3 px-4 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-medium">
                     <span>📊</span> فضاهای کاری
                 </a>
                 <a href="{{route('projects.index')}}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-800 rounded-xl text-sm font-medium transition">
                     <span>📁</span> پروژه‌ها
                 </a>
                 <a href="#" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-800 rounded-xl text-sm font-medium transition">
                     <span>✅</span> تسک‌های من
                 </a>
             </nav>
         </div>

         <form action="{{ route('logout') }}" method="POST">
             @csrf
             <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-rose-400 hover:bg-slate-800 rounded-xl text-sm transition">
                 <span>🚪</span> خروج از حساب
             </button>
         </form>
     </aside>

     <!-- Main Content -->
     <main class="flex-1 flex flex-col overflow-y-auto">
         <!-- Top Navbar -->
         <header class="bg-white border-b border-gray-200 px-8 py-4 flex justify-between items-center">
             <h1 class="text-2xl font-bold text-gray-800">خوش آمدید، {{ Auth::user()->name }} 👋</h1>
             <!-- <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition">
                 + تسک جدید
             </button> -->
         </header>

         <div class="p-8 space-y-8">
             <!-- Stats Grid -->
             <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                 <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                     <div>
                         <p class="text-sm text-gray-500 font-medium">پروژه‌های فعال</p>
                         <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_projects'] }}</p>
                     </div>
                     <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl">📁</div>
                 </div>

                 <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                     <div>
                         <p class="text-sm text-gray-500 font-medium">تسک‌های در جریان</p>
                         <p class="text-3xl font-bold text-amber-600 mt-1">{{ $stats['pending_tasks'] }}</p>
                     </div>
                     <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl">⏳</div>
                 </div>

                 <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                     <div>
                         <p class="text-sm text-gray-500 font-medium">تسک‌های تکمیل‌شده</p>
                         <p class="text-3xl font-bold text-emerald-600 mt-1">{{ $stats['completed_tasks'] }}</p>
                     </div>
                     <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl">🎉</div>
                 </div>
             </div>

             <!-- Kanban Board (Jira/ClickUp Style) -->
             <div>
                 <h2 class="text-xl font-bold text-gray-800 mb-4">بورد تسک‌ها (Kanban)</h2>
                 <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                     <!-- Todo Column -->
                     <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200">
                         <div class="flex justify-between items-center mb-4">
                             <span class="font-bold text-gray-700">📌 در انتظار انجام</span>
                             <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-full font-bold">
                                 {{ count($kanbanTasks['todo'] ?? []) }}
                             </span>
                         </div>
                         <div class="space-y-3">
                             @forelse($kanbanTasks['todo'] ?? [] as $task)
                             <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition">
                                 <span class="text-xs font-semibold px-2 py-1 rounded bg-indigo-50 text-indigo-600">
                                     {{ $task->project->title ?? 'عمومی' }}
                                 </span>
                                 <h4 class="font-bold text-gray-800 mt-2">{{ $task->title }}</h4>
                                 <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $task->description }}</p>
                             </div>
                             @empty
                             <p class="text-xs text-gray-400 text-center py-4">تسکی در این ستون نیست</p>
                             @endforelse
                         </div>
                     </div>

                     <!-- In Progress Column -->
                     <div class="bg-amber-50/50 p-4 rounded-2xl border border-amber-200">
                         <div class="flex justify-between items-center mb-4">
                             <span class="font-bold text-amber-800">⚡ در حال انجام</span>
                             <span class="bg-amber-200 text-amber-800 text-xs px-2 py-1 rounded-full font-bold">
                                 {{ count($kanbanTasks['in_progress'] ?? []) }}
                             </span>
                         </div>
                         <div class="space-y-3">
                             @forelse($kanbanTasks['in_progress'] ?? [] as $task)
                             <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition">
                                 <span class="text-xs font-semibold px-2 py-1 rounded bg-amber-100 text-amber-700">
                                     {{ $task->project->title ?? 'عمومی' }}
                                 </span>
                                 <h4 class="font-bold text-gray-800 mt-2">{{ $task->title }}</h4>
                             </div>
                             @empty
                             <p class="text-xs text-gray-400 text-center py-4">تسکی در حال انجام نیست</p>
                             @endforelse
                         </div>
                     </div>

                     <!-- Done Column -->
                     <div class="bg-emerald-50/50 p-4 rounded-2xl border border-emerald-200">
                         <div class="flex justify-between items-center mb-4">
                             <span class="font-bold text-emerald-800">✅ انجام شده</span>
                             <span class="bg-emerald-200 text-emerald-800 text-xs px-2 py-1 rounded-full font-bold">
                                 {{ count($kanbanTasks['done'] ?? []) }}
                             </span>
                         </div>
                         <div class="space-y-3">
                             @forelse($kanbanTasks['done'] ?? [] as $task)
                             <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 opacity-75">
                                 <h4 class="font-bold text-gray-800 line-through">{{ $task->title }}</h4>
                             </div>
                             @empty
                             <p class="text-xs text-gray-400 text-center py-4">تسکی تمام نشده است</p>
                             @endforelse
                         </div>
                     </div>

                 </div>
             </div>
         </div>
     </main>

 </body>

 </html>