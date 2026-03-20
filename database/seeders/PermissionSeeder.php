<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define Modules based on your Blade Sidebar
        $modules = [
            'dashboard',
            'crm', // Parent Group
            'leads',
            'colleges',
            'courses',
            'admissions',
            'documents',
            'commission-rules',
            'commission-payments',
            'consultants',
            'crm-settings', // Parent Group
            'sources',
            'contact-stage',
            'qualifications',
            'intakes',
            'priorities',
            'document-settings',
            'communication-logs',
            'user-management', // Parent Group
            'users',
            'roles',
            'permissions',
        ];

        // 2. Define Actions
        $actions = ['view', 'create', 'edit', 'delete'];

        // 3. Generate Permissions
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                // Special case: Dashboard usually only needs 'view', but we create all for consistency
                Permission::firstOrCreate(['name' => "{$action}-{$module}"]);
            }
        }

        // 4. Create Roles
        $superAdmin = Role::firstOrCreate(['name' => 'superadmin']);
        $admin      = Role::firstOrCreate(['name' => 'admin']);
        $staff      = Role::firstOrCreate(['name' => 'staff']);
        $user       = Role::firstOrCreate(['name' => 'user']);

        // 5. Assign Permissions to Roles

        // Super Admin: Gets Everything
        $superAdmin->givePermissionTo(Permission::all());

        // Admin: Gets everything except User Management (optional configuration)
        $adminPermissions = Permission::whereNotIn('name', [
            'delete-users', 
            'delete-roles', 
            'delete-permissions'
        ])->get();
        $admin->givePermissionTo($adminPermissions);

        // Staff: CRM Access only (No Settings, No User Management)
        $staffPermissions = Permission::whereIn('name', [
            'view-dashboard',
            'view-crm', 'view-leads', 'create-leads', 'edit-leads',
            'view-colleges', 'view-courses', 'view-admissions',
            'view-documents', 'view-consultants',
            'view-communication-logs', 'create-communication-logs'
        ])->get();
        $staff->givePermissionTo($staffPermissions);

        // User: Read Only access to specific areas
        $user->givePermissionTo([
            'view-dashboard',
            'view-leads',
            'view-courses',
            'view-documents'
        ]);
    }
}