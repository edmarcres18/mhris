<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Load provinces from the JSON file
        $json = File::get(database_path('seeders/province.json'));
        $provinces = json_decode($json, true);

        // Insert provinces into the database
        DB::table('provinces')->insert($provinces);
    }
}
