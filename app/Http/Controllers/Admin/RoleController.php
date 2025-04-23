<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{

    public function index()
    {
        $roles = Role::get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        // Code to store a newly created role in storage
    }

    public function show($id)
    {
        // Code to display a specific role
    }

    public function edit($id)
    {
        // Code to show the form for editing a specific role
    }

    public function update(Request $request, $id)
    {
        // Code to update a specific role in storage
    }

    public function destroy($id)
    {
        // Code to remove a specific role from storage
    }
}
