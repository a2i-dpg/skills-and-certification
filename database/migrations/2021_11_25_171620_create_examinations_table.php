<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExaminationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examinations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('institute_id')->index('examinations_fk_institute_id');
            $table->unsignedInteger('batch_id')->index('examinations_fk_batch_id');
            $table->unsignedInteger('training_center_id')->index('examinations_fk_training_center_id');
            $table->unsignedInteger('examination_type_id')->index('examinations_fk_examination_type_id');
            $table->unsignedInteger('user_id')->comment('trainer id')->index('examination_results_fk_user_id');
            $table->string('code',32)->nullable();
            $table->unsignedInteger('pass_mark');
            $table->unsignedInteger('total_mark');
            $table->mediumText('exam_details')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0 = not publish, 1 = publish, 2 = complete');
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
        Schema::dropIfExists('examinations');
    }
}
