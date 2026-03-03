<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTargetColumnsToActionBatches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('action_batches', function (Blueprint $table) {
            $table->tinyInteger('target_trainees')->after('action_batch'); // Placed after 'action_batch'
            $table->string('target_date', 10)->after('target_trainees'); // Placed after 'target_trainees'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('action_batches', function (Blueprint $table) {
            $table->dropColumn(['target_trainees', 'target_date']);
        });
    }
}