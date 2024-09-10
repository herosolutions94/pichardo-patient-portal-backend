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
        Schema::create('floor_plans_images', function (Blueprint $table) {
            $table->id();
            $table->integer('f_id');
            $table->text('label')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('image')->charset('utf8')->collation('utf8_general_ci')->nullable();
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
        Schema::dropIfExists('floor_plans_images');
    }
};
