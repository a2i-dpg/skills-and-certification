<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitorFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitor_feedback', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('institute_id');
            $table->string('name', 191);
            $table->string('mobile', 191)->nullable();
            $table->string('email', 191)->nullable();
            $table->string('address', 2000)->nullable();
            $table->text('comment')->nullable();
            $table->unsignedTinyInteger('form_type')->comment('FORM_TYPE_FEEDBACK = 1, FORM_TYPE_CONTACT = 2');
            $table->dateTime('read_at')->nullable();
            $table->tinyInteger('row_status')->default(1)->comment('ACTIVE_STATUS = 1; INACTIVE_STATUS = 0; DELETED_STATUS = 99;');
            $table->timestamps();
            $table->foreign('institute_id', 'visitor_feedback_fk_institute_id')
                ->references('id')
                ->on('institutes')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visitor_feedbacks');
    }
}
