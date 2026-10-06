 @extends('layouts.app')

 @section('title', ($workspace->name ?? 'فضای کاری') . ' | سیستم مدیریت پروژه')

 @push('styles')
 <style>
     /* Workspace Page Custom Styling */
     .workspace-header {
         background-color: #ffffff;
         border: 1px solid #e2e8f0;
         border-radius: 12px;
         padding: 1.75rem 2rem;
         margin-bottom: 2rem;
         display: flex;
         justify-content: space-between;
         align-items: flex-start;
         gap: 1.5rem;
         box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
     }

     .workspace-title-area h1 {
         font-size: 1.75rem;
         font-weight: 700;
         margin-bottom: 0.5rem;
         word-break: break-word;
         color: #0f172a;
     }

     .workspace-description {
         color: #64748b;
         font-size: 0.95rem;
         line-height: 1.6;
         word-break: break-word;
     }

     /* Action Buttons */
     .btn-ws-primary {
         background-color: #4f46e5;
         color: #ffffff !important;
         padding: 0.65rem 1.25rem;
         border-radius: 8px;
         text-decoration: none;
         font-weight: 600;
         font-size: 0.9rem;
         display: inline-flex;
         align-items: center;
         gap: 0.5rem;
         transition: background 0.2s;
         white-space: nowrap;
         border: none;
         cursor: pointer;
     }

     .btn-ws-primary:hover {
         background-color: #4338ca;
     }

     /* Stats Grid */
     .stats-grid {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
         gap: 1.25rem;
         margin-bottom: 2rem;
     }

     .stat-card {
         background-color: #ffffff;
         border: 1px solid #e2e8f0;
         border-radius: 12px;
         padding: 1.25rem;
         box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
     }

     .stat-title {
         color: #64748b;
         font-size: 0.85rem;
         margin-bottom: 0.5rem;
     }

     .stat-value {
         font-size: 1.6rem;
         font-weight: 700;
         color: #0f172a;
     }

     /* Dashboard Layout Grid */
     .dashboard-grid {
         display: grid;
         grid-template-columns: 2fr 1fr;
         gap: 1.5rem;
     }

     @media (max-width: 1024px) {
         .dashboard-grid {
             grid-template-columns: 1fr;
         }
     }

     .section-card {
         background-color: #ffffff;
         border: 1px solid #e2e8f0;
         border-radius: 12px;
         padding: 1.5rem;
         box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
     }

     .section-header {
         display: flex;
         justify-content: space-between;
         align-items: center;
         margin-bottom: 1.25rem;
         padding-bottom: 0.75rem;
         border-bottom: 1px solid #e2e8f0;
     }

     .section-title {
         font-size: 1.1rem;
         font-weight: 600;
         color: #0f172a;
     }

     /* Projects List */
     .projects-list {
         display: flex;
         flex-direction: column;
         gap: 1rem;
     }

     .project-item {
         background-color: #f8fafc;
         border: 1px solid #e2e8f0;
         border-radius: 8px;
         padding: 1rem 1.25rem;
         display: flex;
         justify-content: space-between;
         align-items: center;
         transition: all 0.2s;
     }

     .project-item:hover {
         border-color: #4f46e5;
         background-color: #ffffff;
         box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
     }

     .project-name {
         font-weight: 600;
         font-size: 0.95rem;
         color: #0f172a;
     }

     .status-badge {
         font-size: 0.75rem;
         padding: 0.25rem 0.6rem;
         border-radius: 20px;
         background-color: #d1fae5;
         color: #065f46;
         border: 1px solid #a7f3d0;
     }

     /* Members List */
     .members-list {
         display: flex;
         flex-direction: column;
         gap: 0.85rem;
     }

     .member-item {
         display: flex;
         align-items: center;
         justify-content: space-between;
         padding: 0.5rem 0;
     }

     .member-info {
         display: flex;
         align-items: center;
         gap: 0.75rem;
     }

     .member-avatar {
         width: 36px;
         height: 36px;
         border-radius: 50%;
         background-color: #e0e7ff;
         color: #4f46e5;
         display: flex;
         align-items: center;
         justify-content: center;
         font-weight: 600;
         font-size: 0.85rem;
     }

     .member-name {
         font-size: 0.9rem;
         font-weight: 500;
         color: #0f172a;
     }

     .member-role {
         font-size: 0.75rem;
         color: #64748b;
         background-color: #f1f5f9;
         padding: 0.2rem 0.5rem;
         border-radius: 4px;
     }

     .empty-state {
         padding: 2rem;
         text-align: center;
         color: #64748b;
         font-size: 0.9rem;
     }
 </style>
 @endpush

 @section('content')
 <!-- Workspace Banner -->
 <div class="workspace-header">
     <div class="workspace-title-area">
         <h1>{{ $workspace->name }}</h1>
         <p class="workspace-description">
             {{ $workspace->description ?? 'توضیحاتی برای این فضای کاری ثبت نشده است.' }}
         </p>
     </div>
     <div>
         <a href="{{ route('projects.create') }}" class="btn-ws-primary">+ پروژه جدید</a>
     </div>
 </div>

 <!-- Overview Stats -->
 <div class="stats-grid">
     <div class="stat-card">
         <div class="stat-title">پروژه‌های فعال</div>
         <div class="stat-value">{{ $workspace->projects->count() }}</div>
     </div>
     <div class="stat-card">
         <div class="stat-title">تعداد اعضا</div>
         <div class="stat-value">
             {{  $workspace->users()->count()}} 
         </div>
     </div>
     <div class="stat-card">
         <div class="stat-title">وضعیت</div>
         <div class="stat-value" style="color: #10b981; font-size: 1.1rem; margin-top: 0.4rem;">فعال</div>
     </div>
 </div>

 <!-- Dashboard Main Grid -->
 <div class="dashboard-grid">

     <!-- Projects Section -->
     <div class="section-card">
         <div class="section-header">
             <h2 class="section-title">پروژه‌ها</h2>
             <a href="{{ route('projects.index') }}" style="color: #4f46e5; text-decoration: none; font-size: 0.85rem; font-weight: 600;">مشاهده همه</a>
         </div>

         @if($workspace->projects->count() > 0)
         <div class="projects-list">
             @foreach($workspace->projects as $project)
             <div class="project-item">
                 <div>
                     <div class="project-name">{{ $project->name }}</div>
                 </div>
                 <div style="display: flex; align-items: center; gap: 1rem;">
                     <span class="status-badge">در حال اجرا</span>
                     <a href="{{ route('projects.show', $project->id) }}" style="color: #4f46e5; text-decoration: none; font-size: 0.85rem; font-weight: 600;">ورود به پروژه ←</a>
                 </div>
             </div>
             @endforeach
         </div>
         @else
         <div class="empty-state">
             <p style="margin-bottom: 1rem;">هیچ پروژه‌ای در این فضای کاری ایجاد نشده است.</p>
             <a href="{{ route('projects.create') }}" class="btn-ws-primary" style="font-size: 0.85rem;">+ اولین پروژه را بسازید</a>
         </div>
         @endif
     </div>

     <!-- Members Section -->
     <!-- بخش اعضای فضای کاری -->
     <div class="section-card">
         <div class="section-header">
             <h2 class="section-title">اعضای فضای کاری</h2>
         </div>

         <!-- پیام موفقیت -->
         @if(session('success'))
         <div style="background: #d1fae5; color: #065f46; padding: 8px 12px; border-radius: 6px; font-size: 0.85rem; margin-bottom: 12px;">
             {{ session('success') }}
         </div>
         @endif

         <!-- فرم افزودن مستقیم عضو جدید -->
         <form action="{{ route('workspaces.addmember', $workspace->id) }}" method="POST" style="margin-bottom: 20px;">
             @csrf
             <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                 <input type="email" name="email" placeholder="ایمیل کاربر ثبت‌نام‌شده..." class="form-control" style="flex: 2; min-width: 180px;" required>

                 <select name="role" class="form-select" style="flex: 1; min-width: 110px;">
                     <option value="member">عضو (Member)</option>
                     <option value="admin">مدیر (Admin)</option>
                     <option value="viewer">بیننده (Viewer)</option>
                 </select>

                 <button type="submit" class="btn-ws-primary" style="padding: 8px 14px; font-size: 0.85rem;">
                     + افزودن
                 </button>
             </div>
             @error('email')
             <div style="color: #ef4444; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</div>
             @enderror
         </form>

         <!-- لیست اعضای موجود -->
         <div class="members-list">
             @foreach($workspace->users as $user)
             <div class="member-item">
                 <div class="member-info">
                     <div class="member-avatar">{{ mb_substr($user->name, 0, 1) }}</div>
                     <div>
                         <div class="member-name">{{ $user->name }}</div>
                         <div style="font-size: 0.75rem; color: #64748b;">{{ $user->email }}</div>
                     </div>
                 </div>
                 <span class="member-role">{{ $user->pivot->role ?? 'عضو' }}</span>
             </div>
             @endforeach
         </div>
     </div>

     <div class="members-list">
         @if($workspace->relationLoaded('users') && $workspace->users->count() > 0)
         @foreach($workspace->users as $user)
         <div class="member-item">
             <div class="member-info">
                 <div class="member-avatar">{{ mb_substr($user->name, 0, 1) }}</div>
                 <div>
                     <div class="member-name">{{ $user->name }}</div>
                 </div>
             </div>
             <span class="member-role">{{ $user->pivot->role ?? 'عضو' }}</span>
         </div>
         @endforeach
         @else
         <div class="member-item">
             <div class="member-info">
                 <div class="member-avatar">
                     {{ mb_substr(auth()->user()->name ?? 'م', 0, 1) }}
                 </div>
                 <div>
                     <div class="member-name">{{ auth()->user()->name ?? 'کاربر' }}</div>
                 </div>
             </div>
             <span class="member-role">مالک</span>
         </div>
         @endif
     </div>
 </div>

 </div>
 @endsection