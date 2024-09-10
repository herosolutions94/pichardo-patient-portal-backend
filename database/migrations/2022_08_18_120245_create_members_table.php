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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('mem_fname')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->string('mem_mname')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->string('mem_lname')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->string('mem_email')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->string('mem_phone')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('mem_password')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('mem_dob')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('mem_address1')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('mem_address2')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->string('mem_city')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->integer('mem_state')->nullable();
            $table->string('mem_zip')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->string('mem_bio')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->string('mem_image')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->enum('mem_type', ['member', 'company'])->default('member');
            $table->tinyInteger('mem_status')->default(0);
            $table->tinyInteger('mem_verified')->default(0);
            $table->tinyInteger('mem_email_verified')->default(0);
            $table->tinyInteger('mem_phone_verified')->default(0);
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
        Schema::dropIfExists('members');
    }
};
