<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use Illuminate\Support\Facades\DB; // Import the DB facade

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->insert([
            ['name' => 'Human Resources Department', 'head_name' => 'Nikkei Tingson'],
            ['name' => 'Finance and Accounting Department', 'head_name' => 'Janet R. Refugio'],
            ['name' => 'Admin Department', 'head_name' => 'Michael Amus H. Refugio'],
            ['name' => 'Marketing Department', 'head_name' => 'Alayza Cali-at'],
            ['name' => 'Supply Chain Department', 'head_name' => 'Michael Amus H. Refugio'],
            ['name' => 'VHI', 'head_name' => 'Michael Amus H. Refugio'],
            ['name' => 'BGPDI', 'head_name' => 'Michael Amus H. Refugio'],
            ['name' => 'Technical Department', 'head_name' => 'Oniebie B. Tabacon'],
        ]);
    }
}
