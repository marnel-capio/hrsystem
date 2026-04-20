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
        Schema::table('intermediate_applicants', function (Blueprint $table) {
            // Change the course column from varchar(10) to varchar(80)
            $table->string('course', 80)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('intermediate_applicants', function (Blueprint $table) {
            // Revert the course column back to varchar(10)
            $table->string('course', 10)->nullable()->change();
        });
    }
};