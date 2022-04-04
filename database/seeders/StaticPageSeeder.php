<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StaticPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Schema::disableForeignKeyConstraints();

        DB::table('static_pages')->truncate();

        \DB::table('static_pages')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'title' => 'About Us',
                    'page_id' => 'aboutus',
                    'institute_id' => null,
                    'created_by' => '1',
                    'page_contents' => '<h2 class="section-heading" style="text-align: center;">About Us</h2>
                            <p>&nbsp;</p>
                            <p>To conduct and monitor various training courses online with the aim of making the youth self-employed and self-reliant in the implementation of the vision 2021 of the Government of the People\'s Republic of Bangladesh. This web application is basically "Online Course Management System". In this platform, students can easily become self-reliant by training in various training courses. The student will have to request apply to the admin for training on his her own choice. At the end of the training the student will be given a certificate</p>
                            <h2 class="para-heading">Aims / Objectives of the Portalঃ</h2>
                            <ul class="sidebar-list">
                            <li>&nbsp;Students can apply for various training courses on this platform.</li>
                            <li>&nbsp;It is possible to conduct and monitor many courses simultaneously in different categories.</li>
                            <li>&nbsp;Students skills are verified and enhanced in a proper manner.</li>
                            </ul>'
                ),
            1 =>
                array(
                    'id' => 2,
                    'title' => 'About Us',
                    'page_id' => 'aboutus',
                    'institute_id' => 1,
                    'created_by' => '1',
                    'page_contents' => '<h2 class="section-heading" style="text-align: center;">About Us For BITAC</h2>
                            <p>&nbsp;</p>
                            <p>To conduct and monitor various training courses online with the aim of making the youth self-employed and self-reliant in the implementation of the vision 2021 of the Government of the People\'s Republic of Bangladesh. This web application is basically "Online Course Management System". In this platform, students can easily become self-reliant by training in various training courses. The student will have to request apply to the admin for training on his her own choice. At the end of the training the student will be given a certificate</p>
                            <h2 class="para-heading">Aims / Objectives of the Portalঃ</h2>
                            <ul class="sidebar-list">
                            <li>&nbsp;Students can apply for various training courses on this platform.</li>
                            <li>&nbsp;It is possible to conduct and monitor many courses simultaneously in different categories.</li>
                            <li>&nbsp;Students skills are verified and enhanced in a proper manner.</li>
                            </ul>'
                ),
            3 =>
                array(
                    'id' => 3,
                    'title' => 'About Us',
                    'page_id' => 'aboutus',
                    'institute_id' => 2,
                    'created_by' => '1',
                    'page_contents' => '<h2 class="section-heading" style="text-align: center;">About Us For TFL</h2>
                            <p>&nbsp;</p>
                            <p>To conduct and monitor various training courses online with the aim of making the youth self-employed and self-reliant in the implementation of the vision 2021 of the Government of the People\'s Republic of Bangladesh. This web application is basically "Online Course Management System". In this platform, students can easily become self-reliant by training in various training courses. The student will have to request apply to the admin for training on his her own choice. At the end of the training the student will be given a certificate</p>
                            <h2 class="para-heading">Aims / Objectives of the Portalঃ</h2>
                            <ul class="sidebar-list">
                            <li>&nbsp;Students can apply for various training courses on this platform.</li>
                            <li>&nbsp;It is possible to conduct and monitor many courses simultaneously in different categories.</li>
                            <li>&nbsp;Students skills are verified and enhanced in a proper manner.</li>
                            </ul>'
                ),

        ));
        Schema::enableForeignKeyConstraints();
    }
}
