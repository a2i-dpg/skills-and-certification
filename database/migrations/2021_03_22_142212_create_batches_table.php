<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('institute_id')->index('batches_fk_institute_id');
            $table->unsignedInteger('branch_id')->nullable()->index('batches_fk_branch_id');
            $table->unsignedInteger('course_id')->index('batches_fk_course_id');
            $table->unsignedInteger('training_center_id')->index('batches_fk_training_id');
            $table->string('title', 191);
            $table->string('code', 191);
            $table->dateTime('application_start_date');
            $table->dateTime('application_end_date');
            $table->dateTime('batch_start_date');
            $table->dateTime('batch_end_date')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedTinyInteger("batch_status")->nullable()->default(1)->comment('1 => Open for registration, 2 => On Going, 3 => Completed');
            $table->unsignedTinyInteger('row_status')->nullable()->default(1);
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
        Schema::dropIfExists('batches');
    }
}
