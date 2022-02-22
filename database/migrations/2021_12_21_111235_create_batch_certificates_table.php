<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_certificates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('batch_id')->index('batches_fk_batch_id');
            $table->string('tamplate', 191);
            $table->string('signature', 191);
            $table->string('authorized_by', 191)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->dateTime('issued_date');
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
        Schema::dropIfExists('batch_certificates');
    }
}
