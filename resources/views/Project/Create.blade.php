@extends('layouts.app')

@section('title', 'تعریف پروژه جدید | TaskFlow')

@push('styles')
<style>
    .form-container {
        max-width: 650px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .form-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        padding: 32px;
    }

    .form-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f1f5f9;
    }

    .form-header h1 {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        padding: 10px 14px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        font-family: inherit;
        font-size: 0.9rem;
        color: #0f172a;
        background-color: #ffffff;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    }

    .form-control-error {
        border-color: var(--danger) !important;
    }

    .error-text {
        color: var(--danger);
        font-size: 0.8rem;
        margin-top: 6px;
    }

    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid #f1f5f9;
    }

    .required-star {
        color: var(--danger);
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <div class="form-card">

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
        @endif

        <div class="form-header">
            <h1>تعریف پروژه جدید</h1>
            <a href="{{ route('projects.index') }}" class="btn btn-outline" style="font-size: 0.8rem; padding: 6px 12px;">
                انصراف و بازگشت
            </a>
        </div>

        <form action="{{ route('projects.store') }}" method="POST">
            @csrf

            <!-- انتخاب فضای کاری مربوطه -->
            <select name="workspace_id" class="form-select">
                <option value="">...انتخاب فضای کاری...</option>
                @forelse($workspaces as $workspace)
                <option value="{{ $workspace->id }}">{{ $workspace->name }}</option>
                @empty
                <option disabled>شما هنوز هیچ فضای کاری ایجاد نکرده‌اید</option>
                @endforelse
            </select>

            <!-- عنوان پروژه -->
            <div class="form-group">
                <label for="name">عنوان پروژه <span class="required-star">*</span></label>
                <input type="text" name="name" id="title" value="{{ old('name') }}" placeholder="مثلاً: طراحی فرانت‌اند رزرویشن" class="form-control @error('title') form-control-error @enderror" required>
                @error('name')
                <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <!-- توضیحات پروژه -->
            <div class="form-group">
                <label for="description">توضیحات پروژه</label>
                <textarea name="description" id="description" rows="4" placeholder="توضیحات و اهداف پروژه..." class="form-control">{{ old('description') }}</textarea>
                @error('description')
                <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <!-- وضعیت پروژه -->
            <div class="form-group">
                <label for="status">وضعیت اولیه پروژه</label>
                <select name="status" id="status" class="form-control">
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>در انتظار شروع</option>
                    <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>در حال انجام</option>
                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>تکمیل شده</option>
                </select>
            </div>

            <!-- <div class="form-group">
                <label for="start_at">تاریخ شروع</label>
                <input type="date" id="start_at" name="start_at" value="{{old('start_at')}}" required>
            </div> -->

             <div class="form-group">
                <label for="deadline"> مهلت</label>
                <input type="date" id="deadline" name="deadline" value="{{old('deadline')}}" required>
            </div>
        

            <!-- دکمه‌های فرم -->
            <div class="form-actions">
                <a href="{{ route('projects.index') }}" class="btn btn-outline">انصراف</a>
                <button type="submit" class="btn btn-primary">ثبت و ایجاد پروژه</button>
            </div>
        </form>

    </div>
</div>
@endsection