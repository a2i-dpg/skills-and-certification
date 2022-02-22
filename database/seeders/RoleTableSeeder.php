<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoleTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('roles')->truncate();

        DB::table('roles')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'title' => 'Super Admin',
                    'code' => 'super_admin',
                    'is_deletable' => '0'
                ),
            1 =>
                array(
                    'id' => 2,
                    'title' => 'System Admin',
                    'code' => 'system_admin',
                    'is_deletable' => '0'
                ),
            2 =>
                array(
                    'id' => 3,
                    'title' => 'Institute Admin',
                    'code' => 'institute_admin',
                    'is_deletable' => '0'
                ),
            3 =>
                array(
                    'id' => 4,
                    'title' => 'Training Center Admin',
                    'code' => 'training_center_admin',
                    'is_deletable' => '0'
                ),
            4 =>
                array(
                    'id' => 5,
                    'title' => 'Branch Admin',
                    'code' => 'branch_admin',
                    'is_deletable' => '0'
                ),
            5 =>
                array(
                    'id' => 6,
                    'title' => 'Trainer',
                    'code' => 'trainer',
                    'is_deletable' => '0'
                ),
        ));

        Schema::enableForeignKeyConstraints();
    }
}
