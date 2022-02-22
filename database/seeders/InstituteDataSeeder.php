<?php

namespace Database\Seeders;

use App\Models\Institute;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstituteDataSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Institute::factory()->count(2)->create();
    }
}
