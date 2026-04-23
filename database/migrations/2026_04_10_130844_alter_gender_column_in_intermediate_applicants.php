<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('intermediate_applicants', function (Blueprint $table) {
            $table->tinyInteger('gender')->nullable()->change();
            // 1 = Male, 2 = Female, NULL = not specified
        });
    }

    public function down(): void
    {
        Schema::table('intermediate_applicants', function (Blueprint $table) {
            $table->tinyInteger('gender')->nullable(false)->change();
        });
    }
};