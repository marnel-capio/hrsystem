<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('intermediate_applicants_skills', function (Blueprint $table) {
            $table->string('skill', 80)->change(); // ✅ Change to VARCHAR(80)
        });
    }

    public function down(): void
    {
        Schema::table('intermediate_applicants_skills', function (Blueprint $table) {
            $table->string('skill', 20)->change(); // Revert back
        });
    }
};