<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Workspace;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckWorkspacePermission
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            abort(401, 'ابتدا باید وارد حساب کاربری خود شوید.');
        }

        $workspace = $request->route('workspace');

        if (is_numeric($workspace)) {
            $workspace = Workspace::find($workspace);
        }

        if (!$workspace) {
            abort(404, 'ورک‌اسپیس مورد نظر یافت نشد.');
        }

        $routeName = $request->route() ? $request->route()->getName() : null;
        $permissionName = $this->convertToPermission($routeName);

        // بررسی نقش کاربر و وضعیت پرمیشن در دیتابیس 
        $workspaceUser = $user->workspaces()->where('workspaces.id', $workspace->id)->first();
        $roleId = $workspaceUser?->pivot?->role_id;
        $role = Role::find($roleId);

        $hasPermissionInDB = DB::table('role_permissions')
            ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
            ->where('role_permissions.role_id', $roleId)
            ->where('permissions.slug', $permissionName)
            ->exists();

        // dd([
        //     'route_name' => $routeName,
        //     'permission_we_are_looking_for' => $permissionName,
        //     'user_id' => $user->id,
        //     'workspace_id' => $workspace->id,
        //     'workspace_user_pivot' => $workspaceUser?->pivot,
        //     'role_found' => $role?->name,
        //     'role_id' => $roleId,
        //     'exists_in_database' => $hasPermissionInDB
        // ]);

        if ($permissionName && !$user->hasWorkspacePermission($workspace, $permissionName)) {
            abort(403, 'شما سطح دسترسی لازم برای این عملیات را در این ورک‌اسپیس ندارید.');
        }

        return $next($request);
    }

    private function convertToPermission($routeName)
    {
        if (!$routeName) {
            return null;
        }

        // حذف پیشوندهای احتمالی
        foreach (['workspace.', 'api.'] as $prefix) {
            if (str_starts_with($routeName, $prefix)) {
                $routeName = substr($routeName, strlen($prefix));
            }
        }
        // چون این دوتا متدای استاندارد روتارو ندارن مثل ایندکس و استور
        $customMap = [
            'projects.kanban'      => 'view_task',
            'tasks.updateposition' => 'edit_task',
        ]; 
        if (isset($customMap[$routeName])) {
            return $customMap[$routeName];
        }

        $segments = explode('.', $routeName);
        if (count($segments) < 2) {
            return $routeName;
        }

        $action = end($segments); // مثل store, index, update
        $resource = $segments[count($segments) - 2]; // مثل projects یا tasks

        $actionMap = [
            'index'   => 'view',
            'show'    => 'view',
            'create'  => 'create',
            'store'   => 'create',
            'edit'    => 'edit',
            'update'  => 'edit',
            'destroy' => 'delete',
        ];

        if (isset($actionMap[$action]) && !empty($resource)) {
            $mappedAction = $actionMap[$action];

            // تولید هر دو حالت مفرد و جمع برای تطابق کامل با دیتابیس
            $singularResource = Str::singular($resource);
            $pluralResource = Str::plural($resource);

            return $mappedAction . '_' . $pluralResource; // مثلاً create_projects
        }

        return $routeName;
    }
}
