<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class GalleryAlbumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('gallery_categories')->truncate();

        \DB::table('gallery_categories')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'institute_id' => 1,
                    'title' => 'PHP',
                    'featured' => 1,
                    'created_by' => 1,
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ),
            1 =>
                array(
                    'id' => 2,
                    'institute_id' => 1,
                    'title' => 'JAVA',
                    'featured' => 1,
                    'created_by' => 1,
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ),
            2 =>
                array(
                    'id' => 3,
                    'institute_id' => 1,
                    'title' => 'JAVA SCRIPT',
                    'featured' => 1,
                    'created_by' => 1,
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ),
            3 =>
                array(
                    'id' => 4,
                    'institute_id' => 1,
                    'title' => 'C#',
                    'featured' => 1,
                    'created_by' => 1,
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ),
            
        ));
        Schema::enableForeignKeyConstraints();
    }
}
