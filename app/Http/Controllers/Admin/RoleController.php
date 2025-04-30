<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\Store;
use App\Http\Requests\Admin\Role\Update;
use App\Models\Permission;
use App\Models\Role;
use App\Traits\Response\ResponseTrait;
use App\Traits\Role\RoleTrait;
use DB ;
class RoleController extends Controller
{
    use RoleTrait  , ResponseTrait;

    public function index()
    {
        $roles = Role::with('admins')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create' , [
            'html' => view('admin.roles.parts.show-route', ['permissionsByGroup' => $this->getAdminRoutesGrouped()])->render(),
        ]);
    }

    public function store(Store $request)
    {
        DB::transaction(function() use ($request) {
            $role = Role::create($request->only(['ar' , 'en']));
            $permissionIds = collect($request->input('permissions'))
                ->map(function (string $permissionName) {
                    return Permission::firstOrCreate(
                        ['permission' => $permissionName]
                    )->id;
                })->toArray();
            $role->permissions()->sync($permissionIds);
        });
        return $this->respondWithSuccess(__('admin/main.role_created') , [
            'route' => route('admin.roles.index'),
        ]);
    }

    public function show($id)
    {
        $role = Role::findOrFail($id);
        $permissions = $role->permissions()->pluck('permission')->toArray();
        $permissionsByGroup = $this->getAdminRoutesGrouped();
        return view('admin.roles.show' , [
            'role' => $role,
            'html' => view('admin.roles.parts.show-route', get_defined_vars())->render(),
        ]);
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = $role->permissions()->pluck('permission')->toArray();
        $permissionsByGroup = $this->getAdminRoutesGrouped();
        return view('admin.roles.edit' , [
            'role' => $role,
            'html' => view('admin.roles.parts.show-route', get_defined_vars())->render(),
        ]);
    }

    public function update(Update $request, $id)
    {
        $role = Role::findOrFail($id);
        DB::transaction(function() use ($request , $role) {
            $role->update($request->only(['ar' , 'en']));
            $permissionIds = collect($request->input('permissions'))
                ->map(function (string $permissionName) {
                    return Permission::firstOrCreate(
                        ['permission' => $permissionName]
                    )->id;
                })->toArray();
            $role->permissions()->sync($permissionIds);
        });
        return $this->respondWithSuccess(__('admin/main.role_created') , [
            'route' => route('admin.roles.index'),
        ]);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return $this->respondWithSuccess(__('admin/main.role_deleted'));
    }
}
