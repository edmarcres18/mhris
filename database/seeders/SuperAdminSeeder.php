<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creating Super Admin User
        $superAdmin = User::create([
            'first_name' => 'System',
            'middle_name' => ' ',
            'last_name' => 'Admin',
            'suffix' => ' ',
            'email' => 'system@admin.com',
            'bio' => 'Sys Admin',
            'password' => Hash::make('mhrpci-admin@2024'),
            'profile_image' => '',
        ]);
        $superAdmin->assignRole('Super Admin');

        // Creating Admin User
        $admin = User::create([
            'first_name' => 'Admin',
            'middle_name' => ' ',
            'last_name' => 'Administrator',
            'suffix' => ' ',
            'email' => 'admin@administrator.com',
            'bio' => 'Admin',
            'password' => Hash::make('Letmein@2024'),
            'profile_image' => '',
        ]);
        $admin->assignRole('Admin');

        $financeAdmin = User::create([
            'first_name' => 'VP',
            'middle_name' => ' ',
            'last_name' => 'Finance',
            'suffix' => ' ',
            'email' => 'vpfinance@admin.com',
            'bio' => 'VP Finance',
            'password' => Hash::make('vpFinance@2024'),
            'profile_image' => '',
        ]);
        $financeAdmin->assignRole('VP Finance');

         // Creating HR Compliance User
         $finance = User::create([
            'first_name' => 'Finance',
            'middle_name' => ' ',
            'last_name' => 'Head',
            'suffix' => ' ',
            'email' => 'finance@head.com',
            'bio' => 'HR Compliance',
            'password' => Hash::make('Finance@2024'),
            'profile_image' => '',
        ]);
        $finance->assignRole('Finance');

         // Creating HR Compliance User
         $hrcompliance = User::create([
            'first_name' => 'HR',
            'middle_name' => ' ',
            'last_name' => 'Compliance',
            'suffix' => ' ',
            'email' => 'hr@compliance.com',
            'bio' => 'HR Compliance',
            'password' => Hash::make('HrCompliance@2024'),
            'profile_image' => '',
        ]);
        $hrcompliance->assignRole('HR  Compliance');

         // Creating HR ComBen User
         $hrcomben = User::create([
            'first_name' => 'HR',
            'middle_name' => ' ',
            'last_name' => 'ComBen',
            'suffix' => ' ',
            'email' => 'hr@comben.com',
            'bio' => 'HR ComBen',
            'password' => Hash::make('HrComben@2024'),
            'profile_image' => '',
        ]);
        $hrcomben->assignRole('HR ComBen');

           // Creating HR Policy User
       $hrhiring = User::create([
        'first_name' => 'HR',
        'middle_name' => ' ',
        'last_name' => 'Hiring',
        'suffix' => ' ',
        'email' => 'hr@hiring.com',
        'bio' => 'HR Hiring',
        'password' => Hash::make('HrHiring@2024'),
        'profile_image' => '',
        ]);
        $hrhiring->assignRole('HR Hiring');

        // Creating Product Manager User
        $productManager = User::create([
            'first_name' => 'Product',
            'middle_name' => ' ',
            'last_name' => 'Specialist',
            'suffix' => ' ',
            'email' => 'mrkgasst.mhrpci@gmail.com',
            'bio' => 'Product Manager',
            'password' => Hash::make('Product@manager2024'),
            'profile_image' => '',
            'department_id' => '',
        ]);
        $productManager->assignRole('Product Manager');

        // Creating Product Manager User
        $productManager = User::create([
            'first_name' => 'Marketing',
            'middle_name' => ' ',
            'last_name' => 'Assistant',
            'suffix' => ' ',
            'email' => 'csr.mhrhealthcare@gmail.com',
            'bio' => 'Marketing Assistant',
            'password' => Hash::make('Marketing@assistant2024'),
            'profile_image' => '',
            'department_id' => '',
        ]);
        $productManager->assignRole('Product Manager');
    }
}