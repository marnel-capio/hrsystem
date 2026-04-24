<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('intermediate_application_interviews', function (Blueprint $table) {

            // ❌ DROP COLUMNS
            $table->dropColumn([
                'department',
                'positive_feedback',
                'negative_feedback',
            ]);

            // 🔁 RENAME COLUMNS
            $table->renameColumn('results', 'evaluation_results');
            $table->renameColumn('remarks', 'evaluation_remarks');

            // ➕ ADD NEW COLUMNS
            $table->tinyInteger('interview_type')->nullable()->after('intermediate_application_id');

            $table->dateTime('scheduled_date')->nullable()->after('interview_type');

            $table->tinyInteger('interview_status')
                ->default(1)
                ->after('scheduled_date');

            $table->string('decline_reason', 1024)->nullable()->after('interview_status');

            $table->timestamp('pending_approval_notified_at')->nullable()->after('decline_reason');

            $table->decimal('evaluation_score', 8, 2)->nullable()->after('pending_approval_notified_at');

            $table->unsignedBigInteger('created_by')->nullable()->after('evaluation_remarks');

            $table->dateTime('created_time')->nullable()->after('created_by');

            $table->unsignedBigInteger('updated_by')->nullable()->after('created_time');

            $table->dateTime('updated_time')->nullable()->after('updated_by');
        });
    }

    public function down(): void
    {
        Schema::table('intermediate_application_interviews', function (Blueprint $table) {

            // ❌ REMOVE NEW COLUMNS
            $table->dropColumn([
                'interview_type',
                'scheduled_date',
                'interview_status',
                'decline_reason',
                'pending_approval_notified_at',
                'evaluation_score',
                'created_by',
                'created_time',
                'updated_by',
                'updated_time',
            ]);

            // 🔁 REVERT RENAMES
            $table->renameColumn('evaluation_results', 'results');
            $table->renameColumn('evaluation_remarks', 'remarks');

            // ➕ RESTORE DROPPED COLUMNS
            $table->tinyInteger('department')->nullable();
            $table->string('positive_feedback', 1024)->nullable();
            $table->string('negative_feedback', 1024)->nullable();
        });
    }
};
