<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificate_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('trainee_batch_id')->index('trainee_batches_fk_trainee_batch_id');
            $table->unsignedInteger('trainee_id')->index('trainee_batches_fk_trainee_id');
            $table->unsignedInteger('trainee_course_enrolls_id');
            $table->date('date_of_birth');
            $table->string('name', 191);
            $table->string('father_name', 191);
            $table->string('mother_name', 191);
            $table->string('id_image', 191);
            $table->text('comment')->nullable();
            $table->unsignedTinyInteger('row_status')->nullable()->default(1);
            $table->unsignedTinyInteger('id_prof_type')->nullable()->default(1);
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
        Schema::dropIfExists('certificate_requests');
    }
}
