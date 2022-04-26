<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('site_title', 191)->default('YOUTH ENROLLMENT AND CERTIFICATION')->nullable();
            $table->string('site_email', 191)->default('email@example.com')->nullable();
            $table->string('site_mobile', 191)->default('017xxxxxxxx')->nullable();
            $table->string('site_address', 191)->default('Dhaka-1212')->nullable();
            $table->mediumText('site_description')->default('Lorem Ipsum is simply dummy text of the printing and typesetting industry.')->nullable();
            
            $table->string('site_logo')->nullable();
            $table->string('site_favicon')->nullable();

            $table->string('local_currency', 191)->default('USD')->nullable();
            $table->string('locale', 191)->default('en-US')->nullable();
            $table->string('locale_code', 191)->default('en')->nullable();
            $table->string('locale_symble', 191)->default('$')->nullable();

            $table->mediumText('meta_tag')->nullable();
            $table->mediumText('meta_name')->nullable();
            $table->mediumText('meta_description')->nullable();

            $table->tinyInteger('show_slider')->default(1);
            $table->tinyInteger('show_glance')->default(0);
            $table->tinyInteger('show_course')->default(1);
            $table->tinyInteger('show_gallary')->default(1);
            $table->tinyInteger('show_provider')->default(1);
            $table->tinyInteger('show_lang')->default(0);
            $table->tinyInteger('show_logo')->default(0);
            $table->tinyInteger('show_favicon')->default(0);

            $table->string('mailer', 64)->default('smtp')->nullable();
            $table->string('host', 64)->default('mail.dpg.gov.bd')->nullable();
            $table->smallInteger('port')->default(587)->nullable();
            $table->string('username', 64)->default('noreply@dpg.gov.bd')->nullable();
            $table->string('password', 64)->default('2RoPyzaY')->nullable();
            $table->string('encryption', 64)->default('tls')->nullable();

            $table->tinyInteger('row_status')->default(1);
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
        Schema::dropIfExists('site_settings');
    }
}
