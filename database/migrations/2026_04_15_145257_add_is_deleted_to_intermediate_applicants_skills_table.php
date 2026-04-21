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
        Schema::table('intermediate_applicants_skills', function (Blueprint $table) {
            $table->tinyInteger('is_deleted')
                ->default(0)
                ->comment('0-not deleted,1-deleted')
                ->after('remarks'); // adjust if remarks doesn't exist
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('intermediate_applicants_skills', function (Blueprint $table) {
            $table->dropColumn('is_deleted');
        });
    }
};
