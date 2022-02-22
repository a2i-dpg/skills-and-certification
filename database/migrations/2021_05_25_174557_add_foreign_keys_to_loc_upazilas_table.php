<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToLocUpazilasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loc_upazilas', function (Blueprint $table) {
            $table->foreign('loc_district_id')->references('id')->on('loc_districts')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('loc_division_id')->references('id')->on('loc_divisions')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loc_upazilas', function (Blueprint $table) {
            $table->dropForeign('loc_upazilas_loc_district_id_foreign');
            $table->dropForeign('loc_upazilas_loc_division_id_foreign');
        });
    }
}
