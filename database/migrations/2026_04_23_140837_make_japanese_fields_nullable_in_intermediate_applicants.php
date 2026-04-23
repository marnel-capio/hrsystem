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
        Schema::table('intermediate_applicants', function (Blueprint $table) {
            $table->string('japanese_background')->nullable()->change();
            $table->string('japanese_level')->nullable()->change();
            $table->text('background_remarks')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('intermediate_applicants', function (Blueprint $table) {
            $table->string('japanese_background')->nullable(false)->change();
            $table->string('japanese_level')->nullable(false)->change();
            $table->text('background_remarks')->nullable(false)->change();
        });
    }
};
