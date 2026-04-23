<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resource_requisitions', function (Blueprint $table) {

            // Rename columns
            if (Schema::hasColumn('resource_requisitions', 'location_assignement')) {
                $table->renameColumn('location_assignement', 'location_assignment');
            }

            if (Schema::hasColumn('resource_requisitions', 'preferred_skilss')) {
                $table->renameColumn('preferred_skilss', 'preferred_skills');
            }

            // Add new columns
            if (!Schema::hasColumn('resource_requisitions', 'custom_location')) {
                $table->string('custom_location', 1024)->nullable()->after('location_assignment');
            }

            if (!Schema::hasColumn('resource_requisitions', 'expected_salary_range')) {
                $table->string('expected_salary_range', 1024)->nullable()->after('custom_location');
            }
        });
    }

    public function down(): void
    {
        Schema::table('resource_requisitions', function (Blueprint $table) {

            // Remove added columns
            if (Schema::hasColumn('resource_requisitions', 'custom_location')) {
                $table->dropColumn('custom_location');
            }

            if (Schema::hasColumn('resource_requisitions', 'expected_salary_range')) {
                $table->dropColumn('expected_salary_range');
            }

            // Revert renames
            if (Schema::hasColumn('resource_requisitions', 'location_assignment')) {
                $table->renameColumn('location_assignment', 'location_assignement');
            }

            if (Schema::hasColumn('resource_requisitions', 'preferred_skills')) {
                $table->renameColumn('preferred_skills', 'preferred_skilss');
            }
        });
    }
};