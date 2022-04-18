<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExaminationResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examination_results', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('course_id')->index('examination_results_fk_course_id');
            $table->unsignedInteger('batch_id')->index('examination_results_fk_batch_id');
            $table->unsignedInteger('examination_id')->index('examination_results_fk_examination_id');
            $table->unsignedInteger('trainee_id')->index('examination_results_fk_trainee_id');
            $table->unsignedInteger('achieved_marks');
            $table->string('feedback')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('examination_results');
    }
}
