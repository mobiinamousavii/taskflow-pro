<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\workspace;
use Illuminate\Http\Request;

// این کنترلر برای ساختن ورک فلوعه شخصیه هر پروژست
// این واسه ساختن خود ستون هاست
// اضافه کردن ستونها حذف ستونها  عوض کردن اسمش
class ProjectStatusController extends Controller
{
    // لیست وضعیت‌های یک پروژه
    public function index(workspace $workspace, Project $project)
    {
        $this->authorize('view', $project);
        $statuses = $project->status()->orderBy('position')->get();

        return response()->json(['data' => $statuses]);
    }

    // اضافه کردن استاتوس جدید
    public function store(Request $request, workspace $workspace, Project $project)
    {
        $this->authorize('create', $project);
        $validated = $request->validate([
            'name'         => 'required|string|max:100',
            'is_completed' => 'nullable|boolean',
        ]);

        // ترتیب: استاتوس جدید بعد از اخرین استاتوس اضافه مییشه
        $lastPosition = $project->status()->max('position') ?? 0;

        $status = $project->status()->create([
            'name'         => $validated['name'],
            'position'     => $lastPosition + 1,
            'is_completed' => $validated['is_completed'] ?? false,
        ]);

        return response()->json([
            'message' => 'وضعیت جدید اضافه شد',
            'data'    => $status,
        ]);
    }

    // تغییر اسم، ترتیب یا علامت تموم شده
    public function update(Request $request, workspace $workspace, Project $project, $status)
    {
        $this->authorize('update', $project);
        $status = $project->status()->findOrFail($status);

        // sometimesفقد اگه این فیلد فرستاده شد چکش کن 
        $validated = $request->validate([
            'name'         => 'sometimes|required|string|max:100',
            'position'     => 'sometimes|integer|min:1',
            'is_completed' => 'sometimes|boolean',
        ]);

        $status->update($validated);

        return response()->json([
            'message' => 'وضعیت به‌روزرسانی شد',
            'data'    => $status,
        ]);
    }

    // موقعی که تسک داخل ستون استاتوساست نباید استاتوس حذف شه
    public function destroy(workspace $workspace, Project $project, $status)
    {
        $this->authorize('delete', $project);
        $status = $project->status()->findOrFail($status);

        if (Task::where('status_id', $status->id)->exists()) {
            return response()->json([
                'message' => 'این وضعیت تسک دارد. اول تسک‌ها را به وضعیت دیگری ببرید.',
            ]);
        }

        $status->delete();

        return response()->json(['message' => 'وضعیت حذف شد']);
    }
}
