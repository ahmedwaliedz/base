<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\Store;
use App\Http\Requests\Admin\Role\Update;
use App\Models\Role;
use App\Services\Admin\Roles\RoleService;
use App\Traits\Response\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    use ResponseTrait;

    /**
     * @param RoleService $roleService
     */
    public function __construct(protected RoleService $roleService)
    {
    }

    /**
     * Display a listing of the roles
     *
     * @return View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
//            $roles = $this->roleService->getAllRoles();
            $roles = Role::search($request->filters)->paginate(9);
            return view('admin.roles.parts.cards', compact('roles'))->render();
        }
        return view('admin.roles.index');
    }

    /**
     * Show the form for creating a new role
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created role
     *
     * @param Store $request
     * @return JsonResponse
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
     *
     * @param int $id
     * @return View
     */
    public function show(int $id): View
    {
        $role = $this->roleService->getRoleById($id);
        $viewData = $this->roleService->getFormViewData($role);

        return view('admin.roles.show', [
            'role'                => $role,
            'permissions'         => $viewData['permissions'],
            'permissionsByGroup'  => $viewData['permissionsByGroup'],
        ]);
    }

    /**
     * Show the form for editing the specified role
     *
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $role = $this->roleService->getRoleById($id);
        $viewData = $this->roleService->getFormViewData($role);

        return view('admin.roles.edit', $viewData);
    }

    /**
     * Update the specified role
     *
     * @param Update $request
     * @param int $id
     * @return JsonResponse
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
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $this->roleService->deleteRole($id);

        return $this->respondWithSuccess(__('admin/main.role_deleted'));
    }

    /**
     * Get the role form partial
     *
     * @param int|null $id
     * @return View
     */
    public function getForm(?int $id = null): View
    {
        $role = $id ? $this->roleService->getRoleById($id) : null;
        $viewData = $this->roleService->getFormViewData($role);

        return view('admin.roles.parts._form', $viewData);
    }

    /**
     * Prepare role data from request
     *
     * @param Request $request
     * @return array
     */
    private function prepareRoleData(Request $request): array
    {
        return [
            'role' => $request->only(['ar', 'en']),
            'permissions' => $request->input('permissions', [])
        ];
    }
}
