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
        Schema::create('blog', function (Blueprint $table) {
            $table->id();
            $table->string('meta_title')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('meta_description')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('meta_keywords')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('tags')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->string('title')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('detail')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->string('image')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->integer('category')->nullable();
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
        Schema::dropIfExists('blog');
    }
};
