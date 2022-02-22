<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraineeCourseEnrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainee_course_enrolls', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('trainee_id')->index('trainee_course_enrolls_fk_trainee_id');
            $table->unsignedInteger('course_id')->nullable()->index('trainee_course_enrolls_fk_course_id');
            $table->unsignedInteger('batch_id')->nullable()->index('trainee_batch_enrolls_fk_batch_id');
            $table->unsignedTinyInteger('enroll_status')->nullable()->default(0)->comment('0 => Processing  1 => Accept 2 => Reject');
            $table->unsignedTinyInteger('payment_status')->nullable()->default(0)->comment('0 => Unpaid  1 => Paid');
            $table->json('batch_preferences')->nullable();
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
        Schema::dropIfExists('trainee_course_enrolls');
    }
}
