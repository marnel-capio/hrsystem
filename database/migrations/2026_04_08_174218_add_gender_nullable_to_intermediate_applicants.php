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
            if (!Schema::hasColumn('intermediate_applicants', 'gender')) {
                $table->string('gender')->nullable()->after('birthdate'); // place after birthdate column
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('intermediate_applicants', function (Blueprint $table) {
            if (Schema::hasColumn('intermediate_applicants', 'gender')) {
                $table->dropColumn('gender');
            }
        });
    }
};