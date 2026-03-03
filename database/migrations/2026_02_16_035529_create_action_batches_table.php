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
        Schema::create('action_batches', function (Blueprint $table) {
            $table->id(); // BIGINT(20) auto-increment, primary key
            $table->string('action_batch', 20); // VARCHAR(20) NOT NULL
            $table->tinyInteger('target_trainees'); //added, initially from resource_schedules table
            $table->tinyInteger('status')->default(1); // TINYINT(1) NOT NULL, default 1
            $table->string('remarks', 1024)->nullable(); // VARCHAR(1024), nullable
            $table->unsignedBigInteger('created_by'); // BIGINT(20) NOT NULL
            $table->dateTime('created_time'); // DATETIME NOT NULL
            $table->unsignedBigInteger('updated_by'); // BIGINT(20) NOT NULL
            $table->dateTime('updated_time'); // DATETIME NOT NULL

            // Optional: foreign keys to users table
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('action_batches');
    }
};
