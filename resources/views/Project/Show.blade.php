 @extends('layouts.app')

 @section('title', $project->name)

 @section('content')
 <!-- اضافه کردن CDNها برای رندر فوری Tailwind و Alpine -->
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
 <style>


     /* تنظیمات فونت و اندازه کانتینر اصلی */
     .project-wrapper {
         font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
         direction: rtl;
         text-align: right;
         max-width: 1200px;
         margin: 30px auto;
         padding: 0 15px;
         color: #2d3748;
     }

     /* هدر و کادر اصلی اطلاعات پروژه */
     .project-card {
         background-color: #ffffff;
         border-radius: 12px;
         padding: 24px;
         box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
         border: 1px solid #e2e8f0;
         margin-bottom: 24px;
     }

     .project-title-bar {
         display: flex;
         justify-content: space-between;
         align-items: center;
         border-bottom: 2px solid #f7fafc;
         padding-bottom: 16px;
         margin-bottom: 16px;
     }

     .project-title {
         font-size: 22px;
         font-weight: 700;
         color: #1a202c;
         margin: 0;
     }

     .project-status-badge {
         background-color: #ebf8ff;
         color: #2b6cb0;
         padding: 6px 16px;
         border-radius: 9999px;
         font-size: 13px;
         font-weight: 600;
     }

     .project-description {
         color: #4a5568;
         font-size: 14px;
         line-height: 1.7;
         margin-bottom: 24px;
     }

     /* کارت‌های آمار و اطلاعات عددی */
     .stats-grid {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
         gap: 16px;
     }

     .stat-card {
         background-color: #f8fafc;
         border: 1px solid #e2e8f0;
         border-radius: 8px;
         padding: 16px;
     }

     .stat-label {
         font-size: 12px;
         color: #718096;
         font-weight: 500;
         margin-bottom: 6px;
     }

     .stat-value {
         font-size: 16px;
         font-weight: 700;
         color: #1a202c;
     }

     /* نوار پیشرفت درصد پروژه */
     .progress-bar-bg {
         background-color: #e2e8f0;
         border-radius: 9999px;
         height: 8px;
         width: 100%;
         overflow: hidden;
         margin-top: 10px;
     }

     .progress-bar-fill {
         background-color: #3182ce;
         height: 100%;
         border-radius: 9999px;
         transition: width 0.4s ease;
     }

     /* تخته کانبان و وضعیت‌های پروژه */
     .board-grid {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
         gap: 20px;
         align-items: start;
     }

     .status-column {
         background-color: #f1f5f9;
         border-radius: 10px;
         padding: 16px;
         border: 1px solid #e2e8f0;
     }

     .status-header {
         font-size: 15px;
         font-weight: 700;
         color: #334155;
         margin-bottom: 16px;
         display: flex;
         justify-content: space-between;
         align-items: center;
     }

     .task-card {
         background-color: #ffffff;
         border-radius: 8px;
         padding: 14px;
         margin-bottom: 12px;
         box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
         border-right: 4px solid #3182ce;
     }

     .task-title {
         font-size: 14px;
         font-weight: 600;
         color: #1e293b;
         margin-bottom: 8px;
     }

     .task-meta {
         font-size: 12px;
         color: #64748b;
     }
 </style>

 <div class="container mx-auto px-4 py-6" x-data="{ activeTab: 'kanban' }">

     {{-- Header & Breadcrumbs --}}
     <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
         <div>
             <nav class="text-sm font-medium text-gray-500 mb-2">
                 <a href="{{ route('workspaces.show', $project->workspace_id) }}" class="hover:text-gray-700">
                     {{ $project->workspace->name }}
                 </a>
                 <span class="mx-2">/</span>
                 <span class="text-gray-900 font-semibold">{{ $project->name }}</span>
             </nav>
             <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                 {{ $project->name }}
                 <span class="text-xs px-2.5 py-1 rounded-full font-medium 
                    @if($project->status === 'Active') bg-green-100 text-green-800
                    @elseif($project->status === 'Planning') bg-blue-100 text-blue-800
                    @elseif($project->status === 'On Hold') bg-yellow-100 text-yellow-800
                    @elseif($project->status === 'Completed') bg-purple-100 text-purple-800
                    @else bg-gray-100 text-gray-800 @endif">
                     {{ ucfirst($project->status) }}
                 </span>
             </h1>
             @if($project->description)
             <p class="text-sm text-gray-600 mt-1 max-w-2xl">{{ $project->description }}</p>
             @endif
         </div>

         {{-- Actions --}}
         <div class="flex items-center gap-2">
             @can('createTask', $project)
             <button type="button" @click="$dispatch('open-modal', 'create-task-modal')"
                 class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm flex items-center gap-2">
                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                 </svg>
                 ایجاد Task جدید
             </button>
             @endcan

             <a href="#"
                 class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-1.5"
                 title="خروجی CSV">
                 <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                 </svg>
                 Export CSV
             </a>
         </div>
     </div>

     {{-- Metadata Cards / Quick Stats --}}
     <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
         <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
             <span class="text-xs text-gray-500 block">سازنده</span>
             <span class="text-sm font-semibold text-gray-800">{{ $project->created_by }}</span>
         </div>
         <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
             <span class="text-xs text-gray-500 block">تاریخ شروع</span>
             <span class="text-sm font-semibold text-gray-800">{{ $project->start_date ? $project->start_date->format('Y-m-d') : 'تعریف‌نشده' }}</span>
         </div>
         <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
             <span class="text-xs text-gray-500 block">ددلاین (Deadline)</span>
             <span class="text-sm font-semibold {{ $project->deadline && $project->deadline->isPast() ? 'text-red-600' : 'text-gray-800' }}">
                 {{ $project->deadline ? $project->deadline->format('Y-m-d') : 'تعریف‌نشده' }}
             </span>
         </div>
         <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
             <span class="text-xs text-gray-500 block">زمان کل صرف‌شده</span>
             <span class="text-sm font-semibold text-gray-800">{{ floor( $totalloggedminutes / 60) }}h {{ $totalloggedminutes % 60 }}m</span>
         </div>
         <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
             <span class="text-xs text-gray-500 block">پیشرفت پروژه</span>
             <div class="flex items-center gap-2 mt-1">
                 <div class="w-full bg-gray-200 rounded-full h-2">
                     <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $progresspercentage }}%"></div>
                 </div>
                 <span class="text-xs font-bold text-gray-700">{{ $progresspercentage }}%</span>
             </div>
         </div>
     </div>

     {{-- Tabs Navigation --}}
     <div class="border-b border-gray-200 mb-6">
         <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
             <li class="me-2">
                 <button @click="activeTab = 'kanban'"
                     :class="activeTab === 'kanban' ? 'text-indigo-600 border-indigo-600 active' : 'text-gray-500 border-transparent hover:text-gray-600 hover:border-gray-300'"
                     class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg gap-2">
                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V5m6 12V5m-12 12h18" />
                     </svg>
                     Kanban Board
                 </button>
             </li>
             <li class="me-2">
                 <button @click="activeTab = 'members'"
                     :class="activeTab === 'members' ? 'text-indigo-600 border-indigo-600 active' : 'text-gray-500 border-transparent hover:text-gray-600 hover:border-gray-300'"
                     class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg gap-2">
                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                     </svg>
                     اعضای پروژه ({{ $project->users->count() }})
                 </button>
             </li>
             <li class="me-2">
                 <button @click="activeTab = 'workflow'"
                     :class="activeTab === 'workflow' ? 'text-indigo-600 border-indigo-600 active' : 'text-gray-500 border-transparent hover:text-gray-600 hover:border-gray-300'"
                     class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg gap-2">
                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                     </svg>
                     مدیریت Workflow (Statusها)
                 </button>
             </li>
         </ul>
     </div>

     {{-- TAB 1: KANBAN BOARD --}}
     <div x-show="activeTab === 'kanban'" class="overflow-x-auto pb-4">

         {{-- Search & Filters --}}
         <form method="GET" action="{{ route('projects.show', $project) }}" class="mb-4 flex flex-wrap items-center gap-3 bg-white p-3 rounded-lg border border-gray-100 shadow-sm">
             <input type="text" name="search" value="{{ request('search') }}" placeholder="جستجوی Task..."
                 class="text-sm rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 w-full sm:w-64">

             <select name="priority" class="text-sm rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                 <option value="">همه اولویت‌ها</option>
                 <option value="Low" {{ request('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                 <option value="Medium" {{ request('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
                 <option value="High" {{ request('priority') == 'High' ? 'selected' : '' }}>High</option>
                 <option value="Urgent" {{ request('priority') == 'Urgent' ? 'selected' : '' }}>Urgent</option>
             </select>

             <select name="assignee_id" class="text-sm rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                 <option value="">همه افراد مسئول</option>
                 @foreach($project->users as $user)
                 <option value="{{ $user->id }}" {{ request('assignee_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                 @endforeach
             </select>

             <button type="submit" class="px-3 py-1.5 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg">فیلتر</button>
             @if(request()->hasAny(['search', 'priority', 'assignee_id']))
             <a href="{{ route('projects.show', $project) }}" class="text-sm text-red-600 hover:underline">حذف فیلترها</a>
             @endif
         </form>

         {{-- Kanban Columns Container --}}
         <div class="flex gap-4 items-start min-w-[800px]" x-data="kanbanBoard()">
             @forelse(($project->statuses ?? collect())->sortBy('position') as $status)
             <div class="bg-gray-50 border border-gray-200 rounded-xl p-3 w-72 flex-shrink-0"
                 data-status-id="{{ $status->id }}">

                 {{-- Column Header --}}
                 <div class="flex items-center justify-between mb-3 px-1">
                     <div class="flex items-center gap-2">
                         <h3 class="font-bold text-gray-800 text-sm">{{ $status->name }}</h3>
                         <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full font-semibold">
                             {{ $status->tasks->count() }}
                         </span>
                     </div>
                     @if($status->is_final)
                     <span class="text-[10px] text-green-700 bg-green-50 px-1.5 py-0.5 rounded border border-green-200">نهایی</span>
                     @endif
                 </div>

                 {{-- Tasks List / Dropzone --}}
                 <div class="task-list space-y-3 min-h-[150px]" data-status-id="{{ $status->id }}">
                     @foreach($status->tasks as $task)
                     <div class="task-card bg-white p-3 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition cursor-grab"
                         data-task-id="{{ $task->id }}">

                         {{-- Task Header & Priority --}}
                         <div class="flex items-center justify-between mb-2">
                             <span class="text-[11px] font-bold px-2 py-0.5 rounded 
                                        @if($task->priority === 'Urgent') bg-red-100 text-red-700
                                        @elseif($task->priority === 'High') bg-orange-100 text-orange-700
                                        @elseif($task->priority === 'Medium') bg-yellow-100 text-yellow-700
                                        @else bg-gray-100 text-gray-600 @endif">
                                 {{ $task->priority }}
                             </span>

                             @if($task->is_blocked)
                             <span class="text-[10px] bg-red-600 text-white font-bold px-1.5 py-0.5 rounded" title="وابسته به تسک‌های دیگر">Blocked</span>
                             @endif
                         </div>
                         {{-- Title --}}
                         <a href="{{ route('tasks.show', $task) }}" class="text-sm font-semibold text-gray-900 hover:text-indigo-600 block mb-2">
                             {{ $task->title }}
                         </a>

                         {{-- Subtasks / Attachments Icons --}}
                         <div class="flex items-center gap-3 text-xs text-gray-400 mb-3">
                             @if($task->subtasks_count > 0)
                             <span class="flex items-center gap-1" title="Subtasks">
                                 <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                 </svg>
                                 {{ $task->subtasks_completed_count }}/{{ $task->subtasks_count }}
                             </span>
                             @endif

                             @if($task->attachments_count > 0)
                             <span class="flex items-center gap-1" title="پیوست‌ها">
                                 <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                 </svg>
                                 {{ $task->attachments_count }}
                             </span>
                             @endif
                         </div>

                         {{-- Footer: Assignee & Due Date --}}
                         <div class="flex items-center justify-between border-t pt-2 mt-2">
                             <div class="flex items-center gap-1.5">
                                 @if($task->assignee)
                                 <div class="w-6 h-6 rounded-full bg-indigo-500 text-white flex items-center justify-center text-[10px] font-bold" title="{{ $task->assignee->name }}">
                                     {{ substr($task->assignee->name, 0, 2) }}
                                 </div>
                                 @else
                                 <span class="text-xs text-gray-400">بدون مسئول</span>
                                 @endif
                             </div>

                             @if($task->deadline)
                             <span class="text-[11px] {{ $task->isOverdue() ? 'text-red-600 font-bold' : 'text-gray-500' }}">
                                 {{ $task->deadline->format('M d') }}
                             </span>
                             @endif
                         </div>

                     </div>
                     @endforeach
                 </div>

             </div>
             @empty
             <div class="p-4 text-center text-gray-500 w-full">هیچ وضعیت (Status) برای این پروژه تعریف نشده است.</div>
             @endforelse
         </div>
     </div>

     {{-- TAB 2: MEMBERS --}}
     <div x-show="activeTab === 'members'" class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
         <h3 class="text-lg font-bold text-gray-900 mb-4">اعضای پروژه</h3>
         <div class="divide-y">
             @foreach($project->users as $member)
             <div class="py-3 flex items-center justify-between">
                 <div class="flex items-center gap-3">
                     <div class="w-9 h-9 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center">
                         {{ substr($member->name, 0, 2) }}
                     </div>
                     <div>
                         <div class="font-semibold text-gray-800 text-sm">{{ $member->name }}</div>
                         <div class="text-xs text-gray-500">{{ $member->email }}</div>
                     </div>
                 </div>
                 <span class="text-xs bg-gray-100 text-gray-700 px-2.5 py-1 rounded-full font-medium">
                     {{ $member->pivot->role ?? 'Member' }}
                 </span>
             </div>
             @endforeach
         </div>
     </div>

     {{-- TAB 3: WORKFLOW STATUSES MANAGEMENT --}}
     <div x-show="activeTab === 'workflow'" class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
         <h3 class="text-lg font-bold text-gray-900 mb-4">مدیریت مراحل کاری (Workflow)</h3>
         <div class="space-y-2 max-w-md mb-6">
             @foreach(($project->statuses ?? collect())->sortBy('position') as $status)
             <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border">
                 <span class="font-medium text-sm text-gray-800">{{ $status->position }}. {{ $status->name }}</span>
                 @if($status->is_final)
                 <span class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded">Status نهایی</span>
                 @endif
             </div>
             @endforeach
         </div>
     </div>

 </div>

 {{-- Alpine JS Script for Kanban AJAX calls --}}
 <script>
     function kanbanBoard() {
         return {
             init() {
                 // اینجا می‌توانید کتابخانه‌هایی مانند SortableJS را برای Drag & Drop و ارسال درخواست AJAX به Route آپدیت Position متصل کنید.
             }
         }
     }
 </script>
 @endsection