<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_histories', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->tinyInteger('status')->comment('1-sent, 0-failed');
            $table->string('email_code', 80);
            $table->string('subject', 80);
            $table->string('from', 80);
            $table->string('email_from', 80);
            $table->string('email_to', 80);
            $table->string('email_body', 2048);

            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable();

            $table->dateTime('create_time')->nullable();
            $table->dateTime('update_time')->nullable();

            // Indexes
            $table->index('create_time', 'idx_create_time');

            // Optional foreign keys
            // $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_histories');
    }
};
