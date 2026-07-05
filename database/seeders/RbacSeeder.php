<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use App\Models\Admin;
use Illuminate\Database\Seeder;

class RbacSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Define all permissions
        $permissions = [
            'manage-dashboard' => 'Access and view dashboard statistics',
            'manage-admins' => 'Create, edit, delete admin accounts',
            'manage-users' => 'Manage registered frontend customers',
            'manage-roles' => 'Manage security roles and permissions',
            'manage-categories' => 'Manage product categories and subcategories',
            'manage-brands' => 'Manage product brands',
            'manage-products' => 'Manage products, inventory, attributes and variants',
            'manage-orders' => 'Manage customer orders and payments',
            'manage-coupons' => 'Manage discount coupons',
            'manage-reviews' => 'Approve or delete product reviews',
            'manage-blogs' => 'Create and edit blog posts',
            'manage-settings' => 'Modify store settings',
            'manage-logs' => 'View activity and audit logs',
        ];

        $permissionModels = [];
        foreach ($permissions as $slug => $description) {
            $permissionModels[$slug] = Permission::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => ucwords(str_replace('-', ' ', $slug)),
                    'description' => $description,
                ]
            );
        }

        // 2. Define Roles and attach permissions
        
        // Super Admin
        $superAdminRole = Role::updateOrCreate(
            ['slug' => 'super-admin'],
            [
                'name' => 'Super Admin',
                'description' => 'Unrestricted system access',
            ]
        );
        $superAdminRole->permissions()->sync(Permission::all()->pluck('id'));

        // Admin
        $adminRole = Role::updateOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Admin',
                'description' => 'Full administrative access except logs/RBAC modifications',
            ]
        );
        $adminRole->permissions()->sync(
            Permission::whereIn('slug', [
                'manage-dashboard',
                'manage-admins',
                'manage-users',
                'manage-categories',
                'manage-brands',
                'manage-products',
                'manage-orders',
                'manage-coupons',
                'manage-reviews',
                'manage-blogs',
                'manage-settings',
            ])->pluck('id')
        );

        // Manager
        $managerRole = Role::updateOrCreate(
            ['slug' => 'manager'],
            [
                'name' => 'Manager',
                'description' => 'Catalog and sales operations manager',
            ]
        );
        $managerRole->permissions()->sync(
            Permission::whereIn('slug', [
                'manage-dashboard',
                'manage-categories',
                'manage-brands',
                'manage-products',
                'manage-orders',
                'manage-coupons',
                'manage-reviews',
                'manage-blogs',
            ])->pluck('id')
        );

        // Staff
        $staffRole = Role::updateOrCreate(
            ['slug' => 'staff'],
            [
                'name' => 'Staff',
                'description' => 'General support and order fulfillment staff',
            ]
        );
        $staffRole->permissions()->sync(
            Permission::whereIn('slug', [
                'manage-dashboard',
                'manage-products',
                'manage-orders',
            ])->pluck('id')
        );

        // 3. Assign role to default admin
        $adminUser = Admin::where('email', config('shop.admin.email'))->first();
        if ($adminUser) {
            $adminUser->assignRole($superAdminRole);
        }
    }
}
