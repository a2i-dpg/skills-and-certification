<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificateTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('institute_id');
            $table->string('title', 191)->default('Certificate Template')->nullable();
            $table->string('image',191)->nullable();
            $table->unsignedTinyInteger('row_status')->default(1);
            $table->integer('created_by')->nullable();
            $table->timestamps();
            $table->foreign('institute_id', 'certificate_templates_fk_institute_id')
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
        Schema::dropIfExists('certificate_templates');
    }
}
