<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCertificateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certificate_requests', function (Blueprint $table) {
            $table->foreign('trainee_batch_id', 'trainee_batches_fk_trainee_batch_id')
                ->references('id')
                ->on('trainee_batches')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
        });
        Schema::table('certificate_requests', function (Blueprint $table) {
            $table->foreign('trainee_id', 'trainee_batches_fk_trainee_id')
                ->references('id')
                ->on('trainees')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certificate_requests', function (Blueprint $table) {
            $table->dropForeign('trainee_batches_fk_trainee_batch_id');
            $table->dropForeign('trainee_batches_fk_trainee_batch_id');

            $table->dropForeign('trainee_batches_fk_trainee_id');
            $table->dropForeign('trainee_batches_fk_trainee_id');
        });
    }
}
