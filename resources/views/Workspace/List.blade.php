 @extends('layouts.app')

 @section('content')
 <style>
     .workspace-wrapper {
         min-height: 85vh;
         padding: 40px 20px;
         background: linear-gradient(135deg, #f6f8fd 0%, #e9eeef 100%);
         direction: rtl;
         font-family: inherit;
     }

     .container {
         max-width: 1100px;
         margin: 0 auto;
     }

     /* هدر صفحه */
     .page-header {
         display: flex;
         justify-content: space-between;
         align-items: center;
         margin-bottom: 32px;
         background: #ffffff;
         padding: 24px 30px;
         border-radius: 16px;
         box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03);
         border: 1px solid rgba(255, 255, 255, 0.8);
     }

     .page-title-group h1 {
         font-size: 1.6rem;
         font-weight: 800;
         color: #1f2937;
         margin: 0 0 6px 0;
         display: flex;
         align-items: center;
         gap: 10px;
     }

     .page-title-group p {
         font-size: 0.9rem;
         color: #6b7280;
         margin: 0;
     }

     .btn-create {
         background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
         color: #ffffff;
         padding: 12px 22px;
         border-radius: 12px;
         font-size: 0.9rem;
         font-weight: 700;
         text-decoration: none;
         display: inline-flex;
         align-items: center;
         gap: 8px;
         box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);
         transition: all 0.25s ease;
     }

     .btn-create:hover {
         transform: translateY(-2px);
         box-shadow: 0 6px 20px rgba(79, 70, 229, 0.45);
         color: #ffffff;
     }

     /* گرید کارت‌ها */
     .workspace-grid {
         display: grid;
         grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
         gap: 24px;
     }

     .workspace-card {
         background: #ffffff;
         border-radius: 18px;
         border: 1px solid #e5e7eb;
         padding: 24px;
         box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
         transition: all 0.3s ease;
         display: flex;
         flex-direction: column;
         justify-content: space-between;
         position: relative;
         overflow: hidden;
     }

     .workspace-card::before {
         content: '';
         position: absolute;
         top: 0;
         right: 0;
         left: 0;
         height: 4px;
         background: linear-gradient(90deg, #4f46e5, #7c3aed);
         opacity: 0;
         transition: opacity 0.3s ease;
     }

     .workspace-card:hover {
         transform: translateY(-5px);
         box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
         border-color: #d1d5db;
     }

     .workspace-card:hover::before {
         opacity: 1;
     }

     .card-top {
         margin-bottom: 20px;
     }

     .workspace-icon-title {
         display: flex;
         align-items: center;
         gap: 14px;
         margin-bottom: 12px;
     }

     .avatar-icon {
         width: 46px;
         height: 46px;
         border-radius: 12px;
         background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
         color: #4338ca;
         display: flex;
         align-items: center;
         justify-content: center;
         font-size: 1.25rem;
         font-weight: 800;
         flex-shrink: 0;
     }

     .workspace-name {
         font-size: 1.15rem;
         font-weight: 700;
         color: #111827;
         margin: 0;
         line-height: 1.3;
     }

     .workspace-desc {
         font-size: 0.875rem;
         color: #6b7280;
         line-height: 1.6;
         margin: 0;
         display: -webkit-box;
         -webkit-line-clamp: 2;
         -webkit-box-orient: vertical;
         overflow: hidden;
     }

     .card-bottom {
         display: flex;
         align-items: center;
         justify-content: space-between;
         border-top: 1px solid #f3f4f6;
         padding-top: 16px;
         margin-top: 10px;
     }

     .meta-info {
         font-size: 0.8rem;
         color: #9ca3af;
         display: flex;
         align-items: center;
         gap: 4px;
     }

     .btn-enter {
         color: #4f46e5;
         font-size: 0.875rem;
         font-weight: 700;
         text-decoration: none;
         display: flex;
         align-items: center;
         gap: 4px;
         transition: gap 0.2s ease;
     }

     .btn-enter:hover {
         gap: 8px;
         color: #3730a3;
     }

     /* حالت خالی (وقتی هیچ ورک‌اسپیسی نیست) */
     .empty-state {
         background: #ffffff;
         border-radius: 20px;
         padding: 60px 20px;
         text-align: center;
         box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03);
         border: 2px dashed #e5e7eb;
     }

     .empty-icon {
         width: 80px;
         height: 80px;
         background: #f3f4f6;
         color: #9ca3af;
         border-radius: 50%;
         display: flex;
         align-items: center;
         justify-content: center;
         margin: 0 auto 20px auto;
     }

     .empty-title {
         font-size: 1.25rem;
         font-weight: 800;
         color: #374151;
         margin: 0 0 8px 0;
     }

     .empty-desc {
         font-size: 0.9rem;
         color: #6b7280;
         margin: 0 0 24px 0;
     }
 </style>

 <div class="workspace-wrapper">
     <div class="container">

         {{-- هدر بالا --}}
         <div class="page-header">
             <div class="page-title-group">
                 <h1>
                     <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                     </svg>
                     ورک‌اسپیس‌های من
                 </h1>
                 <p>لیست تمام فضاهای کاری که در آن‌ها عضو هستید یا ایجاد کرده‌اید.</p>
             </div>

             <a href="{{ route('workspaces.create') }}" class="btn-create">
                 <span>+</span> ایجاد ورک‌اسپیس جدید
             </a>
         </div>

         {{-- نمایش لیست یا حالت خالی --}}
         @if($workspaces->isEmpty())
         <div class="empty-state">
             <div class="empty-icon">
                 <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                 </svg>
             </div>
             <h2 class="empty-title">هنوز هیچ ورک‌اسپیسی ساخته نشده است!</h2>
             <p class="empty-desc">برای شروع مدیریت پروژه‌ها و تسک‌های خود، اولین فضای کاری‌تان را بسازید.</p>
             <a href="{{ route('workspaces.create') }}" class="btn-create">
                 <span>+</span> ایجاد اولین ورک‌اسپیس
             </a>
         </div>
         @else
         <div class="workspace-grid">
             @foreach($workspaces as $workspace)
             <div class="workspace-card">
                 <div class="card-top">
                     <div class="workspace-icon-title">
                         <div class="avatar-icon">
                             {{ mb_substr($workspace->name, 0, 1) }}
                         </div>
                         <h2 class="workspace-name">{{ $workspace->name }}</h2>
                     </div>
                     <p class="workspace-desc">
                         {{ $workspace->description ?? 'بدون توضیحات ثبت شده' }}
                     </p>
                 </div>

                 <div class="card-bottom">
                     <span class="meta-info">
                         📅 {{ $workspace->created_at->format('Y/m/d') }}
                     </span>
                     <a href="{{ route('workspaces.show', $workspace->id) }}" class="btn-enter">
                         ورود به فضای کاری ➔
                     </a>
                 </div>
             </div>
             @endforeach
         </div>
         @endif

     </div>
 </div>
 @endsection