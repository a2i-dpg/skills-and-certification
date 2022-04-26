<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            GeoLocationDatabaseSeeder::class,
            RoleTableSeeder::class,
            RowStatusSeeder::class,
            UserTypeSeeder::class,
            InstituteSeeder::class,
            StaticPageSeeder::class,
            BranchSeeder::class,
            TrainingCenterSeeder::class,
            ProgrammeSeeder::class,
            CourseSeeder::class,
            FAQSeeder::class,
            SiteSettingSeeder::class,
            BatchSeeder::class,
            TraineeSeeder::class,
            SliderSeeder::class,
            GalleryAlbumSeeder::class,
        ]);
    }
}
