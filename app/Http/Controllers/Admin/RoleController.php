<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ServiceException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\StoreRequest;
use App\Http\Requests\Admin\Role\UpdateRequest;
use App\Models\Admin;
use App\Models\Permission;
use App\Models\Role;
use App\Services\Admin\Roles\RoleService;
use App\Traits\Response\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            DB::table('roles')
                ->leftJoin(DB::raw('(SELECT role_id, COUNT(*) as permission_count FROM permission_role GROUP BY role_id) as pr'), 'roles.id', '=', 'pr.role_id')
                ->selectRaw('AVG(COALESCE(pr.permission_count, 0)) as avg_permissions')
                ->first()?->avg_permissions ?? 0
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
    public function store(StoreRequest $request): JsonResponse
    {
        try {
            $this->roleService->createRole($this->prepareRoleData($request));
            return $this->respondWithSuccess(__('admin/main.role_created'), [
                'route' => route('admin.roles.index'),
            ]);
        } catch (ServiceException $e) {
            Log::warning('ServiceException in store', [
                'controller' => static::class,
                'model' => 'Role',
                'message' => $e->getMessage(),
                'status_code' => $e->getStatusCode(),
                'context' => $e->getContext(),
            ]);
            return $this->respondWithFail($e->getMessage(), [], $e->getStatusCode());
        } catch (\Throwable $e) {
            Log::error('Unexpected error in store', [
                'controller' => static::class,
                'model' => 'Role',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->respondInternalError();
        }
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
    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        try {
            $this->roleService->updateRole($id, $this->prepareRoleData($request));
            return $this->respondWithSuccess(__('admin/main.role_updated'), [
                'route' => route('admin.roles.index'),
            ]);
        } catch (ServiceException $e) {
            Log::warning('ServiceException in update', [
                'controller' => static::class,
                'model' => 'Role',
                'id' => $id,
                'message' => $e->getMessage(),
                'status_code' => $e->getStatusCode(),
                'context' => $e->getContext(),
            ]);
            return $this->respondWithFail($e->getMessage(), [], $e->getStatusCode());
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('ModelNotFoundException in update', [
                'controller' => static::class,
                'model' => 'Role',
                'id' => $id,
                'message' => $e->getMessage(),
            ]);
            return $this->respondWithFail(__('admin/main.role_not_found'), [], 404);
        } catch (\Throwable $e) {
            Log::error('Unexpected error in update', [
                'controller' => static::class,
                'model' => 'Role',
                'id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->respondInternalError();
        }
    }

    /**
     * Remove the specified role
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->roleService->deleteRole($id);
            return $this->respondWithSuccess(__('admin/main.role_deleted'));
        } catch (ServiceException $e) {
            Log::warning('ServiceException in destroy', [
                'controller' => static::class,
                'model' => 'Role',
                'id' => $id,
                'message' => $e->getMessage(),
                'status_code' => $e->getStatusCode(),
                'context' => $e->getContext(),
            ]);
            return $this->respondWithFail($e->getMessage(), [], $e->getStatusCode());
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('ModelNotFoundException in destroy', [
                'controller' => static::class,
                'model' => 'Role',
                'id' => $id,
                'message' => $e->getMessage(),
            ]);
            return $this->respondWithFail(__('admin/main.role_not_found'), [], 404);
        } catch (\Throwable $e) {
            Log::error('Unexpected error in destroy', [
                'controller' => static::class,
                'model' => 'Role',
                'id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->respondInternalError();
        }
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
