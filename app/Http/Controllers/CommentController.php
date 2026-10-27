<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\project;
use App\Models\Task;
use App\Models\User;
use App\Models\workspace;
use App\Notifications\UserMentionedNotification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request,workspace $workspace,project $project, Task $task): JsonResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ]);

        // پاکسازی متن برای محافظت در برابر XSS
        $sanitizedBody = htmlspecialchars($validated['body'], ENT_QUOTES, 'UTF-8');

        $comment = $task->comment()->create([
            'user_id' => Auth::id(),
            'body' => $sanitizedBody,
        ]);

        // شناسایی منشن‌ها (الگوی @username) در متن کامنت
        preg_match_all('/@([a-zA-Z0-9_]+)/', $validated['body'], $matches);
        
        if (!empty($matches[1])) {
            $usernames = $matches[1];
            
            // پیدا کردن کاربران منشن‌شده (بر اساس نام کاربری یا نام)
            $mentionedUsers = User::whereIn('name', $usernames)->get();

            // foreach ($mentionedUsers as $user) {
            //     // کاربر خودش را منشن کند نیازی به نوتیفیکیشن نیست
            //     if ($user->id !== auth()->id()) {
            //         $user->notify(new UserMentionedNotification($comment));
            //     }
            // }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'کامنت با موفقیت ثبت شد و منشن‌ها بررسی شدند.',
            'data' => $comment->load('user:id,name'),
        ], 201);
    }

    public function destroy(workspace $workspace,project $project,Task $task,Comment $comment): JsonResponse
    {
        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'شما اجازه حذف این کامنت را ندارید.',
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'کامنت با موفقیت حذف شد',
        ]);
    }
}