<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('action_applicants_skills', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('action_applicant_id');
            $table->string('skill', 20);
            $table->string('remarks', 1024)->nullable();

            $table->unsignedBigInteger('created_by');
            $table->dateTime('created_time');
            $table->unsignedBigInteger('updated_by');
            $table->dateTime('updated_time');

            // Foreign Keys
            $table->foreign('action_applicant_id')
                ->references('id')
                ->on('action_applicants')
                ->onDelete('cascade');

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('restrict');

            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->onDelete('restrict');

            // Optional index (recommended for performance)
            $table->index('action_applicant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('action_applicants_skills');
    }
};
