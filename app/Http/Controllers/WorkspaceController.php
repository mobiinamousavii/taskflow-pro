<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\workspace;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class WorkspaceController extends Controller
{

    use AuthorizesRequests;
    // رو لارول 11 جواب نمیده
    // public function __construct()
    // {
    //    $this->authorizeResource(workspace::class , 'workspace');
    // }

    // public static function middleware(): array
    // {
    //     return [
    //         'auth:sanctum',
    //     ];
    // }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // فقد ورک اسپیسایی که کاربر ساخته
        // $workspaces = Workspace::where('owner_id', Auth::id())
        //     ->withCount(['projects'])
        //     ->get();

        // چه ورک اسپیسایی که ساخته چه عضوشه باهم
        $workspaces = $request->user()->workspaces()->withCount('projects')->get();

        return response()->json([
            'status' => 'success',
            'data' => $workspaces
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'string',
            'max:255',
            'description' => 'nullable',
        ]);
        $workspace = workspace::create([
            'owner_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // تو سه جا میشه رول رو تعیین کرد
        // یکی موقع ایجاد ورک اسپیس که خودکار مدیر میشه
        //  یکی از طریق دعوت
        // یکیم بعدها داخل ورک اسپیس که میشه به اعضا نقش داد
        // ۳. اتصال کاربر ایجادکننده به عنوان Owner در جدول پیوت workspace_user
        $workspace->users()->attach(Auth::id(), [
            'role' => 'owner' // یا Enum / مقدار تعریف‌شده برای نقش
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'ورک اسپیس با موفقیت ایجاد شد',
            'workspace' => $workspace,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $workspace = Workspace::withCount('projects')->find($id);

        if (!$workspace) {
            return response()->json([
                'status' => 'error',
                'message' => 'ورک‌اسپیس مورد نظر یافت نشد.'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $workspace
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $workspace = Workspace::find($id);

        if (!$workspace) {
            return response()->json([
                'status' => 'error',
                'message' => 'ورک‌اسپیس مورد نظر یافت نشد.'
            ]);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $workspace->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'ورک‌اسپیس با موفقیت به‌روزرسانی شد.',
            'data' => $workspace
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $workspace = Workspace::find($id);

        if (!$workspace) {
            return response()->json([
                'status' => 'error',
                'message' => 'ورک‌اسپیس مورد نظر یافت نشد.'
            ]);
        }

        // حذف ورک‌اسپیس (رکوردهای مرتبط در workspace_users به صورت cascade پاک می‌شوند)
        $workspace->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'ورک‌اسپیس با موفقیت حذف شد.'
        ], 200);
    }


    public function members($id)
    {
        // لیست ممبر فقد برای کسایی که عضو ورک اسپیسن
        $workspace = Workspace::with('users:id,name,email')->find($id);

        if (!$workspace) {
            return response()->json([
                'status' => 'error',
                'message' => 'ورک‌اسپیس مورد نظر یافت نشد.'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $workspace->users
        ]);
    }

    public function addmember(Request $request, $id)
    {
        $workspace = Workspace::find($id);

        if (!$workspace) {
            return response()->json([
                'status' => 'error',
                'message' => 'ورک‌اسپیس مورد نظر یافت نشد.'
            ]);
        }

        $request->validate([
            'email' => 'required|email|exists:users,email',
            'role'  => 'required|in:admin,member,viewer'
        ]);

        $userToAdd = User::where('email', $request->email)->first();

        // بررسی اینکه کاربر قبلاً عضو ورک‌اسپیس شده یا نه
        if ($workspace->users()->where('user_id', $userToAdd->id)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'این کاربر قبلاً عضو ورک‌اسپیس شده است.'
            ]);
        }

        // اضافه کردن کاربر به جدول واسط workspace_users
        $workspace->users()->attach($userToAdd->id, [
            'role' => $request->role
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'کاربر با موفقیت به ورک‌اسپیس اضافه شد.'
        ]);
    }
}
