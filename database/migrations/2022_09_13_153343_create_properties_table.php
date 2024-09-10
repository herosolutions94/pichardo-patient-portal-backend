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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->integer('mem_id');
            $table->enum('type', ['single', 'bulk'])->default('single');
            $table->integer('branch');
            $table->integer('floor_plan');
            $table->text('address_line1')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('address_line2')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('city')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->integer('state');
            $table->text('zip_code')->charset('utf8')->collation('utf8_general_ci')->nullable();
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
        Schema::dropIfExists('properties');
    }
};
