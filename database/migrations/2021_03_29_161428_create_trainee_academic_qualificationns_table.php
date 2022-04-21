<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraineeAcademicQualificationnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainee_academic_qualificationns', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('trainee_id');
            $table->string('examination', 191)->nullable();
            $table->string('examination_name', 191)->nullable();
            $table->string('board', 191)->nullable();
            $table->string('institute', 191)->nullable();
            $table->string('roll_no',20)->nullable();
            $table->string('reg_no',20)->nullable();
            $table->string('grade', 191)->nullable();
            $table->string('result', 191)->nullable();
            $table->string('group', 191)->nullable();
            $table->string('passing_year', 191)->nullable();
            $table->string('subject', 191)->nullable();
            $table->string('course_duration', 191)->nullable();
            $table->timestamps();
            $table->foreign('trainee_id', 'trainee_academic_qualificationns_fk_trainee_id')->references('id')->on('trainees')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trainee_academic_qualificationns');
    }
}
