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
        Schema::create('tbl_multi_text', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('detail')->nullable(true);
            $table->text('txt1')->nullable(true);
            $table->text('txt2')->nullable(true);
            $table->text('txt3')->nullable(true);
            $table->text('txt4')->nullable(true);
            $table->text('txt5')->nullable(true);
            $table->text('image')->nullable(true);
            $table->text('section')->nullable(true);
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
        Schema::dropIfExists('tbl_multi_text');
    }
};
