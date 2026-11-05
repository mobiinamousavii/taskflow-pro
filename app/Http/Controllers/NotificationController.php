<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //دریافت لیست اعلان‌ها (هم خونده شده و هم خونده نشده)
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'اول وارد سایت شوید']);
        }

        return response()->json([
            'status' => 'success',
            'unread_count' => $user->unreadNotifications->count(),
            'notifications' => $user->notifications()->paginate(10),
        ]);
    }

    // فقط دریافت اعلان‌های خونده نشده
    public function unread(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'اول وارد سایت شوید']);
        }

        return response()->json([
            'status' => 'success',
            'notifications' => $user->unreadNotifications,
        ]);
    }

    // علامت‌گذاری یه اعلان خاص به عنوان خونده شده
    public function markAsRead(Request $request, $id)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'اول وارد سایت شوید']);
        }

        $notification = $user->notifications()->where('id', $id)->first();

        if (!$notification) {
            return response()->json([
                'status' => 'error',
                'message' => 'اعلان مورد نظر یافت نشد',
            ]);
        }

        $notification->markAsRead();

        return response()->json([
            'status' => 'success',
            'message' => 'اعلان به عنوان خوانده‌شده علامت‌گذاری شد',
        ]);
    }

    //علامت‌گذاری تمام اعلان‌ها به عنوان حونده شده 
    public function markAllAsRead(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'اول وارد سایت شوید']);
        }

        $user->unreadNotifications->markAsRead();

        return response()->json([
            'status' => 'success',
            'message' => 'تمام اعلان‌ها به عنوان خوانده‌شده علامت‌گذاری شدند',
        ]);
    }
}