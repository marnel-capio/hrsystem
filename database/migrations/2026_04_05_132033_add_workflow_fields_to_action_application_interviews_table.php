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
        Schema::table('action_application_interviews', function (Blueprint $table) {
            $table->dateTime('scheduled_date')->nullable()->after('interview_type');
            $table->dateTime('actual_date')->nullable()->after('scheduled_date');

            // 1 = Pending, 2 = Accepted, 3 = Declined
            $table->tinyInteger('status')->default(1)->after('actual_date');

            $table->string('decline_reason', 1024)->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('action_application_interviews', function (Blueprint $table) {
            $table->dropColumn([
                'scheduled_date',
                'actual_date',
                'status',
                'decline_reason',
            ]);
        });
    }
};