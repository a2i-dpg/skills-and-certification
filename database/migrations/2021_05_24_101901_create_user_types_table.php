<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_types', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('title', 191);
            $table->string('code', 191);
            $table->unsignedTinyInteger('parent_id')->nullable();
            $table->unsignedSmallInteger('default_role_id')->nullable();
            $table->unsignedTinyInteger('row_status');

            $table->foreign('parent_id')->references('id')->on('user_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_types');
    }
}
