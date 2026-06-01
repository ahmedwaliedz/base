<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ServiceException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\StoreRequest;
use App\Http\Requests\Admin\Role\UpdateRequest;
use App\Models\Permission;
use App\Services\Admin\Roles\RoleService;
use App\Traits\Response\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
            $roles = $this->roleService->getAllRoles($request->filters ?? []);

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
        return response()->view('admin.roles.parts.statistics', $this->roleService->getStatistics());
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
            'permissionGroupLabels' => $viewData['permissionGroupLabels'],
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
        $validated = $request->validated();

        return [
            'role' => [
                'ar' => $validated['ar'] ?? [],
                'en' => $validated['en'] ?? [],
            ],
            'permissions' => $validated['permissions'] ?? [],
        ];
    }
}
