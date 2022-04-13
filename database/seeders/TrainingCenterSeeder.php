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
        //DB::table('programmes')->truncate();

        DB::table('training_centers')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'institute_id' => 1,
                    'branch_id' => 1,
                    'title' => 'Basundhara Training Centre',
                    'address' => 'Basundhara, Dhaka',
                    'mobile' => '0171*******',
                    'created_by' => '1',
                    'row_status' => 1,
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ),
            1 =>
                array(
                    'id' => 2,
                    'institute_id' => 1,
                    'branch_id' => 2,
                    'title' => 'Uttara Training Center',
                    'address' => 'Uttara, Dhaka',
                    'mobile' => '0171*******',
                    'created_by' => '1',
                    'row_status' => 1,
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                )
        ));

        // DB::table('programmes')->insert(array(
        //     0 =>
        //         array(
        //             'id' => 1,
        //             'institute_id' => 1,
        //             'title' => 'Spring',
        //             'code' => 'SP-001',
        //             'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
        //             'created_by' => '1',
        //             'row_status' => 1,
        //             'created_at' => NOW(),
        //             'updated_at' => NOW(),
        //         ),
        //     1 =>
        //         array(
        //             'id' => 2,
        //             'institute_id' => 1,
        //             'title' => 'Summer',
        //             'code' => 'SM-001',
        //             'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
        //             'created_by' => '1',
        //             'row_status' => 1,
        //             'created_at' => NOW(),
        //             'updated_at' => NOW(),
        //         ),
        //     2 =>
        //         array(
        //             'id' => 3,
        //             'institute_id' => 1,
        //             'title' => 'Fall',
        //             'code' => 'FL-001',
        //             'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
        //             'created_by' => '1',
        //             'row_status' => 1,
        //             'created_at' => NOW(),
        //             'updated_at' => NOW(),
        //         )
        // ));
        Schema::enableForeignKeyConstraints();
    }
}
