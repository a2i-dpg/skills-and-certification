<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTraineeFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trainee_feedbacks', function (Blueprint $table) {
            $table->foreign('user_id', 'trainee_feedback_fk_user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('trainee_id', 'trainee_feedback_fk_trainee_id')->references('id')->on('trainees')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('batch_id', 'trainee_feedback_fk_batch_id')->references('id')->on('batches')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trainee_feedbacks', function (Blueprint $table) {
            $table->dropForeign('trainee_feedback_fk_user_id');
            $table->dropForeign('trainee_feedback_fk_trainee_id');
            $table->dropForeign('trainee_feedback_fk_batch_id');
        });
    }
}
