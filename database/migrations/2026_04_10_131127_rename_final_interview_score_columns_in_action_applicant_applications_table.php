<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE action_applicant_applications CHANGE final_interview_sf final_interview_score_1 DECIMAL(8,2) NULL');
        DB::statement('ALTER TABLE action_applicant_applications CHANGE final_interview_ib final_interview_score_2 DECIMAL(8,2) NULL');
        DB::statement('ALTER TABLE action_applicant_applications CHANGE final_interview_rv final_interview_score_3 DECIMAL(8,2) NULL');
        DB::statement('ALTER TABLE action_applicant_applications CHANGE final_interview_ma final_interview_score_4 DECIMAL(8,2) NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE action_applicant_applications CHANGE final_interview_score_1 final_interview_sf DECIMAL(8,2) NULL');
        DB::statement('ALTER TABLE action_applicant_applications CHANGE final_interview_score_2 final_interview_ib DECIMAL(8,2) NULL');
        DB::statement('ALTER TABLE action_applicant_applications CHANGE final_interview_score_3 final_interview_rv DECIMAL(8,2) NULL');
        DB::statement('ALTER TABLE action_applicant_applications CHANGE final_interview_score_4 final_interview_ma DECIMAL(8,2) NULL');
    }
};
