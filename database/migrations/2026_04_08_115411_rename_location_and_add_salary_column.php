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
            $table->renameColumn('location_assignement', 'location_assignment');

            $table->renameColumn('preferred_skilss', 'preferred_skills');
            
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
            $table->renameColumn('location_assignment', 'location_assignement');
            $table->renameColumn('preferred_skills', 'preferred_skilss');
            
            $table->dropColumn('expected_salary_range');
        });
    }
}