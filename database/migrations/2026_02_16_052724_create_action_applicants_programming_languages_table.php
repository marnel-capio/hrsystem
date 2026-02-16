<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('action_applicants_programming_languages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('action_applicant_id')->unsigned();
            $table->string('program_language', 80);
            $table->string('remarks', 1024)->nullable();
            $table->bigInteger('created_by')->unsigned();
            $table->dateTime('created_time');
            $table->bigInteger('updated_by')->unsigned();
            $table->dateTime('updated_time');

            // Indexes
            $table->index('created_time', 'idx_created_time');

            // Optional foreign keys
            // $table->foreign('action_applicant_id')->references('id')->on('action_applicants')->onDelete('cascade');
            // $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('action_applicants_programming_languages');
    }
};
