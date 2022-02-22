<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainerBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainer_batches', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('batch_id')->index('trainer_batches_fk_batch_id');
            $table->unsignedInteger('user_id')->index('trainer_batches_fk_user_id');
            $table->tinyInteger('row_status')->default(1);
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('trainer_batches');
    }
}
