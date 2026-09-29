 @extends('layouts.app')

 @section('content')
 <style>
     :root {
         --bg-main: #070913;
         --card-bg: rgba(18, 24, 43, 0.65);
         --card-border: rgba(255, 255, 255, 0.08);
         --accent-purple: #c084fc;
         --accent-pink: #ec4899;
         --glow-gradient: linear-gradient(135deg, #a855f7 0%, #ec4899 50%, #3b82f6 100%);
     }

     .form-wrapper {
         min-height: 100vh;
         padding: 50px 20px;
         background: var(--bg-main);
         background-image:
             radial-gradient(ellipse at 20% 10%, rgba(168, 85, 247, 0.2) 0%, transparent 50%),
             radial-gradient(ellipse at 80% 80%, rgba(56, 189, 248, 0.15) 0%, transparent 50%);
         color: #f1f5f9;
         direction: rtl;
         font-family: system-ui, -apple-system, sans-serif;
     }

     .form-container {
         max-width: 680px;
         margin: 0 auto;
     }

     .form-card {
         background: var(--card-bg);
         backdrop-filter: blur(25px);
         -webkit-backdrop-filter: blur(25px);
         border: 1px solid var(--card-border);
         border-radius: 28px;
         padding: 40px;
         box-shadow: 0 30px 60px rgba(0, 0, 0, 0.7), inset 0 1px 0 rgba(255, 255, 255, 0.12);
         position: relative;
         overflow: hidden;
     }

     .form-card::after {
         content: '';
         position: absolute;
         bottom: 0;
         right: 0;
         left: 0;
         height: 2px;
         background: var(--glow-gradient);
     }

     .card-header {
         margin-bottom: 32px;
         display: flex;
         align-items: center;
         gap: 16px;
     }

     .header-icon {
         width: 52px;
         height: 52px;
         border-radius: 16px;
         background: var(--glow-gradient);
         display: flex;
         align-items: center;
         justify-content: center;
         font-size: 1.5rem;
         box-shadow: 0 0 25px rgba(168, 85, 247, 0.4);
     }

     .card-title {
         font-size: 1.6rem;
         font-weight: 800;
         color: #fff;
         margin: 0 0 4px 0;
     }

     .card-subtitle {
         font-size: 0.88rem;
         color: #94a3b8;
         margin: 0;
     }

     .form-group {
         margin-bottom: 24px;
     }

     .form-label {
         display: block;
         font-size: 0.9rem;
         font-weight: 700;
         color: #e2e8f0;
         margin-bottom: 8px;
     }

     .form-control,
     .form-select {
         width: 100%;
         padding: 14px 18px;
         background: rgba(15, 23, 42, 0.6);
         border: 1px solid rgba(255, 255, 255, 0.12);
         border-radius: 16px;
         color: #fff;
         font-size: 0.95rem;
         transition: all 0.3s ease;
         box-sizing: border-box;
     }

     .form-control:focus,
     .form-select:focus {
         outline: none;
         border-color: var(--accent-purple);
         box-shadow: 0 0 20px rgba(168, 85, 247, 0.3);
         background: rgba(15, 23, 42, 0.85);
     }

     textarea.form-control {
         resize: vertical;
         min-height: 120px;
     }

     .form-select option {
         background: #0f172a;
         color: #fff;
     }

     .error-text {
         color: #f43f5e;
         font-size: 0.8rem;
         margin-top: 6px;
         display: block;
     }

     .form-actions {
         display: flex;
         justify-content: flex-end;
         gap: 14px;
         margin-top: 32px;
         padding-top: 20px;
         border-top: 1px solid rgba(255, 255, 255, 0.06);
     }

     .btn {
         padding: 12px 28px;
         border-radius: 14px;
         font-size: 0.92rem;
         font-weight: 700;
         cursor: pointer;
         text-decoration: none;
         transition: all 0.3s ease;
         display: inline-flex;
         align-items: center;
         gap: 8px;
         border: none;
     }

     .btn-glass {
         background: rgba(255, 255, 255, 0.05);
         color: #cbd5e1;
         border: 1px solid rgba(255, 255, 255, 0.12);
     }

     .btn-glass:hover {
         background: rgba(255, 255, 255, 0.12);
         color: #fff;
     }

     .btn-gradient {
         background: var(--glow-gradient);
         color: #fff;
         box-shadow: 0 8px 25px rgba(168, 85, 247, 0.4);
     }

     .btn-gradient:hover {
         transform: translateY(-2px);
         box-shadow: 0 12px 30px rgba(168, 85, 247, 0.6);
     }
 </style>

 <div class="form-wrapper">
     <div class="form-container">
         <div class="form-card">

             <div class="card-header">
                 <div class="header-icon">⚡</div>
                 <div>
                     <h1 class="card-title">ایجاد پروژه جدید</h1>
                     <p class="card-subtitle">مشخصات پروژه جدید را برای افزودن به ورک‌اسپیس وارد کنید.</p>
                 </div>
             </div>

             <form action="{{ route('projects.store') }}" method="POST">
                 @csrf

                 {{-- انتخاب ورک‌اسپیس --}}
                 <div class="form-group">
                     <label for="workspace_id" class="form-label">فضای کاری (WorkSpace)</label>
                     <select name="workspace_id" id="workspace_id" class="form-select @error('workspace_id') border-danger @enderror" required>
                         <option value="" disabled selected>فضای کاری مورد نظر را انتخاب کنید...</option>
                         @if(isset($workspaces))
                         @foreach($workspaces as $workspace)
                         <option value="{{ $workspace->id }}" {{ (old('workspace_id', request('workspace_id')) == $workspace->id) ? 'selected' : '' }}>
                             {{ $workspace->name }}
                         </option>
                         @endforeach
                         @endif
                     </select>
                     @error('workspace_id')
                     <span class="error-text">{{ $message }}</span>
                     @enderror
                 </div>

                 {{-- نام پروژه --}}
                 <div class="form-group">
                     <label for="name" class="form-label">نام پروژه</label>
                     <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="مثلاً: طراحی پنل کاربری" required>
                     @error('name')
                     <span class="error-text">{{ $message }}</span>
                     @enderror
                 </div>

                 {{-- توضیحات --}}
                 <div class="form-group">
                     <label for="description" class="form-label">توضیحات پروژه (اختیاری)</label>
                     <textarea name="description" id="description" class="form-control" placeholder="جزئیات و هدف پروژه را بنویسید...">{{ old('description') }}</textarea>
                     @error('description')
                     <span class="error-text">{{ $message }}</span>
                     @enderror
                 </div>

                 {{-- دکمه‌ها --}}
                 <div class="form-actions">
                     <a href="{{ url()->previous() }}" class="btn btn-glass">انصراف</a>
                     <button type="submit" class="btn btn-gradient">
                         <span>🚀</span> ثبت پروژه
                     </button>
                 </div>

             </form>

         </div>
     </div>
 </div>
 @endsection