<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('action_application_interviews', function (Blueprint $table) {
            $table->timestamp('pending_approval_notified_at')
                ->nullable()
                ->after('decline_reason');
        });
    }

    public function down(): void
    {
        Schema::table('action_application_interviews', function (Blueprint $table) {
            $table->dropColumn('pending_approval_notified_at');
        });
    }
};