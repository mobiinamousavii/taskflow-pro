<?php

namespace App\Http\Controllers;

use App\Events\TaskActivityEvent;
use App\Http\Requests\TaskRequest;
use App\Models\Project;
use App\Models\project as ModelsProject;
use App\Models\project_status;
use App\Models\Task;
use App\Models\workspace;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Workspace $workspace, Project $project, Request $request)
    {
        $query = $project->tasks()->with(['assignee', 'creator', 'status']);

        // ۱. جستجو بر اساس عنوان یا توضیحات تسک
        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // ۲. فیلتر بر اساس وضعیت (Status)
        if ($request->has('status_id') && !empty($request->input('status_id'))) {
            $query->where('status_id', $request->input('status_id'));
        }

        // ۳. فیلتر بر اساس اولویت (Priority)
        if ($request->has('priority') && !empty($request->input('priority'))) {
            $query->where('priority', $request->input('priority'));
        }

        // ۴. فیلتر بر اساس کاربر مسئول (Assignee)
        if ($request->has('assignee_id') && !empty($request->input('assignee_id'))) {
            $query->where('assignee_id', $request->input('assignee_id'));
        }

        // ۵. فقط تسک‌های عقب‌افتاده (مهلت گذشته و تموم نشده)
        // همون ستون is_completed تو  پروجکت استاتوس
        if ($request->boolean('overdue')) {
            $query->whereNotNull('deadline')
                ->where('deadline', '<', now())
                ->whereNull('completed_at');
        }

        $tasks = $query->latest()->paginate(8);

        return response()->json([
            'status' => 'success',
            'data' => $tasks
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request, workspace $workspace, Project $project)
    {
        $data = $request->validated();
        $data['creator_id'] = Auth::id();
        $data['project_id'] = $project->id;

        $task = Task::create($data);

        TaskActivityEvent::dispatch($task, 'created', [], $task->toArray());

        // اگه تسک جدید شامل مسئول (assignee) بود، به اون اعلان بده
        if ($task->assignee) {
            $task->assignee->notify(new TaskAssignedNotification($task));
        }

        return response()->json([
            'message' => 'تسک با موفقیت ایجاد شد',
            'data' => $task->load(['assignee', 'creator', 'status']),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(workspace $workspace, Project $project, Task $task)
    {
        $task->load(['assignee', 'creator', 'status']);

        return response()->json([
            'status' => 'success',
            'data' => $task
        ]);
    }

    /**
     * 
     */
    public function update(TaskRequest $request, Workspace $workspace, Project $project, Task $task)
    {
        // ثبت اطلاعات جدید و قبلی برای ثبت لاگ
        $oldAssigneeId = $task->assignee_id; // ذخیره شناسه مسئول قبلی برای مقایسه
        $olddata = $task->toArray();

        $task->update($request->validated());

        $newdata = $task->fresh()->toArray();
        TaskActivityEvent::dispatch($task, 'updated', $olddata, $newdata);

        // ارسال اعلان در صورت تغییر یا تعیین مسئول جدید
        if ($task->assignee && $task->assignee_id != $oldAssigneeId) {
            $task->assignee->notify(new TaskAssignedNotification($task));
        }

        return response()->json([
            'message' => 'تسک با موفقیت به روزرسانی شد',
            'data' => $task->fresh(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(workspace $workspace, Project $project, Task $task)
    {

    // اینجا نمیخواد اول تسکو پیدا کنم؟
        $task->delete();

        return response()->json([
            'message' => 'تسک با موفقیت حذف شد',
            'data' => $task,
        ]);
    }

    /**
     * Update task status (برای بورد کانبان و تغییر وضعیت سریع)
     */
    // مثلا کارتو از to do به doneعوض میکنه
    public function updateStatus(Request $request, workspace $workspace, Project $project, Task $task)
    {
        // استاتوس باید مال همین پروژه باشه
        $request->validate([
            'status_id' => [
                'required',
                Rule::exists('project_statuses', 'id')->where('project_id', $project->id),
            ],
        ]);

        $newStatus = project_status::find($request->status_id);

        // زمان تکمیلو هروقت تسکی به ستون تمام شده 
        // (is_completed=1 & done) رفت تو کامپلیتد ات زمان الانو میزاره
        // وقتی تو یه ساون دیگم رفت خالی میشه چون تسک تموم شده نباید عقل افتاده حساب بشه
        $task->update([
            'status_id'    => $request->status_id,
            'completed_at' => $newStatus->is_completed ? now() : null,
        ]);

        return response()->json([
            'message' => 'وضعیت تسک با موفقیت تغییر کرد',
            'data' => $task->load('status')
        ]);
    }
}
