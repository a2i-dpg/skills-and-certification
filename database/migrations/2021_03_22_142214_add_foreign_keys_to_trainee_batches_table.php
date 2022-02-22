<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTraineeBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trainee_batches', function (Blueprint $table) {
            $table->foreign('batch_id', 'trainee_batches_fk_batch_id')->references('id')->on('batches')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trainee_batches', function (Blueprint $table) {
            $table->dropForeign('trainee_batches_fk_batch_id');
            $table->dropForeign('trainee_batches_fk_trainee_id');
            $table->dropForeign('trainee_batches_fk_trainee_registration_id');
        });
    }
}
