<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyResourceRequisitionsAndRemoveRemarksFromProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Modify columns in the `resource_requisitions` table to be NOT NULL
        Schema::table('resource_requisitions', function (Blueprint $table) {
            $table->string('resource', 1024)->nullable(false)->change();
            $table->string('no_resources_needed', 20)->nullable(false)->change();
            $table->string('required_skills', 1024)->nullable(false)->change();
        });

        // Drop the `remarks` column from the `projects` table
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('remarks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Reverse the `resource_requisitions` table changes (make columns nullable)
        Schema::table('resource_requisitions', function (Blueprint $table) {
            $table->string('resource', 1024)->nullable()->change();
            $table->string('no_resources_needed', 20)->nullable()->change();
            $table->string('required_skills', 1024)->nullable()->change();
        });

        // Add back the `remarks` column to the `projects` table
        Schema::table('projects', function (Blueprint $table) {
            $table->string('remarks', 1024)->nullable()->after('project_description');
        });
    }
}