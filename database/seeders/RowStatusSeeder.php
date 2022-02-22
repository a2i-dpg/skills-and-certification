<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RowStatusSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('row_status')->truncate();

        DB::table('row_status')->insert(array (
            0 =>
            array (
                'id' => 1,
                'title' => 'Inactive',
                'code' => 0
            ),
            1 =>
            array (
                'id' => 2,
                'title' => 'Active',
                'code' => 1
            ),
            2 =>
            array (
                'id' => 3,
                'title' => 'Deleted',
                'code' => 99
            )
        ));

        Schema::enableForeignKeyConstraints();
    }
}
