<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameLocationAndAddSalaryColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('resource_requisitions', function (Blueprint $table) {
            // Rename the 'location_assignement' column to 'location_assignment'
            $table->renameColumn('location_assignement', 'location_assignment');

            // Rename the 'preferred_skilss' column to 'preferred_skills'
            $table->renameColumn('preferred_skilss', 'preferred_skills');
            
            // Add the 'expected_salary_range' column after the 'role' column
            $table->string('expected_salary_range', 1024)->nullable()->after('role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('resource_requisitions', function (Blueprint $table) {
            // Rename the columns back to their original names
            $table->renameColumn('location_assignment', 'location_assignement');
            $table->renameColumn('preferred_skills', 'preferred_skilss');
            
            // Drop the 'expected_salary_range' column
            $table->dropColumn('expected_salary_range');
        });
    }
}