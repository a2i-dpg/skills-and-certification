<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('user_type_id', 'users_fk_user_type_id')->references('id')->on('user_types')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('institute_id', 'users_fk_institute_id')->references('id')->on('institutes')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('loc_district_id', 'users_fk_loc_district_id')->references('id')->on('loc_districts')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('loc_division_id', 'users_fk_loc_division_id')->references('id')->on('loc_divisions')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_fk_user_type_id');
            $table->dropForeign('users_fk_institute_id');
            $table->dropForeign('users_fk_loc_district_id');
        });
    }
}
