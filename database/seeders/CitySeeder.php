<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path to the JSON file
        $jsonFilePath = database_path('seeders/city.json');

        // Get the contents of the JSON file
        $jsonData = File::get($jsonFilePath);

        // Decode the JSON data into an array
        $cities = json_decode($jsonData, true);

        // Insert cities into the database
        DB::table('cities')->insert($cities);
    }
}
