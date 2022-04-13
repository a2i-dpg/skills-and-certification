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
        /* Institute Menu Permition Seed */
        $permissions = DB::table('permissions')
                        ->select('id as permission_id', DB::raw('3 as role_id'))
                        ->whereNotIn('table_name', ['site_settings','roles','permissions','menus','user_types'])
                        ->get()
                        ->toArray();
        $permissions = json_decode(json_encode($permissions), True);
        // insert 1st way
        DB::table('permission_role')->insert($permissions);
        
        // insert 2nd way
        // foreach ($permissions as $permission) {
        //     DB::table('permission_role')->insert(['permission_id' => $permission['permission_id'],'role_id'=>$permission['role_id']]);
        // }

        /* Institute Menu Permition Seed */

        Schema::enableForeignKeyConstraints();
    }
}
