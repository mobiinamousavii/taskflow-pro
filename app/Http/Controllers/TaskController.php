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


        //  کنترلر فقط با یه  خط برای هرکدوم، کار فیلترو  به اسکوپ‌های مدل میسپاره:

        // جستجو بر اساس توضیحات یا عنوان
        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        if ($request->filled('status_id')) {
            $query->status($request->input('status_id'));
        }

        if ($request->filled('priority')) {
            $query->priority($request->input('priority'));
        }

        if ($request->filled('assignee_id')) {
            $query->assignedTo($request->input('assignee_id'));
        }

        if ($request->filled('creator_id')) {
            $query->creator($request->input('creator_id'));
        }

        if ($request->filled('due_date')) {
            $query->dueDate($request->input('due_date'));
        }

        if ($request->boolean('overdue')) {
            $query->overdue();
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

        // اگه تسک جدید شامل (assignee) بود، به اون اعلان بده
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
        $oldAssigneeId = $task->assignee_id;
        // ذخیره ایدی مسئول قبلی برای مقایسه
        $olddata = $task->toArray();

        if ($task->is_overdue) {

            // اگر اوردو هست اما تاریخ نفرستاده
            if (!$request->filled('deadline')) {
                return response()->json(['message' => 'مهلت این تسک تمام شده. مهلت جدید را بفرستید.']);
            }
            // اگر تاریخ جدید فرستاده اما هنوز تو گذشته‌ست
            // gt برای مقایسه دوتا تاریخ استفاده میشه 
            if (now()->gt($request->deadline)) {
                return response()->json(['message' => 'مهلت جدید باید در آینده باشد.']);
            }
        }


        $validatedData = $request->validated();

        //  بررسی وضعیت status_id برای پر کردن خودکار completed_at
        if (isset($validatedData['status_id'])) {
            $status = project_status::find($validatedData['status_id']);

            if ($status && $status->is_completed) {
                $validatedData['completed_at'] = now();
            } else {
                // اگه از حالت دان خارج شد، تاریخ تکمیل پاک میشه
                $validatedData['completed_at'] = null;
            }
        }
        $task->update($validatedData);

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
        // وقتی تو یه ستون دیگم رفت خالی میشه چون تسک تموم شده نباید عقل افتاده حساب بشه
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
