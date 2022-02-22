<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->foreign('institute_id', 'courses_fk_institute_id')->references('id')->on('institutes')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('programme_id', 'courses_fk_programme_id')->references('id')->on('programmes')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign('courses_fk_institute_id');
            $table->dropForeign('courses_fk_programme_id');
        });
    }
}
