<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('intermediate_applicants_applications', function (Blueprint $table) {
            $table->string('availability_date', 80)->nullable()->change();
            $table->string('work_preference', 80)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('intermediate_applicants_applications', function (Blueprint $table) {
            $table->dateTime('availability_date')->nullable()->change();
            $table->string('work_preference', 20)->nullable()->change();
        });
    }
};