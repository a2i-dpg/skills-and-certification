<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainerExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainer_experiences', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('trainer_id')->index('trainer_experiences_fk_trainer_id');
            $table->string('organization_name');
            $table->string('position');
            $table->dateTime('job_start_date');
            $table->dateTime('job_end_date')->nullable();
            $table->tinyInteger('current_working_status')->nullable()->default(0);
            $table->timestamps();
            $table->foreign('trainer_id', 'trainer_experiences_fk_trainer_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trainer_experiences');
    }
}
