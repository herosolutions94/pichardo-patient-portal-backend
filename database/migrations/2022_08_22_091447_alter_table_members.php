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
        Schema::table('members', function (Blueprint $table) {
            $table->string('mem_fullname')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->string('mem_business')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->string('mem_domain_name')->charset('utf8')->collation('utf8_general_ci')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
