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
// public function __construct()
// {
//    $this->authorizeResource(workspace::class , 'workspace');
// }

public static function middleware(): array
{
    return[
        'auth:sanctum',
    ];
}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workspaces = Workspace::where('owner_id', Auth::id())
            ->withCount(['projects'])
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $workspaces,
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
            'status'=>'success',
            'message' => 'ورک اسپیس با موفقیت انجام شد',
            'workspace' => $workspace,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(workspace $workspace)
    {
        $workspace->load(['projects']);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(workspace $workspace)
    {
        $workspace->delete();
        return redirect()->route('workspaces.index')->with('success', ' فضای کاری با موفقیت حذف شد');
    }

    public function addmember(Request $request, workspace $workspace)
    {
         $this->authorize('add_member' , 'workspace');
        $request->validate([
            'email' => ['required', 'exists:users,email'],
            'role' => ['required'],
        ], [
            'email.exists' => 'کاربری با این ادرس ایمیل در سیستم یافت نشد',
        ]);
        $usertoadd = User::where('email', $request->email)->first();
        if ($usertoadd && $workspace->users->contains('id', $usertoadd->id)) {
            return back()->withErrors(['email' => 'این کاربر از قبل عضو این فضای کاری است']);
        }
        $workspace->users()->attach($usertoadd->id, [
            'role' => $request->role
        ]);
    }
}
