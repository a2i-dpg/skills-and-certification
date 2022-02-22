<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('trainee_course_enroll_id');
            $table->text('transaction_id')->nullable();
            $table->string('amount', 255)->nullable();
            $table->text('log')->nullable();
            $table->string('payment_type')->nullable();
            $table->dateTime('payment_date');
            $table->unsignedTinyInteger('payment_status')->nullable()->comment('1 => Success  2 => Pending 3 => Canceled');
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
        Schema::dropIfExists('payments');
    }
}
