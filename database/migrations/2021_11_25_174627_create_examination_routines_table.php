<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExaminationRoutinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examination_routines', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('institute_id')->index('examination_routines_fk_institute_id');
            $table->unsignedInteger('batch_id')->index('examination_routines_fk_batch_id');
            $table->unsignedInteger('training_center_id')->index('examination_routines_fk_training_center_id');
            //$table->string('day', 124)->nullable();
            $table->date('date')->nullable();
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
        Schema::dropIfExists('examination_routines');
    }
}
