<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomLocationAndSalaryRangeToResourceRequisitions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('resource_requisitions', function (Blueprint $table) {
            // Add the custom_location column
            $table->string('custom_location', 255)->nullable()->after('role');
            
            // Add the expected_salary_range column
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
            // Drop the custom_location column
            $table->dropColumn('custom_location');
            
            // Drop the expected_salary_range column
            $table->dropColumn('expected_salary_range');
        });
    }
}