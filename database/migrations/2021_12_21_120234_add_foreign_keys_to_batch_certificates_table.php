<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBatchCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('batch_certificates', function (Blueprint $table) {
            $table->foreign('batch_id', 'batches_fk_batch_id')
                ->references('id')
                ->on('batches')
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
        Schema::table('batch_certificates', function (Blueprint $table) {
            $table->dropForeign('batches_fk_batch_id');
            $table->dropForeign('batches_fk_batch_id');
        });
    }
}
