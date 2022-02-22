<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('batches')->truncate();

        \DB::table('batches')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'institute_id' => 1,
                    'branch_id' => null,
                    'course_id' => 1,
                    'training_center_id' => 1,
                    'title' => 'BITAC-batch-1',
                    'code' => '1200',
                    'application_start_date' => Date::now(),
                    'application_end_date' => Date::now()->addDays(6),
                    'batch_start_date' => Date::now()->addDays(12),
                    'batch_end_date' => Date::now()->addDays(30),
                ),

            1 =>
                array(
                    'id' => 2,
                    'institute_id' => 2,
                    'branch_id' => null,
                    'course_id' => 2,
                    'training_center_id' => 1,
                    'title' => 'TFL-Batch-2',
                    'code' => '4500',
                    'application_start_date' => Date::now(),
                    'application_end_date' => Date::now()->addDays(6),
                    'batch_start_date' => Date::now()->addDays(12),
                    'batch_end_date' => Date::now()->addDays(30),
                ),
        ));

        Schema::enableForeignKeyConstraints();
    }
}
