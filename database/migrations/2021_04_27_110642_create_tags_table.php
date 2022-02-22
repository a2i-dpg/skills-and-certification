<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('institute_id');
            $table->string('tag', 191);
            $table->string("taggable_type", 191)->index('tags_fk_taggable_type');
            $table->unsignedInteger("taggable_id")->index('tags_fk_taggable_id');
            $table->timestamps();
            $table->foreign('institute_id', 'tags_fk_institute_id')->references('id')->on('institutes')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags');
    }
}
