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
        DB::table('intro_videos')->truncate();

        \DB::table('branches')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'institute_id' => 1,
                    'title' => 'Bashundhara Branch',
                    'address' => 'Bashundhara, Dhaka-1219',
                    'row_status' => 1,
                    'created_by' => 1,
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ),
            1 =>
                array(
                    'id' => 2,
                    'institute_id' => 1,
                    'title' => 'Uttara Branch',
                    'address' => 'Uttara, Dhaka-1206',
                    'row_status' => 1,
                    'created_by' => 1,
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                )
        ));

        \DB::table('intro_videos')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'institute_id' => 1,
                    'youtube_video_url' => 'https://www.youtube.com/watch?v=K6fNnOgXmOU',
                    'youtube_video_id' => 'K6fNnOgXmOU',
                    'row_status' => 1,
                    'created_by' => 1,
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ),
        ));
        Schema::enableForeignKeyConstraints();
    }

}
