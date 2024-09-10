<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->id();
            $table->string('site_username');
            $table->string('site_password');
            $table->string('site_admin_name')->nullable(true);
            $table->string('site_admin_type')->nullable(true);
            $table->string('site_domain')->nullable(true);
            $table->string('site_name')->nullable(true);
            $table->string('site_email')->nullable(true);
            $table->string('site_noreply_email')->nullable(true);
            $table->string('site_phone')->nullable(true);
            $table->string('site_fax')->nullable(true);
            $table->string('site_logo')->nullable(true);
            $table->string('site_icon')->nullable(true);
            $table->string('site_thumb')->nullable(true);
            $table->string('site_address')->nullable(true);
            $table->text('site_about')->nullable(true);
            $table->string('site_copyright')->nullable(true);
            $table->string('site_facebook')->nullable(true);
            $table->string('site_twitter')->nullable(true);
            $table->string('site_google')->nullable(true);
            $table->string('site_instagram')->nullable(true);
            $table->string('site_linkedin')->nullable(true);
            $table->string('site_youtube')->nullable(true);
            $table->string('site_discord')->nullable(true);
            $table->string('site_contact_map')->nullable(true);
            $table->string('site_meta_desc')->nullable(true);
            $table->string('site_meta_keyword')->nullable(true);
            $table->string('site_meta_author')->nullable(true);
            $table->integer('site_version')->nullable(true);
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
        Schema::dropIfExists('admin');
    }
};
