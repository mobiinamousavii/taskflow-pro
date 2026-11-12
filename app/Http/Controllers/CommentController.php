<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;
use App\Notifications\NewCommentNotification;
use App\Notifications\UserMentionedNotification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;


// کدهای مخرب رو مثل تگ اسکریپت قبل اینکه بره تو دیتابیس تبدیل به متن میکنه
class CommentController extends Controller
{
    //     public function __construct()
    // {
    //     $this->authorizeResource(Comment::class);
    // }

    public function store(Request $request, Workspace $workspace, Project $project, Task $task): JsonResponse
    {
        $this->authorize('create', [$workspace]);
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ]);

        // پاکسازی متن برای محافظت در برابر XSS
        $sanitizedBody = htmlspecialchars($validated['body'], ENT_QUOTES, 'UTF-8');

        $comment = $task->comments()->create([
            'user_id' => Auth::id(),
            'body'    => $sanitizedBody,
        ]);

        //  اگه مسئول تسک (assignee) وجود داشت و خودش نویسنده این کامنت نبود، بهش نوتیفیکیشن بده
        if ($task->assignee_id && $task->assignee_id !== Auth::id()) {
            $assignee = User::find($task->assignee_id);
            if ($assignee) {
                $task->assignee->notify(new NewCommentNotification($comment, $task));
            }
        }

        //شناسایی منشن‌ها (پشتیبانی کامل از حروف فارسی، انگلیسی و اعداد)
        preg_match_all('/@([\p{L}\p{N}_]+)/u', $validated['body'], $matches);

        if (!empty($matches[1])) {
            $usernames = $matches[1];

            // پیدا کردن کاربران منشن‌شده (بر اساس نام یا نام کاربری بسته به ساختار دیتابیس)
            $mentionedUsers = $project->users()->whereIn('name', $usernames)->get();
            foreach ($mentionedUsers as $user) {
                // کاربر خودش رو منشن کنه نیازی به نوتیفیکیشن نیست
                if ($user->id !== Auth::id()) {
                    // ارسال نوتیفیکیشن دیتابیس به کاربر منشن‌شده 
                    $user->notify(new UserMentionedNotification(Auth::user(), $task));
                }
            }
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'کامنت با موفقیت ثبت شد و منشن‌ها بررسی شدند.',
            'data'    => $comment->load('user:id,name'),
        ]);
    }

    public function update(Request $request, Workspace $workspace, Project $project, Task $task, Comment $comment): JsonResponse
    {
        $this->authorize('update', [$comment]);
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ]);

        $sanitizedBody = htmlspecialchars($validated['body'], ENT_QUOTES, 'UTF-8');

        $comment->update([
            'body' => $sanitizedBody,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'کامنت با موفقیت ویرایش شد.',
            'data'    => $comment->load('user:id,name'),
        ]);
    }



    public function destroy(Workspace $workspace, Project $project, Task $task, Comment $comment): JsonResponse
    {
        $this->authorize('delete', [$comment]);
        if ($comment->task_id !== $task->id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'کامنت مورد نظر یافت نشد.',
            ]);
        }

        $comment->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'کامنت با موفقیت حذف شد',
        ]);
    }
}
