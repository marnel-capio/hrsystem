<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('action_applicant_applications', function (Blueprint $table) {
            $table->renameColumn('finall_interview_application_status', 'final_interview_application_status');
        });
    }

    public function down(): void
    {
        Schema::table('action_applicant_applications', function (Blueprint $table) {
            $table->renameColumn('final_interview_application_status', 'finall_interview_application_status');
        });
    }
};
