<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraineeAcademicQualificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainee_academic_qualifications', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('trainee_id');
            $table->string('examination', 191);
            $table->string('examination_name', 191);
            $table->unsignedTinyInteger('board')->nullable();
            $table->string('institute', 191)->nullable();
            $table->string('roll_no',20)->nullable();
            $table->string('reg_no',20)->nullable();
            $table->unsignedFloat('grade', 3,2)->nullable();
            $table->unsignedTinyInteger('result');
            $table->unsignedTinyInteger('group')->nullable();
            $table->string('passing_year', 4);
            $table->string('subject', 191)->nullable();
            $table->unsignedTinyInteger('course_duration')->nullable();
            $table->timestamps();
            $table->foreign('trainee_id', 'trainee_academic_qualifications_fk_trainee_id')->references('id')->on('trainees')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trainee_academic_qualifications');
    }
}
