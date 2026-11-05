<?php

namespace App\Http\Controllers;

use App\Mail\WorkspaceInvitationMail;
use Illuminate\Http\Request;
use App\Models\workspace;
use App\Models\workspace_invitation;
use App\Models\Role;
use App\Models\User;
use App\Notifications\WorkspaceInvitationNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class Workspace_InvitationController extends Controller
{
    public function store(Request $request, workspace $workspace)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'role_id' => [
                'required',
                Rule::exists('roles', 'id')->where('workspace_id', $workspace->id)
            ],
        ]);
        $userexists = $workspace->users()->where('email', $request->email)->exists();
        if ($userexists) {
            return response()->json([
                'status' => 'error',
                'message' => 'این کاربر هم اکنون عضو ورک اسپیس است',
            ]);
        }
        // بررسی اینکه دعوتنامه معلق دیگریpending برای این ایمیل وجود داره یا نه
        $existinvitation = $workspace->invitations()
            ->where('email', $request->email)
            ->where('status', 'pending')->first();

        // dd($existinvitation);

        if ($existinvitation) {
            return response()->json([
                'status' => 'error',
                'message' => 'یک دعوت نامه قبلا برای این ایمیل ارسال شده',
            ]);
        }

        $token = Str::random(40);

        $invitation = workspace_invitation::create([
            'workspace_id' => $workspace->id,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'token' => $token,
            'status' => 'pending',
            'expires_at' => now()->addHours(72),
            // برای تست
            // 'expires_at' => now()->addMinute(1),
        ]);

        // dd('test');
        // ارسال ایمیل با کیو
        // Mail::to($request->email)->queue(new WorkspaceInvitationMail($invitation));

        //  اضافه کردن تریگر نوتیفیکیشن دیتابیس
        $user = User::where('email', $request->email)->first();
        if ($user) {
            // اگر کاربر در سیستم حساب کاربری داشته باشه نوتیفیکیشن دیتابیس براش ثبت میشه
            $user->notify(new WorkspaceInvitationNotification($invitation));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'دعوت نامه با موفقیت ایجاد و به صف ارسال شد',
            'data' => [
                'invitation_token' => $token,
                'expires_at' => $invitation->expires_at,
            ]
        ]);
    }

    public function accept(Request $request, $token)
    {
        // وقتی توکن اصلا تو دیتابیس نباشه اکسپت شده یا  کنسل شده این ارورو میده
        $invitation = workspace_invitation::where('token', $token)
            ->where('status', 'pending')
            ->first();

        if (!$invitation) {
            return response()->json([
                'status' => 'error',
                'message' => 'دعوت‌نامه نامعتبر است یا قبلاً استفاده شده است.'
            ]);
        }

        // بررسی انقضای تاریخ دعوت‌نامه
        // greaterThan($invitation->expires_atبااین دستور 
        // چک میکنیم که زمان الان از تاریخ انقضا گذشته یا نه 
        if (now()->greaterThan($invitation->expires_at)) {
            $invitation->update(['status' => 'expired']);
            return response()->json([
                'status' => 'error',
                'message' => 'تاریخ انقضای این دعوت‌نامه به پایان رسیده است.'
            ]);
        }
        // بررسی امنیت و تطابق ایمیل
        $user = $request->user();

        // بررسی تطابق ایمیل کاربر لاگین‌شده با ایمیل  تو دعوت‌نامه
        if ($user->email !== $invitation->email) {
            return response()->json([
                'status' => 'error',
                'message' => 'این دعوت‌نامه متعلق به حساب کاربری شما نیست.'
            ]);
        }

        $workspace = $invitation->workspace;

        if (!$workspace->users()->where('user_id', $user->id)->exists()) {
            $workspace->users()->attach($user->id, [
                'role_id' => $invitation->role_id,
            ]);
        }

        // تغییر وضعیت دعوت‌نامه به accepted
        $invitation->update(['status' => 'accepted']);

        return response()->json([
            'status' => 'success',
            'message' => 'با موفقیت به ورک‌اسپیس ملحق شدید.'
        ]);
    }

    // وقتی دعوت رو حالت پندینگ قبل از اکسپت شدنه میشه لغوش کرد
    public function destroy(workspace $workspace, workspace_invitation $invitation)
    {
        //  بررسی اینکه آیا دعوت‌نامه هنوز معلقه یا  نه
        if ($invitation->status !== 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'فقط دعوت‌نامه‌های معلق قابل لغو هستند.'
            ]);
        }

        // تغییر وضعیت به cancelled یا حذف کامل رکورد
        $invitation->update(['status' => 'cancelled']);
        // $invitation->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'دعوت‌نامه با موفقیت لغو شد.'
        ]);
    }
}
