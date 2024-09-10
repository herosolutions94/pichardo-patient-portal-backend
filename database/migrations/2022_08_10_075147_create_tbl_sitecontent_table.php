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
        Schema::create('tbl_sitecontent', function (Blueprint $table) {
            $table->id();
            $table->string('ckey');
            $table->string('code')->nullable(true);
            $table->string('full_code')->nullable(true);
            $table->string('site_lang')->nullable(true);
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
        Schema::dropIfExists('tbl_sitecontent');
    }
};
