<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\project;
use App\Models\task;
use App\Models\workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    //     public function __construct()
    // {
    //     $this->authorizeResource(Attachment::class);
    // }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request, workspace $workspace, project $project, task $task)
    {
         $this->authorize('create', [$workspace,$project,$task]);
        $request->validate([
            'file' => 'required|file|max:10240|mimes:rar,zip,jpg,jpeg,png,pdf,doc,docx',
        ]);
        $file = $request->file('file');
        $path = $file->store('attachments');
        $attachments = $task->attachments()->create([
            'user_id' => Auth::id(),
            'original_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'file_type' => $file->getClientMimeType()
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'فایل با موفقیت اضافه شد',
            'data' => $attachments,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( workspace $workspace, project $project, task $task,Attachment $attachment)
    {
         $this->authorize('delete', [$attachment]);
        if (Storage::exists($attachment->file_path)) {
            Storage::delete($attachment->file_path);
        }

        $attachment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'فایل با موفقیت حذف شد',
        ]);
    }


    public function download(workspace $workspace, project $project, task $task, Attachment $attachment)
    {
        // همبن که فایل ها داخل پوشه استوریج ذخیره شده کاربر نمیتونه با url مستقیم ذخیرش کنه
        if (!Storage::exists($attachment->file_path)) {
            return response()->json([
                'status' => 'error',
                'message' => 'فایل یافت نشد.'
            ]);
        }

        return Storage::download($attachment->file_path, $attachment->original_name);
    }
}
