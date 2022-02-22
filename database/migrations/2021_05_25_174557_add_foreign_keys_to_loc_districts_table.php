<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToLocDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loc_districts', function (Blueprint $table) {
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
        Schema::table('loc_districts', function (Blueprint $table) {
            $table->dropForeign('loc_districts_loc_division_id_foreign');
        });
    }
}
