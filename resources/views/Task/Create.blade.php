@extends('layouts.app')

@section('content')
<style>
    /* Container & Card Style */
.task-create-container {
    max-width: 760px;
    margin: 2rem auto;
    padding: 0 1rem;
    direction: rtl;
    font-family: system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
}

.task-card {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    border: 1px solid #e2e8f0;
    overflow: hidden;
}

.task-card-header {
    background: #f8fafc;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.task-card-title {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
}

.task-card-body {
    padding: 1.75rem 1.5rem;
}

/* Form Grid & Layout */
.form-grid {
    display: grid;
    grid-template-columns: repeat(12, 1fr);
    gap: 1.25rem;
}

.col-12 { grid-column: span 12; }
.col-md-6 { grid-column: span 12; }

@media (min-width: 768px) {
    .col-md-6 { grid-column: span 6; }
}

/* Form Inputs & Labels */
.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #475569;
}

.form-label .required {
    color: #ef4444;
}

.form-control,
.form-select {
    width: 100%;
    padding: 0.625rem 0.875rem;
    font-size: 0.95rem;
    color: #0f172a;
    background-color: #f8fafc;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    transition: all 0.2s ease-in-out;
    outline: none;
    box-sizing: border-box;
}

.form-control:focus,
.form-select:focus {
    background-color: #ffffff;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
}

textarea.form-control {
    min-height: 110px;
    resize: vertical;
}

/* Buttons */
.form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 0.75rem;
    margin-top: 1.5rem;
    padding-top: 1.25rem;
    border-top: 1px solid #f1f5f9;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.625rem 1.25rem;
    font-size: 0.9rem;
    font-weight: 600;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    border: 1px solid transparent;
    text-decoration: none;
}

.btn-primary {
    background-color: #6366f1;
    color: #ffffff;
}

.btn-primary:hover {
    background-color: #4f46e5;
}

.btn-secondary {
    background-color: #ffffff;
    color: #64748b;
    border-color: #cbd5e1;
}

.btn-secondary:hover {
    background-color: #f1f5f9;
    color: #334155;
}

/* Validation Error Styles */
.is-invalid {
    border-color: #ef4444 !important;
    background-color: #fef2f2 !important;
}

.invalid-feedback {
    font-size: 0.8rem;
    color: #dc2626;
    margin-top: 0.25rem;
}
    </style>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-dark text-white border-secondary shadow-lg style=" backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.05);">
                <div class="card-header border-secondary d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-info">➕ ایجاد تسک جدید</h5>
                    <a href="{{ route('projects.show', $selectedproject ?? 1) }}" class="btn btn-outline-light btn-sm">بازگشت</a>
                </div>

                <div class="card-body">
                    {{-- نمایش خطاهای کلی اعتبارسنجی --}}
                    @if ($errors->any())
                    <div class="alert alert-danger bg-danger text-white border-0">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('tasks.store') }}" method="POST">
                        @csrf

                        {{-- انتخاب پروژه --}}
                        <div class="mb-3">
                            <label for="project_id" class="form-label text-light">انتخاب پروژه <span class="text-danger">*</span></label>
                            <select name="project_id" id="project_id" class="form-select bg-dark text-white border-secondary @error('project_id') is-invalid @enderror" required onchange="this.form.submit()">
                                <option value="" disabled {{ !$selectedproject ? 'selected' : '' }}>یک پروژه انتخاب کنید...</option>
                                @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ (old('project_id', $selectedproject) == $project->id) ? 'selected' : '' }}>
                                    {{ $project->name }} (ورک‌اسپیس: {{ $project->workspace->name ?? 'عمومی' }})
                                </option>
                                @endforeach
                            </select>
                            @error('project_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- انتخاب وضعیت داینامیک تسک (ProjectStatus) --}}
                        <div class="mb-3">
                            <label for="project_status_id" class="form-label text-light">وضعیت تسک <span class="text-danger">*</span></label>
                            <select name="project_status_id" id="project_status_id" class="form-select bg-dark text-white border-secondary @error('project_status_id') is-invalid @enderror" required>
                                @if(isset($statuses) && $statuses->count() > 0)
                                <option value="" disabled selected>وضعیت تسک را انتخاب کنید...</option>
                                @foreach($statuses as $status)
                                <option value="{{ $status->id }}" {{ old('project_status_id') == $status->id ? 'selected' : '' }}>
                                    {{ $status->name }}
                                </option>
                                @endforeach
                                @else
                                <option value="" disabled selected>ابتدا پروژه را انتخاب کنید یا وضعیت برای این ورک‌اسپیس تعریف نشده است</option>
                                @endif
                            </select>
                            @error('project_status_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- عنوان تسک --}}
                        <div class="mb-3">
                            <label for="title" class="form-label text-light">عنوان تسک <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control bg-dark text-white border-secondary @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="مثلاً: طراحی فرم لاگین" required>
                            @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- توضیحات تسک --}}
                        <div class="mb-3">
                            <label for="description" class="form-label text-light">توضیحات تسک</label>
                            <textarea name="description" id="description" rows="4" class="form-control bg-dark text-white border-secondary @error('description') is-invalid @enderror" placeholder="جزییات و نکات مربوط به این تسک را بنویسید...">{{ old('description') }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- تاریخ مهلت انجام (Due Date) --}}
                        <div class="mb-4">
                            <label for="due_date" class="form-label text-light">مهلت انجام (Due Date)</label>
                            <input type="date" name="due_date" id="due_date" class="form-control bg-dark text-white border-secondary @error('due_date') is-invalid @enderror" value="{{ old('due_date') }}">
                            @error('due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- دکمه ذخیره --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-info font-weight-bold text-dark">
                                🚀 ثبت و ایجاد تسک
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection