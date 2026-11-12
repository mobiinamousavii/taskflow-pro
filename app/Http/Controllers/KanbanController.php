<?php

namespace App\Http\Controllers;

use App\Events\TaskActivityEvent;
use App\Models\Workspace;
use App\Models\Project;
use App\Models\project_status;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class KanbanController extends Controller
{
    /**
     * دریافت اطلاعات کانبان
     * ستون‌های پروژه همراه با تسک‌های مرتب‌شده بر اساس position
     */
    public function index(Workspace $workspace, Project $project)
    {
        $this->authorize('view', $project);
        $statuses = $project->status()->with([
            'task' => function ($query) use ($project) {
                $query->where('project_id', $project->id)
                    ->orderBy('position', 'asc');
            },
            'task.assignee',
            'task.creator',
            'task.status'
        ])->get();

        return response()->json([
            'status' => 'success',
            'data' => $statuses,
        ]);
    }

    /**
     * به‌روزرسانی وضعیت و موقعیت تسک هنگام جابه‌جایی در کانبان
     */
    public function updatePosition(Request $request, Workspace $workspace, Project $project, Task $task)
    {
        $this->authorize('update', $task);
        $request->validate([
            'status_id' => [
                'required',
                Rule::exists('project_statuses', 'id')->where('project_id', $project->id),
            ],
            'position' => 'required|integer|min:0',
        ]);

        if ($task->is_overdue) {
            return response()->json([
                'status' => 'error',
                'message' => 'مهلت این تسک تمام شده. اول مهلت را تمدید کنید.',
            ], 422);
        }

        $newstatus = project_status::find($request->status_id);
        $olddata = $task->toArray();

        $oldPosition = $task->position;
        $oldStatusId = $task->status_id;
        $targetStatusId = $request->status_id;

        // تعداد کل تسک‌های ستون مقصد (بدون محاسبه خود این تسک)
        $countInTarget = Task::where('status_id', $targetStatusId)
            ->where('id', '!=', $task->id)
            ->count();

        // چون پوزیشن از صفر شروع میشه، حداکثر پوزیشن مجاز برابر با تعداد تسک‌هاست (اگه ستون خالی باشه میشه 0)
        $newPosition = min($request->position, $countInTarget);

        DB::transaction(function () use ($task, $oldStatusId, $targetStatusId, $oldPosition, $newPosition, $newstatus) {

            // حالت اول: جابه‌جایی درونِ یک ستون
            if ($oldStatusId == $targetStatusId) {
                if ($oldPosition < $newPosition) {
                    // حرکت به سمت پایین
                    Task::where('status_id', $targetStatusId)
                        ->where('id', '!=', $task->id)
                        ->where('position', '>', $oldPosition)
                        ->where('position', '<=', $newPosition)
                        ->decrement('position');
                } elseif ($oldPosition > $newPosition) {
                    // حرکت به سمت بالا
                    Task::where('status_id', $targetStatusId)
                        ->where('id', '!=', $task->id)
                        ->where('position', '>=', $newPosition)
                        ->where('position', '<', $oldPosition)
                        ->increment('position');
                }
            }
            // حالت دوم: انتقال به ستونِ دیگر
            else {
                // پر کردن جای خالی در ستونِ مبدأ
                Task::where('status_id', $oldStatusId)
                    ->where('id', '!=', $task->id)
                    ->where('position', '>', $oldPosition)
                    ->decrement('position');

                // باز کردن جا در ستونِ مقصد، از پوزیشن جدید به بعد
                Task::where('status_id', $targetStatusId)
                    ->where('position', '>=', $newPosition)
                    ->increment('position');
            }

            // آپدیت نهایی تسک جاری
            $task->update([
                'status_id' => $targetStatusId,
                'position' => $newPosition,
                'completed_at' => $newstatus->is_completed ? ($task->completed_at ?? now()) : null,
                // اگه ستون تموم شده بود و قبلا زمان تکمیل داشت همونو نگه دار
            ]);
        });

        $newdata = $task->fresh()->toArray();

        TaskActivityEvent::dispatch($task, 'kanban_moved', $olddata, $newdata);

        return response()->json([
            'status' => 'success',
            'message' => 'موقعیت و وضعیت تسک با موفقیت به‌روزرسانی شد.',
            'data' => $task->load('status')
        ]);
    }
}
