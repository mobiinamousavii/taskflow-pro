<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $workspace->name }} | سیستم مدیریت پروژه</title>
    <!-- Vazirmatn Font -->
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />
    <style>
        :root {
            --bg-main: #f8fafc;
            --bg-card: #ffffff;
            --bg-sidebar: #ffffff;
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --accent-green: #10b981;
            --accent-purple: #8b5cf6;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Vazirmatn', sans-serif;
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-main);
            min-height: 100vh;
            direction: rtl;
        }

        .app-layout {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* Sidebar Styling (Light Theme) */
        .sidebar {
            width: 260px;
            background-color: var(--bg-sidebar);
            border-left: 1px solid var(--border);
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .brand {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
            padding: 0 0.5rem;
        }

        .nav-menu {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .nav-item a:hover,
        .nav-item.active a {
            background-color: #e0e7ff;
            color: var(--primary);
            font-weight: 600;
        }

        /* Main Content Area */
        .main-content {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }

        /* Workspace Header */
        .workspace-header {
            background-color: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.75rem 2rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        }

        .workspace-title-area h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            word-break: break-word;
            color: var(--text-main);
        }

        .workspace-description {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.6;
            word-break: break-word;
        }

        .btn-primary {
            background-color: var(--primary);
            color: #ffffff;
            padding: 0.65rem 1.25rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.2s;
            white-space: nowrap;
            border: none;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        /* Stats Strip */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        }

        .stat-title {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--text-main);
        }

        /* Dashboard Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
        }

        .section-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--border);
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-main);
        }

        /* Projects List */
        .projects-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .project-item {
            background-color: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 1rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.2s;
        }

        .project-item:hover {
            border-color: var(--primary);
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .project-name {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--text-main);
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.6rem;
            border-radius: 20px;
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        /* Members List */
        .members-list {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .member-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem 0;
        }

        .member-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .member-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #e0e7ff;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .member-name {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-main);
        }

        .member-role {
            font-size: 0.75rem;
            color: var(--text-muted);
            background-color: #f1f5f9;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
        }

        .empty-state {
            padding: 2rem;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        @media (max-width: 1024px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .app-layout {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                border-left: none;
                border-bottom: 1px solid var(--border);
            }
        }
    </style>
</head>
<body>

<div class="app-layout">
    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <div>
            <div class="brand">
                <span>⚡ TaskFlow</span>
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="{{ route('workspaces.index') }}">
                        <span>←</span>
                        <span>بازگشت به فضاهای کاری</span>
                    </a>
                </li>
                <li class="nav-item active">
                    <a href="#">
                        <span>📊</span>
                        <span>داشبورد فضای کاری</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="main-content">
        
        <!-- Workspace Banner -->
        <div class="workspace-header">
            <div class="workspace-title-area">
                <h1>{{ $workspace->name }}</h1>
                <p class="workspace-description">
                    {{ $workspace->description ?? 'توضیحاتی برای این فضای کاری ثبت نشده است.' }}
                </p>
            </div>
            <div>
                <a href="{{route('projects.create')}}" class="btn-primary">+ پروژه جدید</a>
            </div>
        </div>

        <!-- Overview Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-title">پروژه‌های فعال</div>
                <div class="stat-value">{{ $workspace->projects->count() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">تعداد اعضا</div>
                <div class="stat-value">
                    {{ $workspace->relationLoaded('users') ? $workspace->users->count() : 1 }}
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-title">وضعیت</div>
                <div class="stat-value" style="color: var(--accent-green); font-size: 1.1rem; margin-top: 0.4rem;">فعال</div>
            </div>
        </div>

        <!-- Dashboard Main Grid -->
        <div class="dashboard-grid">
            
            <!-- Projects Section -->
            <div class="section-card">
                <div class="section-header">
                    <h2 class="section-title">پروژه‌ها</h2>
                    <a href="{{route('projects.index')}}" style="color: var(--primary); text-decoration: none; font-size: 0.85rem;">مشاهده همه</a>
                </div>

                @if($workspace->projects->count() > 0)
                    <div class="projects-list">
                        
                        @foreach($workspace->projects as $project)
                            <div class="project-item">
                                <div>
                                    <div class="project-name">{{ $project->name }}</div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <span class="status-badge">در حال اجرا</span>
                                    <a href="{{route('projects.show' , $project->id)}}" style="color: var(--primary); text-decoration: none; font-size: 0.85rem;">ورود به پروژه ←</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <p style="margin-bottom: 1rem;">هیچ پروژه‌ای در این فضای کاری ایجاد نشده است.</p>
                        <a href="{{route('projects.create')}}" class="btn-primary" style="font-size: 0.85rem;">+ اولین پروژه را بسازید</a>
                    </div>
                @endif
            </div>

            <!-- Members Section -->
            <div class="section-card">
                <div class="section-header">
                    <h2 class="section-title">اعضای فضای کاری</h2>
                    <a href="#" class="btn-primary" style="padding: 0.35rem 0.75rem; font-size: 0.8rem;">+ عضو جدید</a>
                </div>

                <div class="members-list">
                    @if($workspace->relationLoaded('users') && $workspace->users->count() > 0)
                        @foreach($workspace->users as $user)
                            <div class="member-item">
                                <div class="member-info">
                                    <div class="member-avatar">{{ mb_substr($user->name, 0, 1) }}</div>
                                    <div>
                                        <div class="member-name">{{ $user->name }}</div>
                                    </div>
                                </div>
                                <span class="member-role">{{ $user->pivot->role ?? 'عضو' }}</span>
                            </div>
                        @endforeach
                    @else
                        <div class="member-item">
                            <div class="member-info">
                                <div class="member-avatar">م</div>
                                <div>
                                    <div class="member-name">مبینا موسوی</div>
                                </div>
                            </div>
                            <span class="member-role">مالک</span>
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </main>
</div>

</body>
</html>