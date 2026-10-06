 @extends('layouts.app')

 @section('content')
 <style>
     :root {
         --tf-primary: #6366f1;
         --tf-primary-hover: #4f46e5;
         --tf-bg-card: #ffffff;
         --tf-bg-input: #f8fafc;
         --tf-border: #e2e8f0;
         --tf-border-focus: #cbd5e1;
         --tf-text-main: #0f172a;
         --tf-text-muted: #64748b;
         --tf-text-label: #334155;
         --tf-danger: #ef4444;
         --tf-danger-bg: #fef2f2;
     }

     .tf-container {
         max-width: 800px;
         margin: 2rem auto;
         padding: 0 1rem;
         direction: rtl;
         text-align: right;
         font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
     }

     .tf-card {
         background-color: var(--tf-bg-card);
         border: 1px solid var(--tf-border);
         border-radius: 12px;
         box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
         overflow: hidden;
     }

     .tf-card-header {
         background-color: #f8fafc;
         padding: 1.25rem 1.5rem;
         border-bottom: 1px solid var(--tf-border);
         display: flex;
         justify-content: space-between;
         align-items: center;
     }

     .tf-card-title {
         margin: 0;
         font-size: 1.25rem;
         font-weight: 700;
         color: var(--tf-text-main);
     }

     .tf-card-body {
         padding: 1.75rem 1.5rem;
     }

     .tf-grid {
         display: grid;
         grid-template-columns: repeat(12, 1fr);
         gap: 1.25rem;
     }

     .tf-col-12 {
         grid-column: span 12;
     }

     .tf-col-md-6 {
         grid-column: span 12;
     }

     @media (min-width: 768px) {
         .tf-col-md-6 {
             grid-column: span 6;
         }
     }

     .tf-form-group {
         display: flex;
         flex-direction: column;
         gap: 0.375rem;
     }

     .tf-label {
         font-size: 0.875rem;
         font-weight: 600;
         color: var(--tf-text-label);
     }

     .tf-label .required {
         color: var(--tf-danger);
         margin-right: 0.25rem;
     }

     .tf-control,
     .tf-select {
         width: 100%;
         padding: 0.625rem 0.875rem;
         font-size: 0.9375rem;
         color: var(--tf-text-main);
         background-color: var(--tf-bg-input);
         border: 1px solid var(--tf-border-focus);
         border-radius: 8px;
         outline: none;
         box-sizing: border-box;
         transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
     }

     .tf-control:focus,
     .tf-select:focus {
         background-color: #ffffff;
         border-color: var(--tf-primary);
         box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
     }

     textarea.tf-control {
         min-height: 120px;
         resize: vertical;
     }

     .tf-alert-danger {
         background-color: var(--tf-danger-bg);
         border: 1px solid #fca5a5;
         color: #991b1b;
         padding: 1rem 1.25rem;
         border-radius: 8px;
         margin-bottom: 1.5rem;
         font-size: 0.875rem;
     }

     .tf-alert-danger ul {
         margin: 0;
         padding-right: 1.25rem;
     }

     .is-invalid {
         border-color: var(--tf-danger) !important;
         background-color: #fff5f5 !important;
     }

     .tf-error-msg {
         font-size: 0.8rem;
         color: #dc2626;
         margin-top: 0.25rem;
     }

     .tf-actions {
         display: flex;
         align-items: center;
         justify-content: flex-end;
         gap: 0.75rem;
         margin-top: 1.5rem;
         padding-top: 1.25rem;
         border-top: 1px solid var(--tf-border);
     }

     .tf-btn {
         display: inline-flex;
         align-items: center;
         justify-content: center;
         padding: 0.625rem 1.25rem;
         font-size: 0.875rem;
         font-weight: 600;
         border-radius: 8px;
         cursor: pointer;
         text-decoration: none;
         transition: all 0.15s ease;
         border: 1px solid transparent;
     }

     .tf-btn-primary {
         background-color: var(--tf-primary);
         color: #ffffff;
     }

     .tf-btn-primary:hover {
         background-color: var(--tf-primary-hover);
     }

     .tf-btn-secondary {
         background-color: #ffffff;
         color: var(--tf-text-muted);
         border-color: var(--tf-border-focus);
     }

     .tf-btn-secondary:hover {
         background-color: #f1f5f9;
         color: var(--tf-text-main);
     }
 </style>

 <div class="tf-container">
     <div class="tf-card">

         {{-- Header --}}
         <div class="tf-card-header">
             <h1 class="tf-card-title">
                 ➕ ایجاد تسک جدید
             </h1>
             <a href="{{ route('tasks.index') }}" class="tf-btn tf-btn-secondary">
                 بازگشت
             </a>
         </div>

         {{-- Body --}}
         <div class="tf-card-body">

             @if ($errors->any())
             <div class="tf-alert-danger">
                 <ul>
                     @foreach ($errors->all() as $error)
                     <li>{{ $error }}</li>
                     @endforeach
                 </ul>
             </div>
             @endif

             <form action="{{ route('tasks.store') }}" method="POST">
                 @csrf

                 <div class="tf-grid">

                     {{-- انتخاب پروژه --}}
                     <div class="tf-col-12 tf-col-md-6">
                         <div class="tf-form-group">
                             <label for="project_id" class="tf-label">
                                 پروژه مربوطه <span class="required">*</span>
                             </label>
                             <select name="project_id" id="project_id" class="tf-select @error('project_id') is-invalid @enderror" required>
                                 <option value="" disabled selected>یک پروژه انتخاب کنید...</option>
                                 @foreach($projects as $project)
                                 <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                     {{ $project->name }}
                                 </option>
                                 @endforeach
                             </select>
                             @error('project_id')
                             <div class="tf-error-msg">{{ $message }}</div>
                             @enderror
                         </div>
                     </div>

                     {{-- وضعیت داینامیک --}}
                     <div class="tf-col-12 tf-col-md-6">
                         <div class="tf-form-group">
                             <label for="project_status_id" class="tf-label">
                                 وضعیت تسک <span class="required">*</span>
                             </label>
                             <select name="project_status_id" id="project_status_id" class="tf-select @error('project_status_id') is-invalid @enderror" required>
                                 <option value="" disabled selected>ابتدا پروژه را انتخاب کنید</option>
                             </select>
                             @error('project_status_id')
                             <div class="tf-error-msg">{{ $message }}</div>
                             @enderror
                         </div>
                     </div>

                     {{-- عنوان تسک --}}
                     <div class="tf-col-12">
                         <div class="tf-form-group">
                             <label for="title" class="tf-label">
                                 عنوان تسک <span class="required">*</span>
                             </label>
                             <input type="text" name="title" id="title" class="tf-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="عنوان تسک را وارد کنید" required>
                             @error('title')
                             <div class="tf-error-msg">{{ $message }}</div>
                             @enderror
                         </div>
                     </div>
                     {{-- توضیحات --}}
                     <div class="tf-col-12">
                         <div class="tf-form-group">
                             <label for="description" class="tf-label">توضیحات تسک</label>
                             <textarea name="description" id="description" class="tf-control @error('description') is-invalid @enderror" placeholder="جزئیات و نکات مربوط به این تسک را بنویسید...">{{ old('description') }}</textarea>
                             @error('description')
                             <div class="tf-error-msg">{{ $message }}</div>
                             @enderror
                         </div>
                     </div>

                     {{-- تاریخ مهلت انجام --}}
                     <div class="tf-col-12 tf-col-md-6">
                         <div class="tf-form-group">
                             <label for="due_date" class="tf-label">مهلت انجام (Due Date)</label>
                             <input type="date" name="due_date" id="due_date" class="tf-control @error('due_date') is-invalid @enderror" value="{{ old('due_date') }}">
                             @error('due_date')
                             <div class="tf-error-msg">{{ $message }}</div>
                             @enderror
                         </div>
                     </div>

                 </div>

                 {{-- دکمه‌ها --}}
                 <div class="tf-actions">
                     <a href="{{ route('tasks.index') }}" class="tf-btn tf-btn-secondary">
                         انصراف
                     </a>
                     <button type="submit" class="tf-btn tf-btn-primary">
                         🚀 ثبت و ایجاد تسک
                     </button>
                 </div>

             </form>
         </div>
     </div>
 </div>

 <script>
     document.addEventListener('DOMContentLoaded', function() {
         const projectSelect = document.getElementById('project_id');
         const statusSelect = document.getElementById('project_status_id');

         if (!projectSelect || !statusSelect) return;

         projectSelect.addEventListener('change', function() {
             const projectId = this.value;
             if (!projectId) return;

             statusSelect.innerHTML = '<option value="" disabled selected>در حال دریافت وضعیت‌ها...</option>';

             var url = '/projects/' + projectId + '/statuses';

             fetch(url)
                 .then(function(response) {
                     if (!response.ok) {
                         throw new Error('Network response was not ok');
                     }
                     return response.json();
                 })
                 .then(function(statuses) {
                     statusSelect.innerHTML = '<option value="" disabled selected>انتخاب وضعیت...</option>';
                     if (!statuses || statuses.length === 0) {
                         statusSelect.innerHTML = '<option value="" disabled selected>هیچ وضعیتی برای این پروژه یافت نشد</option>';
                         return;
                     }
                     statuses.forEach(function(status) {
                         const option = document.createElement('option');
                         option.value = status.id;
                         option.textContent = status.name;
                         statusSelect.appendChild(option);
                     });
                 })
                 .catch(function(error) {
                     statusSelect.innerHTML = '<option value="" disabled selected>خطا در دریافت وضعیت‌ها</option>';
                 });
         });
     });
 </script>
 @endsection