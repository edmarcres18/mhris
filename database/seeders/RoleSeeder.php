<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Super Admin']);
        $admin = Role::create(['name' => 'Admin']);
        $financeAdmin = Role::create(['name' => 'VP Finance']);
        $hrcompliance = Role::create(['name' => 'HR  Compliance']);
        $hrcomben = Role::create(['name' => 'HR ComBen']);
        $employee = Role::create(['name' => 'Employee']);
        $it = Role::create(['name' => 'IT Staff']);
        $hrpolicy = Role::create(['name' => 'HR Policy']);
        $hrhiring = Role::create(['name' => 'HR Hiring']);
        $financeHead = Role::create(['name' => 'Finance']);
        $superVisor = Role::create(['name' => 'Supervisor']);
        $productManager = Role::create(['name' => 'Product Manager']);

        $admin->givePermissionTo([
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'position-list',
            'position-create',
            'position-edit',
            'position-delete',
            'department-list',
            'employee-list',
            'post-list',
            'post-create',
            'post-edit',
            'post-delete',
            'attendance-list',
            'leave-list',
            'contribution-list',
            'loan-list',
            'admin',
            'post-show',
            'payroll-list',

        ]);
        $financeAdmin->givePermissionTo([
            'user-list',
            'position-list',
            'department-list',
            'employee-list',
            'post-list',
            'post-create',
            'post-edit',
            'attendance-list',
            'leave-list',
            'contribution-list',
            'loan-list',
            'vpfinance-admin',
            'post-show',
            'payroll-list',

        ]);

        $hrcompliance->givePermissionTo([
            'employee-list',
            'employee-create',
            'employee-edit',
            'department-list',
            'department-create',
            'department-edit',
            'position-list',
            'position-create',
            'position-edit',
            'holiday-list',
            'post-list',
            'post-create',
            'post-edit',
            'inventory-list',
            'inventory-create',
            'inventory-edit',
            'hrcompliance',

        ]);
        $hrcomben->givePermissionTo([
            'attendance-list',
            'attendance-create',
            'attendance-edit',
            'holiday-list',
            'holiday-create',
            'holiday-edit',
            'leave-list',
            'leave-create',
            'payroll-list',
            'payroll-create',
            'contribution-list',
            'contribution-create',
            'contribution-edit',
            'overtime-list',
            'overtime-create',
            'overtime-edit',
            'loan-list',
            'loan-create',
            'loan-edit',
            'hrcomben',
        ]);
        $financeHead->givePermissionTo([
            'attendance-list',
            'leave-list',
            'leave-create',
            'payroll-list',
            'payroll-create',
            'payroll-edit',
            'contribution-list',
            'contribution-create',
            'contribution-edit',
            'employee-list',
            'employee-edit',
            'overtime-list',
            'overtime-create',
            'overtime-edit',
            'loan-list',
            'loan-create',
            'loan-edit',
            'finance',
        ]);

        $it->givePermissionTo([
            'inventory-list',
            'inventory-create',
            'inventory-edit',
            'inventory-delete',
            'it-staff',
        ]);
        $hrpolicy->givePermissionTo([
            'credential-list',
            'credential-create',
            'credential-edit',
            'credential-delete',
            'post-list',
            'post-create',
            'post-edit',
            'hrpolicy',
        ]);
        $hrhiring->givePermissionTo([
            'hiring-list',
            'hiring-create',
            'hiring-edit',
            'hrhiring',
        ]);

        $employee->givePermissionTo([
            'attendance-create',
            'leave-create',
            'payroll-list',
            'normal-employee',

        ]);

        $superVisor->givePermissionTo([
            'attendance-list',
            'attendance-create',
            'leave-list',
            'leave-create',
            'leave-edit',
            'supervisor',
        ]);
        $productManager->givePermissionTo([
            'product-list',
            'product-create',
            'product-edit',
            'product-delete',
            'category-list',
            'category-create',
            'category-edit',
            'category-delete',
            'product-manager',
        ]);
    }
}
