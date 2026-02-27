<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTargetColumnsToActionBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('action_batches', function (Blueprint $table) {
            $table->string('target_trainees')->nullable()->after('action_batch');
            $table->date('target_date')->nullable()->after('target_trainees');
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