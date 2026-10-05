@extends('layouts.app')

@section('title', 'لیست پروژه‌ها | TaskFlow')

@push('styles')
<style>
    .project-container {
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

    .badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-workspace {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .badge-status-pending {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .badge-status-progress {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #dbeafe;
    }

    .badge-status-completed {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .actions-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-action-icon {
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

    .btn-action-icon:hover {
        background: #f1f5f9;
        color: var(--primary);
    }

    .btn-delete:hover {
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
<div class="project-container">

    <!-- هدر صفحه -->
    <div class="page-header">
        <div class="page-title">
            <h1>پروژه‌ها</h1>
            <p>مدیریت و مشاهده لیست تمام پروژه‌های تعریف‌شده در فضاهای کاری</p>
        </div>
        <a href="{{ route('projects.create') }}" class="btn btn-primary">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>تعریف پروژه جدید</span>
        </a>
    </div>

    <!-- پیام موفقیت -->
    @if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
    @endif
    <!-- جدول لیست پروژه‌ها -->
    <div class="table-card">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>عنوان پروژه</th>
                    <th>فضای کاری مربوطه</th>
                    <th style="text-align: center;">وضعیت</th>
                    <th style="text-align: center;">تاریخ ایجاد</th>
                    <th style="text-align: center;">عملیات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects as $project)
                <tr>
                    <td style="font-weight: 700; color: #0f172a;">
                        {{ $project->title ?? $project->name }}
                    </td>
                    <td>
                        <span class="badge badge-workspace">
                            {{ $project->workspace->name ?? 'نامشخص' }}
                        </span>
                    </td>
                    <td style="text-align: center;">
                        @if(($project->status ?? '') === 'completed')
                        <span class="badge badge-status-completed">تکمیل شده</span>
                        @elseif(($project->status ?? '') === 'in_progress')
                        <span class="badge badge-status-progress">در حال انجام</span>
                        @else
                        <span class="badge badge-status-pending">در انتظار شروع</span>
                        @endif
                    </td>
                    <td style="text-align: center; color: #94a3b8; font-size: 0.8rem;">
                        @if($project->created_at)
                        {{ \Morilog\Jalali\Jalalian::fromDateTime($project->created_at)->format('Y/m/d') }}
                        @else
                        -
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <div class="actions-wrapper">
                            <!-- حذف -->
                            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('آیا از حذف این پروژه مطمئن هستید؟')" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action-icon btn-delete" title="حذف پروژه">
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
                    <td colspan="5" class="empty-state">
                        هنوز هیچ پروژه‌ای تعریف نشده است.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection