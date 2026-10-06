@extends('layouts.app')

@section('title', 'تعریف پروژه جدید | TaskFlow Pro')

@push('styles')
<style>
    .form-container {
        max-width: 680px;
        margin: 30px auto;
        padding: 0 15px;
    }

    .form-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.05), 0 8px 10px -6px rgba(15, 23, 42, 0.01);
        padding: 36px 32px;
    }

    /* Form Header Modern */
    .form-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
        padding-bottom: 20px;
        border-bottom: 1px solid #f1f5f9;
    }

    .header-title-group {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .header-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #e0e7ff;
        color: #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .form-header h1 {
        font-size: 1.35rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 2px;
    }

    .form-header p {
        font-size: 0.85rem;
        color: #64748b;
    }

    /* Grid layout for short fields */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    @media (max-width: 640px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }

    .form-group {
        margin-bottom: 22px;
    }

    .form-group label {
        display: block;
        font-size: 0.88rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 8px;
    }

    .form-control,
    .form-select {
        width: 100%;
        padding: 11px 14px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        font-family: inherit;
        font-size: 0.9rem;
        color: #0f172a;
        background-color: #ffffff;
        transition: all 0.2s ease-in-out;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
    }

    .form-control:hover,
    .form-select:hover {
        border-color: #94a3b8;
    }

    .form-control:focus,
    .form-select:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 110px;
    }

    .form-control-error {
        border-color: #ef4444 !important;
    }

    .error-text {
        color: #ef4444;
        font-size: 0.8rem;
        margin-top: 6px;
    }

    /* Alerts */
    .alert-danger-custom {
        background-color: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
        border-radius: 10px;
        padding: 14px 18px;
        margin-bottom: 24px;
        font-size: 0.88rem;
    }

    .alert-danger-custom ul {
        margin: 0;
        padding-right: 18px;
    }

    /* Buttons Modern */
    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 32px;
        padding-top: 20px;
        border-top: 1px solid #f1f5f9;
    }

    .btn-create-submit {
        background-color: #4f46e5;
        color: #ffffff;
        padding: 11px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
    }

    .btn-create-submit:hover {
        background-color: #4338ca;
        box-shadow: 0 6px 16px rgba(79, 70, 229, 0.35);
        transform: translateY(-1px);
    }

    .btn-cancel-link {
        color: #64748b;
        text-decoration: none;
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 0.88rem;
        font-weight: 600;
        transition: all 0.2s;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }

    .btn-cancel-link:hover {
        background: #f1f5f9;
        color: #334155;
    }

    .required-star {
        color: #ef4444;
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <div class="form-card">

        <!-- نمایش خطاهای لاراول -->
        @if ($errors->any())
        <div class="alert-danger-custom">
            <strong>لطفاً خطاهای زیر را برطرف کنید:</strong>
            <ul style="margin-top: 6px;">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- هدر فرم -->
        <div class="form-header">
            <div class="header-title-group">
                <div class="header-icon-box">📌</div>
                <div>
                    <h1>تعریف پروژه جدید</h1>
                    <p>مشخصات پروژه را برای شروع مدیریت کارها وارد کنید</p>
                </div>
            </div>
            <a href="{{ route('projects.index') }}" class="btn-cancel-link" style="padding: 6px 14px; font-size: 0.8rem;">
                ✕ انصراف
            </a>
        </div>

        <form action="{{ route('projects.store') }}" method="POST">
            @csrf

            <!-- انتخاب فضای کاری -->
            <div class="form-group">
                <label for="workspace_id">فضای کاری مربوطه <span class="required-star">*</span></label>
                <select name="workspace_id" id="workspace_id" class="form-select @error('workspace_id') form-control-error @enderror" required>
                    <option value="">... انتخاب فضای کاری ...</option>
                    @forelse($workspaces as $workspace)
                    <option value="{{ $workspace->id }}" {{ old('workspace_id') == $workspace->id ? 'selected' : '' }}>
                        {{ $workspace->name }}
                    </option>
                    @empty
                    <option value="" disabled>شما هنوز هیچ فضای کاری ایجاد نکرده‌اید</option>
                    @endforelse
                </select>
                @error('workspace_id')
                <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <!-- عنوان پروژه -->
            <div class="form-group">
                <label for="name">عنوان پروژه <span class="required-star">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="مثلاً: طراحی فرانت‌اند رزرویشن" class="form-control @error('name') form-control-error @enderror" required autofocus>
                @error('name')
                <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <!-- توضیحات پروژه -->
            <div class="form-group">
                <label for="description">توضیحات پروژه</label>
                <textarea name="description" id="description" rows="4" placeholder="توضیحات و اهداف پروژه را در چند سطر بنویسید..." class="form-control @error('description') form-control-error @enderror">{{ old('description') }}</textarea>
                @error('description')
                <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <!-- وضعیت و مهلت در یک سطر -->
            <div class="form-row">
                <!-- وضعیت پروژه -->
                <div class="form-group">
                    <label for="status">وضعیت اولیه پروژه</label>
                    <select name="status" id="status" class="form-select">
                        <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>در انتظار شروع</option>
                        <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>در حال انجام</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>تکمیل شده</option>
                    </select>
                </div>
                <!-- مهلت پروژه -->
                <div class="form-group">
                    <label for="deadline">تاریخ مهلت (Deadline) <span class="required-star">*</span></label>
                    <input type="date" id="deadline" name="deadline" value="{{ old('deadline') }}" class="form-control @error('deadline') form-control-error @enderror" required>
                    @error('deadline')
                    <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- دکمه‌های فرم -->
            <div class="form-actions">
                <a href="{{ route('projects.index') }}" class="btn-cancel-link">انصراف</a>
                <button type="submit" class="btn-create-submit">✨ ثبت و ایجاد پروژه</button>
            </div>
        </form>

    </div>
</div>
@endsection