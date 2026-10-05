 @extends('layouts.app')

 @section('title', 'لیست فضاهای کاری | TaskFlow')

 @push('styles')
 <style>
     .workspace-container {
         max-width: 1200px;
         margin: 40px auto;
         padding: 0 20px;
     }

     .page-header {
         background: #ffffff;
         padding: 24px 32px;
         border-radius: 12px;
         border: 1px solid var(--border-color);
         display: flex;
         align-items: center;
         justify-content: space-between;
         margin-bottom: 24px;
         box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
     }

     .page-title h1 {
         font-size: 1.35rem;
         font-weight: 800;
         color: #0f172a;
     }

     .page-title p {
         font-size: 0.875rem;
         color: #64748b;
         margin-top: 4px;
     }

     .alert-success {
         background: #ecfdf5;
         border: 1px solid #a7f3d0;
         color: #065f46;
         padding: 14px 20px;
         border-radius: 10px;
         font-size: 0.9rem;
         font-weight: 600;
         margin-bottom: 24px;
     }

     .table-card {
         background: #ffffff;
         border-radius: 12px;
         border: 1px solid var(--border-color);
         box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
         overflow: hidden;
     }

     .custom-table {
         width: 100%;
         border-collapse: collapse;
         text-align: right;
         font-size: 0.9rem;
     }

     .custom-table th {
         background: #f8fafc;
         color: #475569;
         font-weight: 700;
         font-size: 0.8rem;
         padding: 14px 20px;
         border-bottom: 1px solid var(--border-color);
     }

     .custom-table td {
         padding: 16px 20px;
         border-bottom: 1px solid #f1f5f9;
         color: #334155;
         vertical-align: middle;
     }

     .custom-table tbody tr:hover {
         background-color: #f8fafc;
     }

     .custom-table tbody tr:last-child td {
         border-bottom: none;
     }

     /* نشانگرها (Badge) */
     .badge {
         display: inline-flex;
         align-items: center;
         padding: 4px 10px;
         border-radius: 20px;
         font-size: 0.75rem;
         font-weight: 600;
     }

     .badge-blue {
         background: #eff6ff;
         color: #1d4ed8;
         border: 1px solid #dbeafe;
     }

     .badge-purple {
         background: #faf5ff;
         color: #6b21a8;
         border: 1px solid #f3e8ff;
     }

     /* اکشن‌ها */
     .actions-wrapper {
         display: flex;
         align-items: center;
         justify-content: center;
         gap: 8px;
     }

     .btn-show {
         background: #eff6ff;
         color: var(--primary);
         border: 1px solid #e0e7ff;
         padding: 6px 12px;
         border-radius: 6px;
         font-size: 0.8rem;
         font-weight: 600;
         display: inline-flex;
         align-items: center;
         gap: 4px;
         transition: all 0.2s;
     }

     .btn-show:hover {
         background: var(--primary);
         color: #ffffff;
         border-color: var(--primary);
     }

     .btn-delete-icon {
         background: transparent;
         border: none;
         color: #94a3b8;
         padding: 6px;
         border-radius: 6px;
         cursor: pointer;
         display: inline-flex;
         align-items: center;
         justify-content: center;
         transition: all 0.2s;
     }

     .btn-delete-icon:hover {
         background: #fef2f2;
         color: var(--danger);
     }

     .empty-state {
         text-align: center;
         padding: 40px !important;
         color: #94a3b8;
     }
 </style>
 @endpush

 @section('content')
 <div class="workspace-container">

     <!-- هدر صفحه -->
     <div class="page-header">
         <div class="page-title">
             <h1>فضاهای کاری</h1>
             <p>مدیریت و مشاهده لیست تمام فضاهای کاری فعال شما</p>
         </div>
         <a href="{{ route('workspaces.create') }}" class="btn btn-primary">
             <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
             </svg>
             <span>ایجاد فضای کاری جدید</span>
         </a>
     </div>

     <!-- پیام موفقیت -->
     @if(session('success'))
     <div class="alert-success">
         {{ session('success') }}
     </div>
     @endif

     <!-- جدول فضاهای کاری -->
     <div class="table-card">
         <table class="custom-table">
             <thead>
                 <tr>
                     <th>عنوان فضای کاری</th>
                     <th>توضیحات</th>
                     <th style="text-align: center;">اعضا</th>
                     <th style="text-align: center;">پروژه‌ها</th>
                     <th style="text-align: center;">تاریخ ایجاد</th>
                     <th style="text-align: center;">عملیات</th>
                 </tr>
             </thead>
             <tbody>
                 @forelse($workspaces as $workspace)
                 <tr>
                     <td style="font-weight: 700; color: #0f172a;">
                         {{ $workspace->name }}
                     </td>
                     <td style="color: #64748b; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                         {{ $workspace->description ?? 'بدون توضیحات' }}
                     </td>
                     <td style="text-align: center;">
                         <span class="badge badge-blue">
                             {{ $workspace->members_count ?? 0 }} نفر
                         </span>
                     </td>
                     <td style="text-align: center;">
                         <span class="badge badge-purple">
                             {{ $workspace->projects_count ?? 0 }} پروژه
                         </span>
                     </td>
                     <td style="text-align: center; color: #94a3b8; font-size: 0.8rem;">
                         @if($workspace->created_at)
                         {{ \Morilog\Jalali\Jalalian::fromDateTime($workspace->created_at)->format('Y/m/d') }}
                         @else
                         -
                         @endif
                     </td>
                     <td style="text-align: center;">
                         <div class="actions-wrapper">
                             <!-- ورود به فضای کاری (Show) -->
                             <a href="{{ route('workspaces.show', $workspace->id) }}" class="btn-show">
                                 <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                 </svg>
                                 <span>ورود به فضای کاری</span>
                             </a>

                             <!-- حذف -->
                             <form action="{{ route('workspaces.destroy', $workspace->id) }}" method="POST" onsubmit="return confirm('آیا از حذف این فضای کاری مطمئن هستید؟')" style="display: inline-block;">
                                 @csrf
                                 @method('DELETE')
                                 <button type="submit" class="btn-delete-icon" title="حذف">
                                     <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                     </svg>
                                 </button>
                             </form>
                         </div>
                     </td>
                 </tr>
                 @empty
                 <tr>
                     <td colspan="6" class="empty-state">
                         هنوز هیچ فضای کاری ایجاد نشده است.
                     </td>
                 </tr>
                 @endforelse
             </tbody>
         </table>
     </div>

 </div>
 @endsection