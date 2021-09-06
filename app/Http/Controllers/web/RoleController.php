<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Spatie\Permission\Models\Permission;
use App\User;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->orderBy('id', 'DESC')->paginate(3);
        $permissions = Permission::paginate(5);
        $allPermissions = Permission::all();
        $allRoles = Role::all();
        $users = User::all();
        return view('pages.role.index', ['allRoles' => $allRoles, 'roles' => $roles, 'permissions' => $permissions, 'allPermissions' => $allPermissions, 'users' => $users]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required | unique:roles'
        ], [
            'name.required' => 'Role name is required.',
            'name.unique' => 'Role name is already registered'
        ]);

        if ($request->has('name')) {
            $name = strtolower($request->input('name'));
        }

        $role = Role::create(['name' => $name]);

        if ($role) {
            Toastr::success('New ' . $role->name . ' role has created successfully.', 'success');
            return redirect()->back();
        } else {
            Toastr::error('W00ps! Something went wrong. Try again.', 'error');
            return redirect()->back();
        }
    }

    /**
     * check permissions to specific role by id
     */

    public function check_permissions($id)
    {
        $RoleHasPermissions = Role::with('permissions')->findOrFail($id);
        $permissionList = [];
        foreach ($RoleHasPermissions->permissions as $p) {
            $permissionList[] = $p->id;
        }
        $permissions = Permission::all();
        $checkResult = view('pages.role._check-role', ['permissionList' => $permissionList, 'permissions' => $permissions])->render();
        return (['checkResult' => $checkResult]);
    }

    /**
     * Store permissions according to role
     */

    public function privilege_store(Request $request)
    {
        $this->validate($request, [
            'role' => 'required',
            'permissions' => 'required'
        ], [
            'role.required' => 'Select role first.',
            'permissions.required' => 'Select a permission',
        ]);

        if ($request->has('role')) {
            $roleId = $request->input('role');
            $role = Role::findOrFail($roleId);
        }

        // Remove privious permissions that is not in new permission list
        $dbp = Role::with('permissions')->findOrFail($role->id);
        $dbpl = [];
        foreach ($dbp->permissions as $p) {
            $dbpl[] = $p->id;
        }
        $assignPermissionList = [];
        if ($request->has('permissions')) {
            foreach ($request->input('permissions') as $permission) {
                $assignPermissionList[] = $permission;
            }
        }
        $removedValues = array_diff($dbpl, $assignPermissionList);
        if ($removedValues != null) {
            foreach ($removedValues as $value) {
                $pId = Permission::findOrFail($value);
                $role->revokePermissionTo($pId);
            }
        }

        // Save new permission list
        if ($request->has('permissions')) {
            foreach ($request->input('permissions') as $permission) {
                $pId = Permission::findOrFail($permission);
                $save = $role->givePermissionTo($pId);
            }
        }

        if ($save) {
            Toastr::success('Save all permission to role successfully.', 'success');
            return redirect()->back();
        } else {
            Toastr::error('W00ps! Something went wrong. Try again.', 'error');
            return redirect()->back();
        }
    }

    /**
     * Check user direct permissions
     */

    public function check_direct_permission($id)
    {
        $user = User::findOrFail($id);
        $userRoles = $user->getRoleNames();
        $userDirectPermissions = $user->permissions;
        $permissions = Permission::all();

        $checkResult = view('pages.role._check-direct-permissions', ['permissions' => $permissions, 'userRoles' => $userRoles, 'userDirectPermissions' => $userDirectPermissions])->render();
        return (['checkDirectPermissions' => $checkResult]);
    }
}
