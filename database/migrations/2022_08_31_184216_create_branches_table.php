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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->integer('mem_id');
            $table->text('name')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('address')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('year')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('property_type')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('house_style')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('nearest_metro')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('lot_size')->charset('utf8')->collation('utf8_general_ci')->nullable();
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
        Schema::dropIfExists('branches');
    }
};
