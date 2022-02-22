<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTraineeCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trainee_certificates', function (Blueprint $table) {
//            $table->foreign('batch_certificate_id', 'batch_certificates_fk_batch_certificate_id')
//                ->references('id')
//                ->on('batch_certificates')
//                ->onUpdate('CASCADE')
//                ->onDelete('RESTRICT');
//            $table->foreign('batch_id', 'batches_fk_batch_id')
//                ->references('id')
//                ->on('batches')
//                ->onUpdate('CASCADE')
//                ->onDelete('RESTRICT');
//            $table->foreign('trainee_id', 'trainees_fk_trainee_id')
//                ->references('id')
//                ->on('trainees')
//                ->onUpdate('CASCADE')
//                ->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trainee_certificates', function (Blueprint $table) {
//            $table->dropForeign('batch_certificates_fk_batch_certificate_id');
//            $table->dropForeign('batch_certificates_fk_batch_certificate_id');
//            $table->dropForeign('batches_fk_batch_id');
//            $table->dropForeign('batches_fk_batch_id');
//            $table->dropForeign('trainees_fk_trainee_id');
//            $table->dropForeign('trainees_fk_trainee_id');
        });
    }
}
