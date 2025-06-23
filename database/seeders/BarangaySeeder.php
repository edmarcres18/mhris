<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BarangaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path to the JSON file
        $jsonFilePath = database_path('seeders/barangays.json');

        // Get the contents of the JSON file
        $jsonData = File::get($jsonFilePath);

        // Decode the JSON data into an array
        $barangays = json_decode($jsonData, true);

        // Insert the barangay data into the database in batches
        $chunkSize = 500; // You can adjust this number as needed
        foreach (array_chunk($barangays, $chunkSize) as $chunk) {
            DB::table('barangays')->insert($chunk);
        }
    }
}
