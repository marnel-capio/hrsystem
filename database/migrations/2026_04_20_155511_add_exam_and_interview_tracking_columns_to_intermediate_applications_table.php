<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('intermediate_applicants_applications', function (Blueprint $table) {

            $table->dateTime('exam_plan_date')->nullable()->after('paper_screening_status');

            $table->dateTime('exam_actual_date')->nullable()->after('exam_plan_date');

            $table->tinyInteger('exam_venue')->nullable()
                ->comment('1-Gmeet,2-Zoom,3-USJ-R Basak,4-AdDU')
                ->after('exam_actual_date');

            $table->smallInteger('exam_atpp_part1_correct')->unsigned()->nullable()->after('exam_venue');
            $table->smallInteger('exam_atpp_part1_wrong')->unsigned()->nullable()->after('exam_atpp_part1_correct');

            $table->smallInteger('exam_atpp_part2_correct')->unsigned()->nullable()->after('exam_atpp_part1_wrong');
            $table->smallInteger('exam_atpp_part2_wrong')->unsigned()->nullable()->after('exam_atpp_part2_correct');

            $table->smallInteger('exam_atpp_part3_correct')->unsigned()->nullable()->after('exam_atpp_part2_wrong');
            $table->smallInteger('exam_atpp_part3_wrong')->unsigned()->nullable()->after('exam_atpp_part3_correct');

            $table->decimal('exam_atpp_result', 8, 2)->nullable()->after('exam_atpp_part3_wrong');
            $table->decimal('exam_tech_result', 8, 2)->nullable()->after('exam_atpp_result');

            $table->tinyInteger('exam_result')->nullable()
                ->comment('1-Pending,2-Passed,3-Failed')
                ->after('exam_tech_result');

            $table->tinyInteger('exam_application_status')->nullable()
                ->comment('1-Pending,2-1st Priority (Passed),3-2nd Priority (P2),4-Done,5-Passed,6-Failed')
                ->after('exam_result');

            $table->string('exam_remarks', 1024)->nullable()->after('exam_application_status');

            // Initial Interview
            $table->dateTime('initial_interview_plan_date')->nullable()->after('exam_remarks');
            $table->dateTime('initial_interview_actual_date')->nullable()->after('initial_interview_plan_date');
            $table->tinyInteger('initial_interview_venue')->nullable()->after('initial_interview_actual_date');
            $table->decimal('initial_interview_final', 8, 2)->nullable()->after('initial_interview_venue');

            $table->tinyInteger('initial_interview_result')->nullable()
                ->comment('1-Pending,2-Passed,3-Failed')
                ->after('initial_interview_final');

            $table->tinyInteger('initial_interview_application_status')->nullable()
                ->comment('1-Pending,2-Done,3-Passed,4-P2,5-Failed')
                ->after('initial_interview_result');

            $table->string('initial_interview_remarks', 1024)->nullable()
                ->after('initial_interview_application_status');

            // Final Interview
            $table->dateTime('final_interview_date')->nullable()->after('initial_interview_remarks');
            $table->decimal('final_interview_final', 8, 2)->nullable()->after('final_interview_date');

            $table->tinyInteger('final_interview_result')->nullable()
                ->comment('1-Pending,2-Passed,3-Failed')
                ->after('final_interview_final');

            $table->tinyInteger('final_interview_application_status')->nullable()
                ->comment('1-Pending,2-Done,3-Passed,4-P2,5-Failed')
                ->after('final_interview_result');

            $table->string('final_interview_remarks', 1024)->nullable()
                ->after('final_interview_application_status');

            // Job Offer
            $table->dateTime('job_offer_schedule')->nullable()->after('final_interview_remarks');

            $table->tinyInteger('job_offer_status')->nullable()
                ->comment('1-Pending,2-Done,3-Accept,4-Decline,5-Withdraw,6-Retracted')
                ->after('job_offer_schedule');

            $table->string('job_offer_remarks', 1024)->nullable()
                ->after('job_offer_status');
        });
    }

    public function down(): void
    {
        Schema::table('intermediate_applicants_applications', function (Blueprint $table) {
            $table->dropColumn([
                'exam_plan_date',
                'exam_actual_date',
                'exam_venue',
                'exam_atpp_part1_correct',
                'exam_atpp_part1_wrong',
                'exam_atpp_part2_correct',
                'exam_atpp_part2_wrong',
                'exam_atpp_part3_correct',
                'exam_atpp_part3_wrong',
                'exam_atpp_result',
                'exam_tech_result',
                'exam_result',
                'exam_application_status',
                'exam_remarks',

                'initial_interview_plan_date',
                'initial_interview_actual_date',
                'initial_interview_venue',
                'initial_interview_final',
                'initial_interview_result',
                'initial_interview_application_status',
                'initial_interview_remarks',

                'final_interview_date',
                'final_interview_final',
                'final_interview_result',
                'final_interview_application_status',
                'final_interview_remarks',

                'job_offer_schedule',
                'job_offer_status',
                'job_offer_remarks',
            ]);
        });
    }
};
