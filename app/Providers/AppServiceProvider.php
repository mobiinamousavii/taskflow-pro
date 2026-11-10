<?php

namespace App\Providers;

use App\Events\TaskActivityEvent;
use App\Listeners\LogTaskActivityListener;
use App\Models\Attachment;
use App\Models\Comment;
use App\Models\project;
use App\Models\task;
use App\Models\workspace;
use App\Observers\TaskObserver;
use App\Policies\AttachmentPolicy;
use App\Policies\CommentPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\TaskPolicy;
use App\Policies\WorkspacePolicy;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       Gate::policy(workspace::class, WorkspacePolicy::class);
       Gate::policy(Task::class, TaskPolicy::class);
       Gate::policy(project::class, ProjectPolicy::class);
       Gate::policy(Comment::class, CommentPolicy::class);
       Gate::policy(Attachment::class, AttachmentPolicy::class);
    }
}
