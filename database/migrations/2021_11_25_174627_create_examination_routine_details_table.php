<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExaminationRoutineDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examination_routine_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('institute_id')->index('examination_routine_details_fk_institute_id');
            $table->unsignedInteger('examination_routine_id')->index('examination_routine_details_fk_examination_routine_id');
            $table->unsignedInteger('examination_id')->index('examination_routine_details_fk_examination_id');
            //$table->string('class',256)->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->tinyInteger('row_status')->default(1);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('examination_routine_details');
    }
}
