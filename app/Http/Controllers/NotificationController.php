<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // ۱. دریافت لیست اعلان‌ها (هم خوانده‌شده‌ها و هم خوانده‌نشده‌ها)
    public function index(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'status' => 'success',
            'unread_count' => $user->unreadNotifications->count(), // تعداد اعلان‌های خوانده نشده
            'notifications' => $user->notifications()->paginate(10), // لیست تمام اعلان‌ها با صفحه‌بندی
        ]);
    }

    // ۲. فقط دریافت اعلان‌های خوانده‌نشده
    public function unread(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'status' => 'success',
            'notifications' => $user->unreadNotifications,
        ]);
    }

    // ۳. علامت‌گذاری یک اعلان خاص به عنوان خوانده‌شده (Mark as Read)
    public function markAsRead(Request $request, $id)
    {
        $user = $request->user();
        $notification = $user->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            return response()->json([
                'status' => 'success',
                'message' => 'اعلان به عنوان خوانده‌شده علامت‌گذاری شد',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'اعلان یافت نشد',
        ]);
    }

    // ۴. علامت‌گذاری تمام اعلان‌ها به عنوان خوانده‌شده (Mark All as Read)
    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return response()->json([
            'status' => 'success',
            'message' => 'تمام اعلان‌ها به عنوان خوانده‌شده علامت‌گذاری شدند',
        ]);
    }
}
