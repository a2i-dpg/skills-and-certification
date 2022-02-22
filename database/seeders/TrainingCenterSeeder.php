<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TrainingCenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('training_centers')->truncate();

        DB::table('training_centers')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'institute_id' => 1,
                    'title' => 'CSL Training Centre',
                    'address' => '',
                    'created_by' => '1',
                    'row_status' => 1
                ),
            1 =>
                array(
                    'id' => 2,
                    'institute_id' => 1,
                    'title' => 'Creative IT',
                    'address' => '',
                    'created_by' => '1',
                    'row_status' => 1
                )
        ));
        Schema::enableForeignKeyConstraints();
    }
}
