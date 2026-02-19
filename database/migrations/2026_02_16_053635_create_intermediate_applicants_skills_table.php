<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intermediate_applicants_skills', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('intermediate_applicant_id')->unsigned();
            $table->string('skill', 20);
            $table->string('remarks', 1024)->nullable();

            $table->bigInteger('created_by')->unsigned();
            $table->dateTime('created_time');
            $table->bigInteger('updated_by')->unsigned();
            $table->dateTime('updated_time');

            // Optional foreign keys
            // $table->foreign('intermediate_applicant_id')->references('id')->on('intermediate_applicants')->onDelete('cascade');
            // $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intermediate_applicants_skills');
    }
};
