<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraineeFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainee_feedbacks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('batch_id')->index('trainee_feedback_fk_batch_id');
            $table->unsignedInteger('user_id')->index('trainee_feedback_fk_user_id');
            $table->unsignedInteger('trainee_id')->index('trainee_feedback_fk_trainee_id');
            $table->text('feedback')->nullable();
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
        Schema::dropIfExists('trainee_feedbacks');
    }
}
