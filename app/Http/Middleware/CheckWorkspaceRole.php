<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckWorkspaceRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 
        $workspace = $request->route('workspace');
        if (!$workspace) {
            return response()->json([
                'status' => 'error',
                'message' => 'ورک اسپیس موردنظر یافت نشد',
            ]);
        }
        // چک کردن اینکه کاربر عضو این ورک اسپیس هست یا نه
        // از طریق جدول ورک اسپیس یوزر هم رابطه یوزر با ورک اسپیس رو میفهمم
        // مثلا این کاربر عضو این ورک اسپیس هست یا نه و رول اش رو میفهمم
        $user = $request->user();
        $membership = $workspace->users()->where('user_id', $user->id)->first();
        if (!$membership) {
            return response()->json([
                'status' => 'error',
                'message' => 'شما عضو این ورک اسپیس نیستید و دسترسی ندارید',
            ]);
        }

        // اگه برای این مسیر نقش خاصی تعیین کرده بودیم چک میکنیم
        if (!empty($roles)) {
            // پیدا کردن نقش کاربر
            $userrole = $membership->pivot->role;
            // نقش کاربر در لیست نفش های مجاز وجود داره یا نه 
            // چون داخل روت بعضی وقتا مینویسم workspace.role
            // این ینی هر عضوی با هر نقشی که بیاد قبوله
            // workspace.role:owner,adminاین ینی فقد ادمین و مدیر اجازه دارن  
            // دسترسی مطلق مدیر
            if ($userrole!== 'owner' && in_array($userrole, $roles)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'شما دسترسی لازم برای این عملیات را ندارید',
                ]);
            }
        }

        // داخل فایل app.php  این میدلورو ثبت کردم
        return $next($request);
    }
}
