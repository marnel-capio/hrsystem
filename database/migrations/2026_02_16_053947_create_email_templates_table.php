<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->tinyInteger('status')->default(1)->comment('1-active, 0-inactive');
            $table->string('code', 80);
            $table->string('name', 80);
            $table->string('subject', 80);
            $table->string('from', 80);
            $table->string('email_from', 80);
            $table->string('email_body', 2048);

            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable();

            $table->dateTime('create_time')->nullable();
            $table->dateTime('update_time')->nullable();

            // Indexes
            $table->index('name', 'idx_name');

            // Optional foreign keys
            // $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};
