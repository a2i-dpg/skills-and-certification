<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraineeBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainee_batches', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('batch_id')->index('trainee_batches_fk_batch_id');
            $table->unsignedInteger('trainee_course_enroll_id');
            $table->date('enrollment_date');
            $table->unsignedTinyInteger('enrollment_status');
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
            $table->unsignedTinyInteger('row_status')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trainee_batches');
    }
}
