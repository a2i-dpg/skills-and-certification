<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class InstituteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('institutes')->truncate();

        \DB::table('institutes')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'title' => 'BITAC',
                    'address' => 'Dhaka-1200',
                    'email' => 'bitac@gmail.com',
                    'mobile' => '01837473838',
                    'office_head_name' => 'SK',
                    'office_head_post' => 'SWE',
                    'contact_person_name' => 'HR',
                    'contact_person_email' => 'HR@gmail.com',
                    'contact_person_mobile' => '01837473838',
                    'contact_person_post' => 'HR',
                    'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries,',
                    'row_status' => 1,
                    'slug' => Str::slug('bitac'),
                ),

            1 =>
                array(
                    'id' => 2,
                    'title' => 'TFL',
                    'address' => 'CA',
                    'email' => 'tfl@gmail.com',
                    'mobile' => '01837473839',
                    'office_head_name' => 'SKK',
                    'office_head_post' => 'CEO',
                    'contact_person_name' => 'John Doe',
                    'contact_person_email' => 'HR@gmail.com',
                    'contact_person_mobile' => '01837473823',
                    'contact_person_post' => 'HR',
                    'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries,',
                    'row_status' => 1,
                    'slug' => Str::slug('tfl'),
                ),
        ));
        Schema::enableForeignKeyConstraints();
    }
}
