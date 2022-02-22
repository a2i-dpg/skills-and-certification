<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTraineeCourseEnrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trainee_course_enrolls', function (Blueprint $table) {
            $table->foreign('trainee_id', 'trainee_course_enrolls_fk_trainee_id')
                ->references('id')
                ->on('trainees')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('course_id', 'trainee_course_enrolls_fk_course_id')
                ->references('id')
                ->on('courses')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->foreign('batch_id', 'trainee_batch_enrolls_fk_batch_id')
                ->references('id')
                ->on('batches')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trainee_course_enrolls', function (Blueprint $table) {
            $table->dropForeign('trainee_course_enrolls_fk_trainee_id');
            $table->dropForeign('trainee_course_enrolls_fk_course_id');
            $table->dropForeign('trainee_batch_enrolls_fk_batch_id');
        });
    }
}
