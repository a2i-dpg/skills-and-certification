<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institutes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 191)->nullable();
            $table->string('email', 191);
            $table->string('mobile', 191);
            $table->string('address', 191);
            $table->string('office_head_name');
            $table->string('office_head_post');
            $table->string('contact_person_name');
            $table->string('contact_person_post');
            $table->string('contact_person_email');
            $table->string('contact_person_mobile');
            $table->mediumText('description')->nullable();
            $table->string('google_map_src')->nullable();
            $table->string('logo')->nullable();
            $table->tinyInteger('row_status')->default(0);
            $table->string('slug');
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
        Schema::dropIfExists('institutes');
    }
}
