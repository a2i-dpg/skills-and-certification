<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('sliders')->truncate();

        \DB::table('sliders')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'institute_id' => 1,
                    'title' => 'Slider Image 1',
                    'sub_title' => 'Slider Image 1',
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ),
            1 =>
                array(
                    'id' => 2,
                    'institute_id' => 1,
                    'title' => 'Slider Image 2',
                    'sub_title' => 'Slider Image 2',
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ),
            2 =>
                array(
                    'id' => 3,
                    'institute_id' => 1,
                    'title' => 'Slider Image 3',
                    'sub_title' => 'Slider Image 3',
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ),
            
        ));
        Schema::enableForeignKeyConstraints();
    }
}
