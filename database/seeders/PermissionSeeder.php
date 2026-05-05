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
            'crm',
            'leads',
            'colleges',
            'courses',
            'admissions',
            'documents',
            'commission-rules',
            'commission-payments',
            'consultants',
            'crm-settings',
            'sources',
            'contact-stage',
            'qualifications',
            'intakes',
            'priorities',
            'document-settings',
            'communication-logs',
            'user-management',
            'users',
            'roles',
            'permissions',
        ];

        // 2. Define Actions
        $actions = ['view', 'create', 'edit', 'delete'];

        // 3. Generate Permissions for Modules
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$action}-{$module}"]);
            }
        }

        // 4. ✅ ADD SETTINGS PERMISSIONS (New Section)
        $settingsPermissions = [
            // Main Settings Group
            'view-settings',
            
            // General Settings
            'view-general-settings',
            'view-profile-settings',
            'view-security-settings',
            
            // Website Settings
            'view-website-settings',
            'view-localization-settings',
            'view-language-settings',
            
            // App Settings
            'view-app-settings',
            'view-invoice-settings',
            
            // System Settings
            'view-system-settings',
            'view-email-settings',
            'view-cookie-settings',
            
            // Financial Settings
            'view-financial-settings',
            'view-payment-gateways',
            'view-bank-accounts',
            'view-tax-rates',
            'view-currencies',
        ];

        foreach ($settingsPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 5. Create Roles
        $superAdmin = Role::firstOrCreate(['name' => 'superadmin']);
        $admin      = Role::firstOrCreate(['name' => 'admin']);
        $staff      = Role::firstOrCreate(['name' => 'staff']);
        $user       = Role::firstOrCreate(['name' => 'user']);
        $consultant = Role::firstOrCreate(['name' => 'consultant']);

        // 6. Assign Permissions to Roles

        // 🔹 Super Admin: Gets EVERYTHING including Settings
        $superAdmin->givePermissionTo(Permission::all());

        // 🔹 Admin: Gets everything except User Management deletion + ALL Settings
        $adminExcluded = [
            'delete-users', 
            'delete-roles', 
            'delete-permissions'
        ];
        $adminPermissions = Permission::whereNotIn('name', $adminExcluded)->get();
        $admin->givePermissionTo($adminPermissions);

        // 🔹 Staff: CRM Access only (NO Settings, NO User Management)
        $staffPermissions = Permission::whereIn('name', [
            'view-dashboard',
            'view-crm', 'view-leads', 'create-leads', 'edit-leads',
            'view-colleges', 'view-courses', 'view-admissions',
            'view-documents', 'view-consultants',
            'view-communication-logs', 'create-communication-logs'
        ])->get();
        $staff->givePermissionTo($staffPermissions);

        // 🔹 Consultant: Limited Access (NO Settings)
        $consultantPermissions = Permission::whereIn('name', [
            'view-dashboard',
            'view-leads',
            'view-documents',
            'view-commission-rules',
            'view-payment-requests',
            'view-consultants',
            'edit-consultants',
        ])->get();
        $consultant->givePermissionTo($consultantPermissions);

        // 🔹 User: Read Only (NO Settings)
        $user->givePermissionTo([
            'view-dashboard',
            'view-leads',
            'view-courses',
            'view-documents'
        ]);
    }
}