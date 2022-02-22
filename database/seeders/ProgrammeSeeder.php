<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProgrammeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('programmes')->truncate();

        \DB::table('programmes')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'institute_id' => 1,
                    'title' => 'PROGRAMME ON RMG',
                    'code' => 'PR1212',
                    'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries,',
                    'created_by' => '1',
                    'row_status' => 1
                ),
            1 =>
                array(
                    'id' => 2,
                    'institute_id' => 1,
                    'title' => 'STEP',
                    'code' => 'PR2020',
                    'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries,',
                    'created_by' => '1',
                    'row_status' => 1
                )
        ));
        Schema::enableForeignKeyConstraints();
    }
}
