<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('branches')->truncate();

        \DB::table('branches')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'institute_id' => 1,
                    'title' => 'Bashundhara Branch',
                    'created_by' => '1',
                    'address' => '',
                    'row_status' => 1
                ),
            1 =>
                array(
                    'id' => 2,
                    'institute_id' => 1,
                    'title' => 'Uttara Branch',
                    'created_by' => '1',
                    'address' => '',
                    'row_status' => 1
                )
        ));
        Schema::enableForeignKeyConstraints();
    }

}
