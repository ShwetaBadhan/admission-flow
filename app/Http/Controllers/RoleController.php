<?php

namespace App\Http\Controllers;

use App\Models\Role; // Your custom Role model
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('created_at', 'desc')->paginate(10);
        // Return the view path matching your folder structure
        return view('pages.user-management.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('pages.user-management.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'status' => 'required|boolean',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
            'status' => $request->status,
        ]);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('pages.user-management.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'status' => 'required|boolean',
        ]);

        $role->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        // Prevent deleting critical roles
        if ($role->name === 'admin' || $role->name === 'super-admin') {
            return back()->with('error', 'Cannot delete system role.');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }public function managePermissions(Role $role)
    {
        $allPermissions = Permission::orderBy('name')->get();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        
        return view('pages.user-management.roles.permissions', compact('role', 'allPermissions', 'rolePermissions'));
    }

    // In your RoleController.php - assign method
public function assignPermissions(Request $request, Role $role)
{
    $request->validate([
        'permissions' => 'required|array',
    ]);

    // Sync permissions by NAME (Spatie expects names)
    $role->syncPermissions($request->permissions);

    return redirect()->back()->with('success', 'Permissions updated successfully!');
}
}
