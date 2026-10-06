@extends('layouts.app')

@section('content')
<style>
    /* Dashboard Modern Styling */
    .tf-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.75rem;
    }

    .tf-header-title {
        font-size: 1.35rem;
        font-weight: 800;
        color: #0f172a;
    }

    .tf-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.65rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 700;
        border-radius: 10px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .tf-btn-primary {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
    }

    .tf-btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(99, 102, 241, 0.35);
    }

    /* Stat Cards Grid */
    .tf-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .tf-stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        position: relative;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .tf-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    }

    .tf-stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        height: 4px;
    }

    .tf-stat-card.warning::before {
        background: #f59e0b;
    }

    .tf-stat-card.danger::before {
        background: #ef4444;
    }

    .tf-stat-card.success::before {
        background: #10b981;
    }

    .tf-stat-card.primary::before {
        background: #6366f1;
    }

    .tf-stat-title {
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 0.4rem;
        font-weight: 600;
    }

    .tf-stat-value {
        font-size: 1.75rem;
        font-weight: 800;
        color: #0f172a;
    }

    .tf-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
    }

    /* Dashboard Layout Grid */
    .tf-dashboard-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
    }

    @media (max-width: 992px) {
        .tf-dashboard-grid {
            grid-template-columns: 1fr;
        }
    }

    .tf-card-box {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    }

    .tf-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
        padding-bottom: 0.85rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .tf-card-title {
        font-size: 1.05rem;
        font-weight: 800;
        color: #0f172a;
    }

    /* Kanban Mini Board */
    .tf-kanban-cols {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    @media (max-width: 768px) {
        .tf-kanban-cols {
            grid-template-columns: 1fr;
        }
    }

    .tf-kanban-col {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem;
    }

    .tf-kanban-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.825rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 0.85rem;
    }

    .tf-badge {
        font-size: 0.75rem;
        background-color: #e2e8f0;
        color: #475569;
        padding: 0.15rem 0.55rem;
        border-radius: 999px;
        font-weight: 700;
    }

    .tf-empty-box {
        text-align: center;
        padding: 2rem 0.5rem;
        font-size: 0.78rem;
        color: #94a3b8;
        border: 2px dashed #cbd5e1;
        border-radius: 10px;
        background: #ffffff;
    }

    .tf-task-item {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 0.85rem;
        border-radius: 10px;
        margin-bottom: 0.6rem;
        font-size: 0.825rem;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
    }

    .tf-task-item-title {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .tf-task-item-project {
        font-size: 0.725rem;
        color: #64748b;
    }

    /* Activity Log */
    .tf-activity-list {
        display: flex;
        flex-direction: column;
        gap: 0.9rem;
    }

    .tf-activity-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 0.75rem;
    }

    .tf-activity-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .tf-activity-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #6366f1;
        margin-top: 0.3rem;
        flex-shrink: 0;
        box-shadow: 0 0 0 3px #e0e7ff;
    }

    .tf-activity-text {
        font-size: 0.825rem;
        font-weight: 600;
        color: #334155;
    }

    .tf-activity-time {
        font-size: 0.725rem;
        color: #94a3b8;
        margin-top: 0.15rem;
    }
</style>

{{-- Header --}}
<div class="tf-header">
    <h1 class="tf-header-title">
        خوش آمدید، {{ auth()->user()->name ?? 'کاربر' }} 👋
    </h1>
</div>

{{-- Stat Cards --}}
<div class="tf-stats-grid">
    <!-- 1. Due Today -->
    <div class="tf-stat-card warning">
        <div>
            <div class="tf-stat-title">مهلت امروز (Due Today)</div>
            <div class="tf-stat-value">{{ $dueTodayCount ?? 0 }}</div>
        </div>
        <div class="tf-stat-icon" style="background-color: #fef3c7; color: #d97706;">⏳</div>
    </div>

    <!-- 2. Overdue -->
    <div class="tf-stat-card danger">
        <div>
            <div class="tf-stat-title">تأخیر یافته (Overdue)</div>
            <div class="tf-stat-value" style="color: #ef4444;">{{ $overdueCount ?? 0 }}</div>
        </div>
        <div class="tf-stat-icon" style="background-color: #fee2e2; color: #dc2626;">⚠️</div>
    </div>

    <!-- 3. Completed This Week -->
    <div class="tf-stat-card success">
        <div>
            <div class="tf-stat-title">تکمیل‌شده این هفته</div>
            <div class="tf-stat-value">{{ $completedThisWeekCount ?? 0 }}</div>
        </div>
        <div class="tf-stat-icon" style="background-color: #d1fae5; color: #059669;">✅</div>
    </div>

    <!-- 4. Time Logged -->
    <div class="tf-stat-card primary">
        <div>
            <div class="tf-stat-title">کارکرد این هفته (ساعت)</div>
            <div class="tf-stat-value">{{ $loggedHoursThisWeek ?? 0 }}</div>
        </div>
        <div class="tf-stat-icon" style="background-color: #e0e7ff; color: #4f46e5;">⏱️</div>
    </div>
</div>

{{-- Main Grid --}}
<div class="tf-dashboard-grid">

    <!-- My Tasks Section -->
    <div class="tf-card-box">
        <div class="tf-card-header">
            <h2 class="tf-card-title">تسک‌های من (My Tasks)</h2>
            <a href="{{ route('tasks.index') }}" style="font-size: 0.8rem; color: #4f46e5; text-decoration: none; font-weight: 700;">مشاهده همه ←</a>
        </div>
        <div class="tf-kanban-cols">
            <!-- Todo Column -->
            <div class="tf-kanban-col">
                <div class="tf-kanban-head">
                    <span>📌 در انتظار انجام</span>
                    <span class="tf-badge">{{ count($todoTasks ?? []) }}</span>
                </div>
                @forelse($todoTasks ?? [] as $task)
                <div class="tf-task-item">
                    <div class="tf-task-item-title">{{ $task->title }}</div>
                    <div class="tf-task-item-project">{{ $task->project->name ?? 'پروژه' }}</div>
                </div>
                @empty
                <div class="tf-empty-box">تسکی وجود ندارد</div>
                @endforelse
            </div>

            <!-- In Progress Column -->
            <div class="tf-kanban-col">
                <div class="tf-kanban-head">
                    <span>⚡ در حال انجام</span>
                    <span class="tf-badge">{{ count($inProgressTasks ?? []) }}</span>
                </div>
                @forelse($inProgressTasks ?? [] as $task)
                <div class="tf-task-item">
                    <div class="tf-task-item-title">{{ $task->title }}</div>
                    <div class="tf-task-item-project">{{ $task->project->name ?? 'پروژه' }}</div>
                </div>
                @empty
                <div class="tf-empty-box">تسکی در حال انجام نیست</div>
                @endforelse
            </div>

            <!-- Done Column -->
            <div class="tf-kanban-col">
                <div class="tf-kanban-head">
                    <span>✅ انجام شده</span>
                    <span class="tf-badge">{{ count($completedTasks ?? []) }}</span>
                </div>
                @forelse($completedTasks ?? [] as $task)
                <div class="tf-task-item" style="opacity: 0.75;">
                    <div class="tf-task-item-title" style="text-decoration: line-through;">{{ $task->title }}</div>
                    <div class="tf-task-item-project">{{ $task->project->name ?? 'پروژه' }}</div>
                </div>
                @empty
                <div class="tf-empty-box">تسک تکمیل‌شده‌ای نیست</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Activity Log Section -->
    <div class="tf-card-box">
        <div class="tf-card-header">
            <h2 class="tf-card-title">تاریخچه فعالیت‌ها</h2>
        </div>

        <div class="tf-activity-list">
            @forelse($activities ?? [] as $activity)
            <div class="tf-activity-item">
                <div class="tf-activity-dot"></div>
                <div>
                    <div class="tf-activity-text">{{ $activity->description ?? 'فعالیتی ثبت شد' }}</div>
                    <div class="tf-activity-time">{{ $activity->created_at?->diffForHumans() ?? 'اخيراً' }}</div>
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 2.5rem 0; font-size: 0.8rem; color: #94a3b8;">
                هیچ فعالیتی ثبت نشده است
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection