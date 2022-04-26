<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoleWisePermissionSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('permission_role')->truncate();

        /* System Admin Menu Permition Seed */
        $permissions = DB::table('permissions')
                        ->select('id as permission_id', DB::raw('2 as role_id'))
                        //->whereNotIn('table_name', ['site_settings','roles','permissions','menus','user_types'])
                        ->get()
                        ->toArray();
        $permissions = json_decode(json_encode($permissions), True);

        DB::table('permission_role')->insert($permissions);
        //SELECT * FROM `permission_role` WHERE permission_id = 395 and role_id = 5;
        //DB::table('permission_role')->where(['permission_id' => 395, 'role_id' => 2])->delete();
        DB::table('permission_role')->whereIn('permission_id', [373,395])->where(['role_id' => 3])->delete();
        /* System Admin Menu Permition Seed */


        /* Institute Admin Menu Permition Seed */
        $permissions = DB::table('permissions')
                        ->select('id as permission_id', DB::raw('3 as role_id'))
                        ->whereNotIn('table_name', ['site_settings','roles','permissions','menus','user_types'])
                        ->get()
                        ->toArray();
        $permissions = json_decode(json_encode($permissions), True);

        DB::table('permission_role')->insert($permissions);
        DB::table('permission_role')->whereIn('permission_id', [373,395])->where(['role_id' => 3])->delete();
        /* Institute Admin Menu Permition Seed */

        /* Branch Admin Menu Permition Seed */
        $permissions = DB::table('permissions')
                        ->select('id as permission_id', DB::raw('5 as role_id'))
                        ->whereNotIn('table_name', ['site_settings','roles','permissions','menus','user_types',
                        'intro_videos','sliders','gallery_categories','galleries','video_categories','videos','static_pages','question_answers',
                        'institutes'
                        ])
                        ->get()
                        ->toArray();
        $permissions = json_decode(json_encode($permissions), True);
        DB::table('permission_role')->insert($permissions);
        DB::table('permission_role')->whereIn('permission_id', [17,18,19,373,395])->where(['role_id' => 5])->delete();
        /* Branch Admin Menu Permition Seed */

        /* Trainig Center Admin Menu Permition Seed */
        $permissions = DB::table('permissions')
                        ->select('id as permission_id', DB::raw('4 as role_id'))
                        ->whereNotIn('table_name', ['site_settings','roles','permissions','menus','user_types',
                        'intro_videos','sliders','gallery_categories','galleries','video_categories','videos','static_pages','question_answers',
                        'institutes','branches'
                        ])
                        ->get()
                        ->toArray();
        $permissions = json_decode(json_encode($permissions), True);
        DB::table('permission_role')->insert($permissions);
        DB::table('permission_role')->whereIn('permission_id', [360,361,362,373,395])->where(['role_id' => 4])->delete();
        /* Trainig Center Admin Menu Permition Seed */


        /* Trainer Menu Permition Seed */
        $permissions = DB::table('permissions')
                        ->select('id as permission_id', DB::raw('6 as role_id'))
                        ->whereNotIn('table_name', ['site_settings','roles','permissions','menus','user_types','users',
                        'intro_videos','sliders','gallery_categories','galleries','video_categories','videos','static_pages','question_answers',
                        'institutes','branches','training_centers','programmes','courses','batches','batch_certificates','certificates',
                        'certificate_requests','certificate_templates','trainee_certificates','routine_slots','routines','visitor_feedback'
                        ])
                        ->get()
                        ->toArray();
        $permissions = json_decode(json_encode($permissions), True);
        DB::table('permission_role')->insert($permissions);
        DB::table('permission_role')->whereIn('permission_id', [75,82,88,])->where(['role_id' => 6])->delete();
        /* Trainer Menu Permition Seed */

        Schema::enableForeignKeyConstraints();
    }
}
