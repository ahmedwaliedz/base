<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\Store;
use App\Http\Requests\Admin\Role\Update;
use App\Models\Admin;
use App\Models\Permission;
use App\Models\Role;
use App\Services\Admin\Roles\RoleService;
use App\Traits\Response\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class RoleController extends Controller
{
    use ResponseTrait;

    public function __construct(protected RoleService $roleService) {}

    /**
     * Display a listing of the roles
     *
     * @return View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            //            $roles = $this->roleService->getAllRoles();
            $roles = Role::search($request->filters)->paginate($request->filters['per_page'] ?? 9);

            return view('admin.roles.parts.cards', compact('roles'))->render();
        }

        return view('admin.roles.index');
    }

    /**
     * Aggregated statistics for the roles listing page.
     * Mirrors UserController::statistics: returns a Blade partial of stat cards
     * for injection into the shared <x-table.statistics> container.
     */
    public function statistics(Request $request): Response
    {
        $now = Carbon::now();

        $totalRoles = Role::query()->count();
        $assignedAdmins = Admin::whereNotNull('role_id')->count();
        $unassignedRoles = Role::query()->doesntHave('admins')->count();
        $avgPermissions = (int) round(
            Role::query()->withCount('permissions')->get()->avg('permissions_count') ?? 0
        );
        $mostPopulated = Role::query()
            ->withCount('admins')
            ->orderByDesc('admins_count')
            ->first();
        $createdThisMonth = Role::query()
            ->where('created_at', '>=', $now->copy()->startOfMonth())
            ->count();

        return response()->view('admin.roles.parts.statistics', compact(
            'totalRoles',
            'assignedAdmins',
            'unassignedRoles',
            'avgPermissions',
            'mostPopulated',
            'createdThisMonth'
        ));
    }

    /**
     * Show the form for creating a new role
     */
    public function create(): View
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created role
     */
    public function store(Store $request): JsonResponse
    {
        $this->roleService->createRole($this->prepareRoleData($request));

        return $this->respondWithSuccess(__('admin/main.role_created'), [
            'route' => route('admin.roles.index'),
        ]);
    }

    /**
     * Display the specified role
     */
    public function show(int $id): View
    {
        $role = $this->roleService->getRoleById($id);
        $viewData = $this->roleService->getFormViewData($role);

        $totalPermissions = Permission::count();
        $granted = count($viewData['permissions'] ?? []);
        $coverage = $totalPermissions > 0
            ? (int) round(($granted / $totalPermissions) * 100)
            : 0;

        return view('admin.roles.show', [
            'role' => $role,
            'permissions' => $viewData['permissions'],
            'permissionsByGroup' => $viewData['permissionsByGroup'],
            'coverage' => $coverage,
        ]);
    }

    /**
     * Show the form for editing the specified role
     */
    public function edit(int $id): View
    {
        $role = $this->roleService->getRoleById($id);
        $viewData = $this->roleService->getFormViewData($role);

        return view('admin.roles.edit', $viewData);
    }

    /**
     * Update the specified role
     */
    public function update(Update $request, int $id): JsonResponse
    {
        $this->roleService->updateRole($id, $this->prepareRoleData($request));

        return $this->respondWithSuccess(__('admin/main.role_updated'), [
            'route' => route('admin.roles.index'),
        ]);
    }

    /**
     * Remove the specified role
     */
    public function destroy(int $id): JsonResponse
    {
        $this->roleService->deleteRole($id);

        return $this->respondWithSuccess(__('admin/main.role_deleted'));
    }

    /**
     * Get the role form partial
     */
    public function getForm(?int $id = null): View
    {
        $role = $id ? $this->roleService->getRoleById($id) : null;
        $viewData = $this->roleService->getFormViewData($role);

        return view('admin.roles.parts._form', $viewData);
    }

    /**
     * Prepare role data from request
     */
    private function prepareRoleData(Request $request): array
    {
        return [
            'role' => $request->only(['ar', 'en']),
            'permissions' => $request->input('permissions', []),
        ];
    }
}
