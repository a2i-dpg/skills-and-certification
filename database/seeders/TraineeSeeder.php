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
                    'user_id' => 2,
                    'name' => 'Mr.Trainee',
                    'mobile' => '01746383836',
                    'email' => 'trainee@gmail.com',
                    'address' => 'Mirpur, Dhaka-1212',
                    'date_of_birth' => Date::now(),
                    'gender' => 1,
                    'disable_status' => 2,
                    'ethnic_group' => 2,
                    'password' => Hash::make('password'),
                    'row_status' => 1,
                    'created_at' => NOW(),
                    'updated_at' => NOW()
                )
        ));

        Schema::enableForeignKeyConstraints();
    }
}
