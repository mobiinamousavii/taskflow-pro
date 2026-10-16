<?php

namespace App\Http\Controllers;

use App\Mail\WorkspaceInvitationMail;
use Illuminate\Http\Request;
use App\Models\workspace;
use App\Models\workspace_invitation;
use App\Models\Role; // اضافه کردن مدل Role برای پیدا کردن آیدی رول
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class Workspace_InvitationController extends Controller
{
    public function store(Request $request, workspace $workspace)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'role' => ['required', 'in:admin,member,viewer'],
        ]);
        // بررسی اینکه کاربر از قبل عضو ورک اسپیس بوده یا نه
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
        //   تولید توکن امن و یونیک و ذخیره اش
        $token = str::random(40);

        $invitation = workspace_invitation::create([
            'workspace_id' => $workspace->id,
            'email' => $request->email,
            'role' => $request->role,
            'token' => $token,
            'status' => 'pending',
            'expires_at' => now()->addHours(72),
            // برای تست
            // 'expires_at' => now()->addMinute(1),
        ]);

        // dd('test');
        // ارسال ایمیل از طریق صف (Queue) به جای ارسال مستقیم
        Mail::to($request->email)->queue(new WorkspaceInvitationMail($invitation));

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
        // پیدا کردن دعوت نامه با توکن
        // وقتی توکن اصلا تو دیتابیس نباشه اکسپت شده یا  کنسل شده این ارور
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
        // اگه گذشت وضعیتش به expired
        if (now()->greaterThan($invitation->expires_at)) {
            $invitation->update(['status' => 'expired']);
            return response()->json([
                'status' => 'error',
                'message' => 'تاریخ انقضای این دعوت‌نامه به پایان رسیده است.'
            ]);
        }
        // بررسی امنیت و تطابق ایمیل
        $user = $request->user();

        // بررسی تطابق ایمیل کاربر لاگین‌شده با ایمیل درج‌شده در دعوت‌نامه
        if ($user->email !== $invitation->email) {
            return response()->json([
                'status' => 'error',
                'message' => 'این دعوت‌نامه متعلق به حساب کاربری شما نیست.'
            ]);
        }

        $workspace = $invitation->workspace;

        // اتصال کاربر به جدول  workspace_users با نقش تعیین‌شده
        if (!$workspace->users()->where('user_id', $user->id)->exists()) {
            // پیدا کردن آیدی رول بر اساس نام ذخیره‌شده در دعوت‌نامه
            $role = Role::where('name', $invitation->role)->first();
            
            $workspace->users()->attach($user->id, [
                'role_id' => $role ? $role->id : null
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
    public function destroy( workspace $workspace,workspace_invitation $invitation)
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