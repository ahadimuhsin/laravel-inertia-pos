<?php

namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::when(request()->q, function($roles)
        {
            $roles = $roles->where('name', 'like', '%'.request()->q.'%');
        })->with('permissions')->latest()->paginate(5);

        return inertia('Apps/Roles/Index', ['roles' => $roles]);
    }

    public function create()
    {
        //get all permission
        $permissions = Permission::all();

        return inertia ('Apps/Roles/Create', [
            'permissions' => $permissions
        ]);
    }

    public function store(Request $request)
    {
        /**
         * Validate Request
         */
        $this->validate($request, [
            'name' => 'required',
            'permissions' => 'required'
        ], [
            'name.required' => 'Nama role harus diisi',
            'permissions.required' => 'Permission harus diisi'
        ]);

        $role = Role::create([
            'name' => $request->name
        ]);

        //assign permissions to role
        $role->givePermissionTo($request->permissions);

        return redirect()->route('apps.roles.index');
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);

        $permissions = Permission::all();

        //render with inertia
        return inertia('Apps/Roles/Edit', [
            'role' => $role,
            'permissions' => $permissions
        ]);
    }

    public function update(Request $request, Role $role)
    {
        /**
         * Validate request
         */
        $this->validate($request, [
            'name' => 'required',
            'permissions' => 'required'
        ],[
            'name.required' => 'Nama role harus diisi',
            'permissions.required' => 'Permission harus diisi'
        ]);

        $role->update(['name' => $request->name]);

        //sync permissions
        $role->syncPermissions($request->permissions);

        return redirect()->route('apps.roles.index');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        //delete role
        $role->delete();
        return redirect()->route('apps.roles.index');
    }
}
