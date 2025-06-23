<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Position;
use Illuminate\Support\Facades\DB; // Import the DB facade

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('positions')->insert([
            ['name' => 'HR COMPLIANCE', 'department_id' => '1'],
            ['name' => 'HR RECRUITMENT', 'department_id' => '1'],
            ['name' => 'HR COORDINATOR', 'department_id' => '1'],
            ['name' => 'HR COMPENSATION & BENEFITS', 'department_id' => '1'],
            ['name' => 'FINANCE HEAD', 'department_id' => '2'],
            ['name' => 'SENIOR BOOKKEEPER', 'department_id' => '2'],
            ['name' => 'JUNIOR BOOKKEEPER', 'department_id' => '2'],
            ['name' => 'CREDIT & COLLECTION', 'department_id' => '2'],
            ['name' => 'BILLING STAFF', 'department_id' => '2'],
            ['name' => 'TREASURY', 'department_id' => '2'],
            ['name' => 'CORPORATE ADMIN', 'department_id' => '3'],
            ['name' => 'ELECTRICIAN MAINTENANCE STAFF', 'department_id' => '3'],
            ['name' => 'IT STAFF', 'department_id' => '3'],
            ['name' => 'UTILITY STAFF', 'department_id' => '3'],
            ['name' => 'PROPERTY MANAGEMENT STAFF', 'department_id' => '3'],
            ['name' => 'BUILDING MAINTENANCE', 'department_id' => '3'],
            ['name' => 'ADMIN OFFICER', 'department_id' => '3'],
            ['name' => 'MARKETING SUPERVISOR', 'department_id' => '4'],
            ['name' => 'MARKETING ASSISTANT', 'department_id' => '4'],
            ['name' => 'PRODUCT SPECIALIST(SUPPLIES)', 'department_id' => '4'],
            ['name' => 'PRODUCT SPECIALIST(EQUIPMENT)', 'department_id' => '4'],
            ['name' => 'SENIOR PHSS', 'department_id' => '4'],
            ['name' => 'PHSS', 'department_id' => '4'],
            ['name' => 'BIOMEDICAL SUPERVISOR', 'department_id' => '8'],
            ['name' => 'BIOMEDICAL TECHNICIAN', 'department_id' => '8'],
            ['name' => 'WAREHOUSE COORDINATOR', 'department_id' => '5'],
            ['name' => 'WAREHOUSE SUPERVISOR', 'department_id' => '5'],
            ['name' => 'WAREHOUSE ASSISTANT', 'department_id' => '5'],
            ['name' => 'WAREHOUSE MAN', 'department_id' => '5'],
            ['name' => 'DRIVER', 'department_id' => '5'],
            ['name' => 'WAREHOUSE & LOGISTIC STAFF', 'department_id' => '5'],
            ['name' => 'OFFICER IN-CHARGE', 'department_id' => '5'],
            ['name' => 'PROCUREMENT OFFICER', 'department_id' => '5'],
            ['name' => 'CASHIER', 'department_id' => '7'],
            ['name' => 'PUMP ATTENDANT', 'department_id' => '7'],
            ['name' => 'STAFF', 'department_id' => '6'],
        ]);
    }
}
