<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('intermediate_applicants_applications', function (Blueprint $table) {
            $table->dropColumn([
                'site_assignment',
                'asking_rate',
                'reason_by_category',
                'reason_for_decline',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('intermediate_applicants_applications', function (Blueprint $table) {
            $table->string('site_assignment', 20)->nullable()->after('current_employer');
            $table->string('asking_rate', 20)->nullable()->after('site_assignment');
            $table->string('reason_by_category', 1024)->nullable()->after('parked_to');
            $table->string('reason_for_decline', 1024)->nullable()->after('reason_by_category');
        });
    }
};