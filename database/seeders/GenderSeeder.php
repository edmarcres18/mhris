<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gender;
use Illuminate\Support\Facades\DB; // Import the DB facade

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('genders')->insert([
            ['name' => 'Male'],
            ['name' => 'Female'],
        ]);
    }
}
