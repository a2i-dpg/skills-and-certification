<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('headers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('institute_id')->nullable();
            $table->string('title', 191);
            $table->string('url', 191)->nullable();
            $table->string('target', 10)->nullable()->default('_self');
            $table->unsignedTinyInteger('order')->nullable();
            $table->string('route', 191)->nullable();
            $table->tinyInteger('row_status')->default(0);
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
        Schema::dropIfExists('headers');
    }
}
