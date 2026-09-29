 @extends('layouts.app')

@section('content')
<style>
    :root {
        --bg-dark: #05070f;
        --card-glass: rgba(15, 23, 42, 0.75);
        --card-border: rgba(255, 255, 255, 0.1);
        --primary-glow: #d946ef;
        --secondary-glow: #0ea5e9;
        --accent-glow: #8b5cf6;
        --text-bright: #ffffff;
        --text-muted: #94a3b8;
    }

    .workspace-wrapper {
        min-height: 100vh;
        padding: 60px 20px;
        background: var(--bg-dark);
        background-image: 
            radial-gradient(circle at 50% 0%, rgba(217, 70, 239, 0.22) 0%, transparent 60%),
            radial-gradient(circle at 100% 50%, rgba(14, 165, 233, 0.18) 0%, transparent 50%),
            radial-gradient(circle at 0% 100%, rgba(139, 92, 246, 0.2) 0%, transparent 50%);
        color: var(--text-bright);
        direction: rtl;
        font-family: system-ui, -apple-system, sans-serif;
        position: relative;
    }

    .container {
        max-width: 1240px;
        margin: 0 auto;
    }

    /* کارت هدر فضایی (Cyber Hero Card) */
    .hero-card {
        background: var(--card-glass);
        backdrop-filter: blur(30px);
        -webkit-backdrop-filter: blur(30px);
        border: 1px solid var(--card-border);
        border-radius: 32px;
        padding: 44px;
        margin-bottom: 36px;
        box-shadow: 0 30px 70px rgba(0, 0, 0, 0.8), inset 0 1px 0 rgba(255, 255, 255, 0.15);
        position: relative;
        overflow: hidden;
    }

    .hero-card::before {
        content: '';
        position: absolute;
        top: -2px; left: -2px; right: -2px; bottom: -2px;
        background: linear-gradient(135deg, rgba(217, 70, 239, 0.4), transparent, rgba(14, 165, 233, 0.4));
        border-radius: 34px;
        z-index: -1;
    }

    .hero-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 28px;
        flex-wrap: wrap;
    }

    .hero-title-group {
        display: flex;
        align-items: center;
        gap: 24px;
    }

    /* آواتار متحرک و نئونی */
    .hero-avatar {
        width: 96px;
        height: 96px;
        border-radius: 28px;
        background: linear-gradient(135deg, #d946ef 0%, #8b5cf6 50%, #0ea5e9 100%);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.8rem;
        font-weight: 900;
        box-shadow: 0 0 40px rgba(217, 70, 239, 0.55);
        flex-shrink: 0;
        animation: pulseGlow 4s infinite alternate;
    }

    @keyframes pulseGlow {
        0% { box-shadow: 0 0 30px rgba(217, 70, 239, 0.4); }
        100% { box-shadow: 0 0 50px rgba(14, 165, 233, 0.7); }
    }

    .hero-info h1 {
        font-size: 2.3rem;
        font-weight: 900;
        color: #ffffff;
        margin: 0 0 10px 0;
        letter-spacing: -0.5px;
    }

    .hero-info p {
        font-size: 1rem;
        color: var(--text-muted);
        margin: 0;
        line-height: 1.7;
        max-width: 620px;
    }

    /* دکمه‌ها */
    .btn {
        padding: 14px 30px;
        border-radius: 18px;
        font-size: 0.95rem;
        font-weight: 800;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 10px;
        border: none;
    }

    .btn-dark-glass {
        background: rgba(255, 255, 255, 0.06);
        color: #f1f5f9;
        border: 1px solid rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(12px);
    }

    .btn-dark-glass:hover {
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.5);
    }

    .btn-neon-primary {
        background: linear-gradient(135deg, #d946ef 0%, #8b5cf6 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(217, 70, 239, 0.45);
    }
 .btn-neon-primary:hover {
        transform: translateY(-3px) scale(1.03);
        box-shadow: 0 15px 40px rgba(217, 70, 239, 0.7);
        color: #ffffff;
    }

    /* کارت‌های آمار بالای پروژه‌ها */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 22px;
        margin-bottom: 44px;
    }

    .stat-box {
        background: var(--card-glass);
        backdrop-filter: blur(20px);
        border: 1px solid var(--card-border);
        border-radius: 24px;
        padding: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s ease;
    }

    .stat-box:hover {
        transform: translateY(-5px);
        border-color: rgba(255, 255, 255, 0.2);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
    }

    .stat-title {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-bottom: 6px;
    }

    .stat-number {
        font-size: 1.8rem;
        font-weight: 900;
        color: #fff;
    }

    .stat-icon-wrapper {
        width: 56px;
        height: 56px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .icon-magenta { background: rgba(217, 70, 239, 0.15); color: #f472b6; border: 1px solid rgba(217, 70, 239, 0.3); }
    .icon-cyan { background: rgba(14, 165, 233, 0.15); color: #38bdf8; border: 1px solid rgba(14, 165, 233, 0.3); }
    .icon-violet { background: rgba(139, 92, 246, 0.15); color: #c084fc; border: 1px solid rgba(139, 92, 246, 0.3); }

    /* هدر بخش پروژه‌ها */
    .section-title-wrap {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 28px;
    }

    .section-title {
        font-size: 1.45rem;
        font-weight: 900;
        color: #fff;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    /* کارت‌های گرید پروژه */
    .projects-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 30px;
    }

    .project-card {
        background: var(--card-glass);
        backdrop-filter: blur(20px);
        border: 1px solid var(--card-border);
        border-radius: 28px;
        padding: 32px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }

    .project-card::before {
        content: '';
        position: absolute;
        top: 0; right: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, #d946ef, #0ea5e9);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .project-card:hover {
        transform: translateY(-8px) scale(1.02);
        border-color: rgba(217, 70, 239, 0.4);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.7), 0 0 35px rgba(217, 70, 239, 0.25);
    }

    .project-card:hover::before {
        opacity: 1;
    }

    .project-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 18px;
    }

    .project-badge {
        font-size: 0.75rem;
        padding: 4px 14px;
        border-radius: 20px;
        background: rgba(16, 185, 129, 0.18);
        color: #34d399;
        border: 1px solid rgba(16, 185, 129, 0.35);
        font-weight: 800;
    }

    .project-title {
        font-size: 1.3rem;
        font-weight: 800;
        color: #ffffff;
        margin: 0 0 10px 0;
    }

    .project-desc {
        font-size: 0.9rem;
        color: var(--text-muted);
        line-height: 1.7;
        margin: 0 0 28px 0;
    }

    .project-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        padding-top: 20px;
    }
 .project-meta {
        font-size: 0.82rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .project-action {
        color: #38bdf8;
        font-size: 0.9rem;
        font-weight: 800;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.25s ease;
    }

    .project-card:hover .project-action {
        gap: 12px;
        color: #f472b6;
        text-shadow: 0 0 10px rgba(244, 114, 182, 0.5);
    }

    /* باکس خالی بودن لیست */
    .empty-card {
        background: var(--card-glass);
        border: 2px dashed rgba(255, 255, 255, 0.15);
        border-radius: 32px;
        padding: 80px 20px;
        text-align: center;
    }

    .empty-icon-wrap {
        width: 88px;
        height: 88px;
        border-radius: 30px;
        background: linear-gradient(135deg, rgba(217, 70, 239, 0.2), rgba(14, 165, 233, 0.2));
        border: 1px solid rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px auto;
        font-size: 2.2rem;
        color: #f472b6;
        box-shadow: 0 0 30px rgba(217, 70, 239, 0.3);
    }
</style>

<div class="workspace-wrapper">
    <div class="container">

        {{-- هدر ورک‌اسپیس --}}
        <div class="hero-card">
            <div class="hero-header">
                <div class="hero-title-group">
                    <div class="hero-avatar">
                        {{ mb_substr($workspace->name, 0, 1) }}
                    </div>
                    <div class="hero-info">
                        <h1>{{ $workspace->name }}</h1>
                        <p>{{ $workspace->description ?? 'هیچ توضیحی برای این فضای کاری تعریف نشده است.' }}</p>
                    </div>
                </div>

                <div class="hero-actions">
                    <a href="{{ route('workspaces.index') }}" class="btn btn-dark-glass">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                        بازگشت
                    </a>
                    @if(Route::has('projects.create'))
                        <a href="{{ route('projects.create', ['workspace_id' => $workspace->id]) }}" class="btn btn-neon-primary">
                            <span>✨</span> ساخت پروژه جدید
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- آمار سریع --}}
        <div class="stats-container">
            <div class="stat-box">
                <div>
                    <div class="stat-title">مجموع پروژه‌ها</div>
                    <div class="stat-number">{{ isset($workspace->projects) ? $workspace->projects->count() : 0 }}</div>
                </div>
                <div class="stat-icon-wrapper icon-magenta">📁</div>
            </div>

            <div class="stat-box">
                <div>
                    <div class="stat-title">وضعیت سیستم</div>
                    <div class="stat-number" style="font-size: 1.3rem; color: #34d399;">آنلاین / فعال</div>
                </div>
                <div class="stat-icon-wrapper icon-cyan">⚡</div>
            </div>

            <div class="stat-box">
                <div>
                    <div class="stat-title">زمان ایجاد</div>
                    <div class="stat-number" style="font-size: 1.1rem; color: #cbd5e1;">
                        {{ $workspace->created_at ? $workspace->created_at->format('Y/m/d') : 'ثبت نشده' }}
                    </div>
                </div>
                <div class="stat-icon-wrapper icon-violet">📅</div>
            </div>
        </div>
 {{-- بخش پروژه‌ها --}}
        <div class="section-title-wrap">
            <h2 class="section-title">
                <svg width="26" height="26" fill="none" stroke="#d946ef" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                لیست پروژه‌های این ورک‌اسپیس
            </h2>
        </div>

        {{-- گرید کارت‌ها --}}
        @if(isset($workspace->projects) && $workspace->projects->isNotEmpty())
            <div class="projects-grid">
                @foreach($workspace->projects as $project)
                    <div class="project-card">
                        <div>
                            <div class="project-top">
                                <span class="project-badge">در حال توسعه</span>
                            </div>
                            <h3 class="project-title">{{ $project->name }}</h3>
                            <p class="project-desc">{{ $project->description ?? 'بدون توضیحات اضافی' }}</p>
                        </div>

                        <div class="project-footer">
                            <span class="project-meta">
                                🕒 {{ $project->created_at ? $project->created_at->diffForHumans() : '' }}
                            </span>
                            @if(Route::has('projects.show'))
                                <a href="{{ route('projects.show', $project->id) }}" class="project-action">
                                    مشاهده تسک‌ها ➔
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-card">
                <div class="empty-icon-wrap">🚀</div>
                <h3 style="color: #fff; margin-bottom: 8px; font-size: 1.3rem;">هنوز پروژه‌ای ثبت نشده است</h3>
                <p style="color: #94a3b8; margin-bottom: 28px;">می‌توانید با کلیک روی دکمه زیر اولین پروژه این ورک‌اسپیس را ایجاد کنید.</p>
                @if(Route::has('projects.create'))
                    <a href="{{ route('projects.create', ['workspace_id' => $workspace->id]) }}" class="btn btn-neon-primary">
                        + ساخت اولین پروژه
                    </a>
                @endif
            </div>
        @endif

    </div>
</div>
@endsection