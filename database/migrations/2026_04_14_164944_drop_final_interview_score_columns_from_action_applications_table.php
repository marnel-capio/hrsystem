<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('action_applicant_applications', function (Blueprint $table) {

            if (Schema::hasColumn('action_applicant_applications', 'final_interview_score_1')) {
                $table->dropColumn('final_interview_score_1');
            }

            if (Schema::hasColumn('action_applicant_applications', 'final_interview_score_2')) {
                $table->dropColumn('final_interview_score_2');
            }

            if (Schema::hasColumn('action_applicant_applications', 'final_interview_score_3')) {
                $table->dropColumn('final_interview_score_3');
            }

            if (Schema::hasColumn('action_applicant_applications', 'final_interview_score_4')) {
                $table->dropColumn('final_interview_score_4');
            }
        });
    }

    public function down(): void
    {
        Schema::table('action_applicant_applications', function (Blueprint $table) {
            $table->decimal('final_interview_score_1', 5, 2)->nullable();
            $table->decimal('final_interview_score_2', 5, 2)->nullable();
            $table->decimal('final_interview_score_3', 5, 2)->nullable();
            $table->decimal('final_interview_score_4', 5, 2)->nullable();
        });
    }
};
