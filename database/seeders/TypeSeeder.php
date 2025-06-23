<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Type;
use Illuminate\Support\Facades\DB; // Import the DB facade

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('types')->insert([
            ['name' => 'Vacation Leave'],
            ['name' => 'Emergency Leave'],
            ['name' => 'Sick Leave'],
        ]);
    }
}
