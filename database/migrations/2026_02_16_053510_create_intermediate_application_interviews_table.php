<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intermediate_application_interviews', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('interviewer_id')->unsigned()->comment('user id');
            $table->bigInteger('intermediate_application_id')->unsigned()->comment('intermediate application id');
            $table->tinyInteger('department')->nullable()->comment('*ask list of department');

            $table->string('positive_feedback', 1024)->nullable();
            $table->string('negative_feedback', 1024)->nullable();
            $table->tinyInteger('results')->nullable()->comment("1-Pending,2-Done,3-Passed,4-P2,5-Failed");
            $table->string('remarks', 1024)->nullable();

            // Indexes
            $table->index('interviewer_id', 'idx_interviewer_id');
            $table->index('intermediate_application_id', 'idx_intermediate_application_id');
            $table->index('department', 'idx_department');

            // Optional foreign keys
            // $table->foreign('interviewer_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('intermediate_application_id')->references('id')->on('intermediate_applicants_applications')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intermediate_application_interviews');
    }
};
