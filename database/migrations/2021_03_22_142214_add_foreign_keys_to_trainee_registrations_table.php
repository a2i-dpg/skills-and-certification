<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTraineeRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trainee_registrations', function (Blueprint $table) {
            $table->foreign('course_id', 'trainee_registrations_fk_course_id')->references('id')->on('courses')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('trainee_id', 'trainee_registrations_fk_trainee_id')
                ->references('id')
                ->on('trainees')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trainee_registrations', function (Blueprint $table) {
            $table->dropForeign('trainee_registrations_fk_course_id');
            $table->dropForeign('trainee_registrations_fk_trainee_id');
        });
    }
}
