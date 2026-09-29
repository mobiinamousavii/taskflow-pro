@extends('layouts.app')

@section('content')
<style>
    .workspace-wrapper {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        background: linear-gradient(135deg, #f6f8fd 0%, #e9eeef 100%);
        direction: rtl;
    }

    .workspace-card {
        width: 100%;
        max-width: 580px;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.8);
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .card-banner {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        padding: 32px 30px;
        color: #ffffff;
        position: relative;
    }

    .card-banner h1 {
        font-size: 1.5rem;
        font-weight: 800;
        margin: 0 0 8px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-banner p {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.85);
        margin: 0;
        line-height: 1.5;
    }

    .card-body {
        padding: 32px 30px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        font-size: 0.9rem;
        font-weight: 700;
        color: #374151;
        margin-bottom: 8px;
    }

    .required-star {
        color: #ef4444;
    }

    .input-wrapper {
        position: relative;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border-radius: 12px;
        border: 2px solid #e5e7eb;
        font-size: 0.95rem;
        box-sizing: border-box;
        transition: all 0.25s ease;
        font-family: inherit;
        background-color: #f9fafb;
        color: #1f2937;
    }

    .form-control:focus {
        outline: none;
        border-color: #6366f1;
        background-color: #ffffff;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
    }

    .form-control.is-invalid {
        border-color: #ef4444;
        background-color: #fef2f2;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 110px;
    }

    .error-text {
        color: #ef4444;
        font-size: 0.825rem;
        margin-top: 6px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        margin-top: 32px;
        padding-top: 20px;
        border-top: 1px solid #f3f4f6;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-secondary {
        background-color: #f3f4f6;
        color: #4b5563;
    }

    .btn-secondary:hover {
        background-color: #e5e7eb;
        color: #1f2937;
    }

    .btn-primary {
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(79, 70, 229, 0.45);
    }

    .btn-primary:active {
        transform: translateY(0);
    }
</style>

<div class="workspace-wrapper">
    <div class="workspace-card">
        {{-- بنر رنگی بالای کارت --}}
        <div class="card-banner">
            <h1>
                <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
 </svg>
                ایجاد ورک‌اسپیس جدید
            </h1>
            <p>فضای کاری جدیدی برای مدیریت پروژه‌ها، اعضای تیم و تسک‌های خود بسازید.</p>
        </div>

        <div class="card-body">
            <form action="{{ route('workspaces.store') }}" method="POST">
                @csrf

                {{-- نام ورک‌اسپیس --}}
                <div class="form-group">
                    <label for="name" class="form-label">
                        نام ورک‌اسپیس <span class="required-star">*</span>
                    </label>
                    <div class="input-wrapper">
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name') }}"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="مثال: تیم توسعه نرم‌افزار، مارکتینگ..."
                               required>
                    </div>
                    @error('name')
                        <p class="error-text">
                            <span>⚠️</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- توضیحات --}}
                <div class="form-group">
                    <label for="description" class="form-label">
                        توضیحات (اختیاری)
                    </label>
                    <div class="input-wrapper">
                        <textarea name="description" 
                                  id="description" 
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="خلاصه‌ای از هدف یا پروژه‌های این ورک‌اسپیس را بنویسید...">{{ old('description') }}</textarea>
                    </div>
                    @error('description')
                        <p class="error-text">
                            <span>⚠️</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- دکمه‌ها --}}
                <div class="form-actions">
                    <a href="{{ route('workspaces.index') }}" class="btn btn-secondary">
                        انصراف
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <span>+</span> ساخت ورک‌اسپیس
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection