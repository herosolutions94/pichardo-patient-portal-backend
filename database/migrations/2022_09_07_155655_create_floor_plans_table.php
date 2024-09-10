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
        Schema::create('floor_plans', function (Blueprint $table) {
            $table->id();
            $table->integer('mem_id');
            $table->integer('branch');
            $table->text('floor_plan')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('sq_feet')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('studio')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('bedrooms')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('full_bathrooms')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('half_bathrooms')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('air_conditioning')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('heating')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('flooring')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('counter_top')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('laundry')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('imageThumbnail')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('floor_plans');
    }
};
