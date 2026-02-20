<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('module', 80);
            $table->string('activity', 1024);
            $table->string('ip_address', 80)->nullable();

            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable();

            $table->dateTime('create_time')->nullable();
            $table->dateTime('update_time')->nullable();

            // Indexes
            $table->index('module', 'idx_module');
            $table->index('created_by', 'idx_created_by');
            $table->index('create_time', 'idx_create_time');

            // Optional foreign keys
            // $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
