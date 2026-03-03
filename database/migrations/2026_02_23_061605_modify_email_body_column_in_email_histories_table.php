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
        Schema::table('email_histories', function (Blueprint $table) {
            // Change email_body to TEXT (supports much more than 4096 chars)
            $table->text('email_body')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_histories', function (Blueprint $table) {
            // Revert back to VARCHAR(255) if needed
            $table->string('email_body', 255)->change();
        });
    }
};