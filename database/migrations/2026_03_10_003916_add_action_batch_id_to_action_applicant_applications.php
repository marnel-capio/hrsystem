<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('action_applicant_applications', function (Blueprint $table) {
            $table->bigInteger('action_batch_id')
                  ->unsigned()
                  ->after('action_applicant_id')
                  ->comment('Links to action_batches');
            
            $table->index('action_batch_id', 'idx_action_batch_id');
        });
    }

    public function down(): void
    {
        Schema::table('action_applicant_applications', function (Blueprint $table) {
            $table->dropColumn('action_batch_id');
        });
    }
};