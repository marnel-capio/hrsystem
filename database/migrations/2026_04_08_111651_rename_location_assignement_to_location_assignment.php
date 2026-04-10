<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameLocationAssignementToLocationAssignment extends Migration
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
        });
    }
}