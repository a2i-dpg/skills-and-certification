<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserTypeSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('user_types')->truncate();

        DB::table('user_types')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'title' => 'Super User',
                    'code' => '1',
                    'parent_id' => null,
                    'row_status' => 1,
                    'default_role_id' => '1'
                ),
            1 =>
                array(
                    'id' => 2,
                    'title' => 'System User',
                    'code' => '2',
                    'parent_id' => null,
                    'row_status' => 1,
                    'default_role_id' => '2'
                ),
            2 =>
                array(
                    'id' => 3,
                    'title' => 'Institute User',
                    'code' => '3',
                    'parent_id' => null,
                    'row_status' => 1,
                    'default_role_id' => '3'
                ),
            3 =>
                array(
                    'id' => 4,
                    'title' => 'Trainer',
                    'parent_id' => '3',
                    'code' => '4',
                    'row_status' => 1,
                    'default_role_id' => '6'
                ),
            4 =>
                array(
                    'id' => 5,
                    'title' => 'Branch User',
                    'parent_id' => '3',
                    'code' => '5',
                    'row_status' => 1,
                    'default_role_id' => '5'
                ),
            5 =>
                array(
                    'id' => 6,
                    'title' => 'Training Center User',
                    'parent_id' => '3',
                    'code' => '6',
                    'row_status' => 1,
                    'default_role_id' => '4'
                ),
            6 =>
                array(
                    'id' => 7,
                    'title' => 'Trainee',
                    'code' => '7',
                    'row_status' => 1,
                    'parent_id' => null,
                    'default_role_id' => null
                ),
        ));

        DB::table('users')->truncate();

        DB::table('users')->insert([
            0 => [
                'id' => 1,
                'name' => 'Super Admin',
                'email' => 'admin@gmail.com',
                'user_type_id' => 1,
                'institute_id' => null,
                'role_id' => 1,
                'password' => Hash::make('password'),
                'row_status' => 1,
                'created_at' => NOW(),
                'updated_at' => NOW(),
                'email_verified_at' => NOW(),
                'created_by' => null,
            ],
            1 => [
                'id' => 2,
                'name' => 'Mr.Trainee',
                'email' => 'trainee@gmail.com',
                'user_type_id' => 7,
                'institute_id' => null,
                'role_id' => null,
                'password' => Hash::make('password'),
                'row_status' => 1,
                'created_at' => NOW(),
                'updated_at' => NOW(),
                'email_verified_at' => NOW(),
                'created_by' => null,
            ],
            2 => [
                'id' => 3,
                'name' => 'BITAC Institute Admin',
                'email' => 'bitac@gmail.com',
                'user_type_id' => 3,
                'institute_id' => 1,
                'role_id' => 3,
                'password' => Hash::make('password'),
                'row_status' => 1,
                'created_at' => NOW(),
                'updated_at' => NOW(),
                'email_verified_at' => NOW(),
                'created_by' => 1,
            ],
            3 => [
                'id' => 4,
                'name' => 'TFL Institute Admin',
                'email' => 'tfl@gmail.com',
                'user_type_id' => 3,
                'institute_id' => 2,
                'role_id' => 3,
                'password' => Hash::make('password'),
                'row_status' => 1,
                'created_at' => NOW(),
                'updated_at' => NOW(),
                'email_verified_at' => NOW(),
                'created_by' => 1,
            ],
            4 => [
                'id' => 5,
                'name' => 'Mr. Trainer',
                'email' => 'trainer@gmail.com',
                'user_type_id' => 4,
                'institute_id' => 1,
                'role_id' => 6,
                'password' => Hash::make('password'),
                'row_status' => 1,
                'created_at' => NOW(),
                'updated_at' => NOW(),
                'email_verified_at' => NOW(),
                'created_by' => 1,
            ],
            5 => [
                'id' => 6,
                'name' => 'Mr. Bitac Branch User',
                'email' => 'branch@gmail.com',
                'user_type_id' => 5,
                'institute_id' => 1,
                'role_id' => 5,
                'password' => Hash::make('password'),
                'row_status' => 1,
                'created_at' => NOW(),
                'updated_at' => NOW(),
                'email_verified_at' => NOW(),
                'created_by' => 1,
            ],
            6 => [
                'id' => 7,
                'name' => 'Mr. Bitac Trainig Center User',
                'email' => 'training-center@gmail.com',
                'user_type_id' => 6,
                'institute_id' => 1,
                'role_id' => 4,
                'password' => Hash::make('password'),
                'row_status' => 1,
                'created_at' => NOW(),
                'updated_at' => NOW(),
                'email_verified_at' => NOW(),
                'created_by' => 1,
            ],
            7 => [
                'id' => 8,
                'name' => 'System Admin',
                'email' => 'system-admin@gmail.com',
                'user_type_id' => 2,
                'institute_id' => null,
                'role_id' => 2,
                'password' => Hash::make('password'),
                'row_status' => 1,
                'created_at' => NOW(),
                'updated_at' => NOW(),
                'email_verified_at' => NOW(),
                'created_by' => null,
            ],
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
