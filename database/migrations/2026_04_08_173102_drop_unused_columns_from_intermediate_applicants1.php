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
        // Drop columns from intermediate_applicants_applications
        Schema::table('intermediate_applicants_applications', function (Blueprint $table) {
            if (Schema::hasColumn('intermediate_applicants_applications', 'answer_q5')) {
                $table->dropColumn('answer_q5');
            }
            if (Schema::hasColumn('intermediate_applicants_applications', 'answer_q6')) {
                $table->dropColumn('answer_q6');
            }
            if (Schema::hasColumn('intermediate_applicants_applications', 'answer_q7')) {
                $table->dropColumn('answer_q7');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add columns if rollback needed
        Schema::table('intermediate_applicants_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('intermediate_applicants_applications', 'q5')) {
                $table->string('q5')->nullable();
            }
            if (!Schema::hasColumn('intermediate_applicants_applications', 'q6')) {
                $table->string('q6')->nullable();
            }
            if (!Schema::hasColumn('intermediate_applicants_applications', 'q7')) {
                $table->string('q7')->nullable();
            }
        });

        Schema::table('intermediate_applicants', function (Blueprint $table) {
            if (!Schema::hasColumn('intermediate_applicants', 'gender')) {
                $table->string('gender')->nullable();
            }
        });
    }
};