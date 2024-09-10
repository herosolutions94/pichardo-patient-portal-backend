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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('color_name')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->string('name')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('detail')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->text('image')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->integer('order_no')->nullable(true);
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
        Schema::dropIfExists('services');
    }
};
