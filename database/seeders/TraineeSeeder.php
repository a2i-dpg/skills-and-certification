<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class TraineeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('trainees')->truncate();

        \DB::table('trainees')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'name' => 'ak',
                    'mobile' => '01746383836',
                    'email' => 'ak@gmail.com',
                    'address' => 'LA',
                    'date_of_birth' => Date::now(),
                    'gender' => 1,
                    'disable_status' => 2,
                    'ethnic_group' => 2,
                    'password' => Hash::make('123'),
                ),

            1 =>
                array(
                    'id' => 2,
                    'name' => 'bk',
                    'mobile' => '017463836378',
                    'email' => 'bk@gmail.com',
                    'address' => 'Tennesse',
                    'date_of_birth' => Date::now(),
                    'gender' => 1,
                    'disable_status' => 2,
                    'ethnic_group' => 2,
                    'password' => Hash::make('123'),
                ),
        ));

        Schema::enableForeignKeyConstraints();
    }
}
