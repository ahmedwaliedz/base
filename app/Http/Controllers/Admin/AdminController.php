<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use App\Traits\Response\ResponseTrait;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $admins = Admin::has('role')->search($request->filters)->paginate(30);
            return view('admin.admins.table', compact('admins'))->render();
        }
        $roles = Role::get();
        return view('admin.admins.index', get_defined_vars());
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        // Implementation will be added later
        return $this->respondWithSuccess(__('admin/main.admin_created'), [
            'route' => route('admin.admins.index')
        ]);
    }

    public function show($id)
    {
        return view('admin.admins.show', compact('id'));
    }

    public function edit($id)
    {
        return view('admin.admins.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        return $this->respondWithSuccess(__('admin/main.admin_updated'), [
            'route' => route('admin.admins.index')
        ]);
    }

    public function destroyAll(Request $request)
    {
        if (is_array($request->ids)) {
           $admins = Admin::has('role')->whereIn('id' , $request->ids)->get();
           $admins->each->delete();
           return $this->respondWithSuccess(__('admin/main.admins_deleted'));
        }
        return $this->respondWithFail(__('admin/main.admin_not_found'), [
            'route' => route('admin.admins.index')
        ]);
    }

}
