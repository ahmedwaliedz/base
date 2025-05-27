<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\Store;
use App\Http\Requests\Admin\Role\Update;
use App\Services\Admin\Roles\RoleService;
use App\Traits\Response\ResponseTrait;

class RoleController extends Controller
{
    use ResponseTrait;

    public function __construct(
        protected RoleService $roleService
    ) {

    }

    public function index()
    {
        $roles = $this->roleService->getAllRoles();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Store $request)
    {
        $data = [
            'role' => $request->only(['ar', 'en']),
            'permissions' => $request->input('permissions', [])
        ];

        $this->roleService->createRole($data);

        return $this->respondWithSuccess(__('admin/main.role_created'), [
            'route' => route('admin.roles.index'),
        ]);
    }

    public function show($id)
    {
        $role = $this->roleService->getRoleById($id);
        $permissions = $this->roleService->getRolePermissions($role);
        $permissionsByGroup = $this->roleService->getAdminRoutesGrouped();

        return view('admin.roles.show', [
            'role' => $role,
            'permissions' => $permissions,
            'permissionsByGroup' => $permissionsByGroup,
        ]);
    }

    public function edit($id)
    {
        $role = $this->roleService->getRoleById($id);
        $viewData = $this->roleService->getFormViewData($role);

        return view('admin.roles.edit', $viewData);
    }

    public function update(Update $request, $id)
    {
        $data = [
            'role' => $request->only(['ar', 'en']),
            'permissions' => $request->input('permissions', [])
        ];

        $this->roleService->updateRole($id, $data);

        return $this->respondWithSuccess(__('admin/main.role_updated'), [
            'route' => route('admin.roles.index'),
        ]);
    }

    public function destroy($id)
    {
        $this->roleService->deleteRole($id);
        return $this->respondWithSuccess(__('admin/main.role_deleted'));
    }

    public function getForm($id = null)
    {
        if ($id) {
            $role = $this->roleService->getRoleById($id);
            $viewData = $this->roleService->getFormViewData($role);
        } else {
            $viewData = $this->roleService->getFormViewData();
        }

        return view('admin.roles.parts._form', $viewData);
    }
}
