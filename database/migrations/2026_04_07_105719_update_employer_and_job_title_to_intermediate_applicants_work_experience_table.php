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
        Schema::table('intermediate_applicants_work_experiences', function (Blueprint $table) {
            // Change employer and job_title columns to varchar(80)
            $table->string('employer', 80)->nullable()->change();
            $table->string('job_title', 80)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('intermediate_applicants_work_experiences', function (Blueprint $table) {
            // Revert employer and job_title back to varchar(20)
            $table->string('employer', 20)->nullable()->change();
            $table->string('job_title', 20)->nullable()->change();
        });
    }
};