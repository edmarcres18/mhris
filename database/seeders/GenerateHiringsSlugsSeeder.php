<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hiring;
use Illuminate\Support\Str;

class GenerateHiringsSlugsSeeder extends Seeder
{
    public function run()
    {
        $hirings = Hiring::whereNull('slug')->get();

        foreach ($hirings as $hiring) {
            $hiring->slug = Hiring::generateUniqueSlug($hiring->position);
            $hiring->save();
        }
    }
}
