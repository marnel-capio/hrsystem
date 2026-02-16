<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('users'); // remove any old table

        Schema::create('users', function (Blueprint $table) {
            $table->id(); // BIGINT(20), auto-increment, primary key
            $table->string('last_name', 80);
            $table->string('first_name', 80);
            $table->string('middle_name', 80)->nullable();
            $table->string('address', 1024);
            $table->string('contact_no', 20);
            $table->string('email_address', 80)->unique(); // login email
            $table->string('password'); // keep string type
            $table->string('position', 80);
            $table->string('permissions', 1024);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->dateTime('create_time')->nullable();
            $table->dateTime('update_time')->nullable();

            // Indexes
            $table->index('last_name', 'idx_last_name');
            $table->index('position', 'idx_position');

            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
