<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TransferProjectDescriptionColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('project_description', 1024)->nullable()->after('project_name');
        });

        Schema::table('resource_requisitions', function (Blueprint $table) {
            $table->dropColumn('project_description');
        });
    }
}