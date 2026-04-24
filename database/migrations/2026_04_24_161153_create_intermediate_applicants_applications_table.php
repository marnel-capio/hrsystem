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
        Schema::create('intermediate_applicants_applications', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('application_stage')->default(1)->comment('1-New,2-For Exam,3-For HR Interview, 4-For BU Interview, 5-For Final Interview,6-For Job Offer, 7-Failed');
            $table->unsignedBigInteger('intermediate_applicant_id');
            $table->unsignedBigInteger('resource_schedule_id')->nullable();
            $table->string('fy_week', 20);
            $table->string('position', 80)->nullable();
            $table->unsignedBigInteger('source_project_id')->nullable();
            $table->string('upload_resume', 80)->nullable();
            $table->string('upload_pic', 80)->nullable();
            $table->tinyInteger('answer_q1')->nullable()->comment('1-Yes,0-No');
            $table->tinyInteger('answer_q2')->nullable()->comment('1-Yes,0-No');
            $table->tinyInteger('answer_q3')->nullable()->comment('1-Yes,0-No');
            $table->tinyInteger('answer_q4')->nullable()->comment('1-Yes,0-No');
            $table->string('availability_date', 80)->nullable();
            $table->string('desired_salary_range', 80)->nullable();
            $table->string('work_preference', 80)->nullable();
            $table->string('basic_pay', 20)->nullable();
            $table->string('bonuses', 1024)->nullable();
            $table->string('hmo', 80)->nullable();
            $table->string('leaves', 80)->nullable();
            $table->string('allowances', 250)->nullable();
            $table->string('other_benefits', 250)->nullable();
            $table->string('targeted_company', 80)->nullable();
            $table->string('industry_experience', 80)->nullable();
            $table->unsignedBigInteger('contacted_by')->nullable();
            $table->dateTime('contacted_date')->nullable();
            $table->tinyInteger('replied')->nullable()->comment('1-Yes,0-No');
            $table->dateTime('replied_date')->nullable();
            $table->string('current_employer', 80)->nullable();
            $table->string('asking_rate', 20)->nullable();
            $table->string('site_assignment', 20)->nullable();
            $table->tinyInteger('paper_screening_status')->default(1);
            $table->dateTime('exam_plan_date')->nullable();
            $table->dateTime('exam_actual_date')->nullable();
            $table->tinyInteger('exam_venue')->nullable()->comment('1-Gmeet,2-Zoom,3-USJ-R Basak,4-AdDU');
            $table->unsignedSmallInteger('exam_atpp_part1_correct')->nullable();
            $table->unsignedSmallInteger('exam_atpp_part1_wrong')->nullable();
            $table->unsignedSmallInteger('exam_atpp_part2_correct')->nullable();
            $table->unsignedSmallInteger('exam_atpp_part2_wrong')->nullable();
            $table->unsignedSmallInteger('exam_atpp_part3_correct')->nullable();
            $table->unsignedSmallInteger('exam_atpp_part3_wrong')->nullable();
            $table->decimal('exam_atpp_result', 8, 2)->nullable();
            $table->decimal('exam_tech_result', 8, 2)->nullable();
            $table->tinyInteger('exam_result')->nullable()->comment('1-Pending,2-Passed,3-Failed');
            $table->tinyInteger('exam_application_status')->nullable()->comment('1-Pending,2-1st Priority (Passed),3-2nd Priority (P2),4-Done,5-Passed,6-Failed');
            $table->string('exam_remarks', 1024)->nullable();
            $table->dateTime('initial_interview_plan_date')->nullable();
            $table->dateTime('initial_interview_actual_date')->nullable();
            $table->tinyInteger('initial_interview_venue')->nullable();
            $table->decimal('initial_interview_final', 8, 2)->nullable();
            $table->tinyInteger('initial_interview_result')->nullable()->comment('1-Pending,2-Passed,3-Failed');
            $table->tinyInteger('initial_interview_application_status')->nullable()->comment('1-Pending,2-Done,3-Passed,4-P2,5-Failed');
            $table->string('initial_interview_remarks', 1024)->nullable();
            $table->dateTime('final_interview_date')->nullable();
            $table->decimal('final_interview_final', 8, 2)->nullable();
            $table->tinyInteger('final_interview_result')->nullable()->comment('1-Pending,2-Passed,3-Failed');
            $table->tinyInteger('final_interview_application_status')->nullable()->comment('1-Pending,2-Done,3-Passed,4-P2,5-Failed');
            $table->string('final_interview_remarks', 1024)->nullable();
            $table->dateTime('job_offer_schedule')->nullable();
            $table->tinyInteger('job_offer_status')->nullable()->comment('1-Pending,2-Done,3-Accept,4-Decline,5-Withdraw,6-Retracted');
            $table->string('job_offer_remarks', 1024)->nullable();
            $table->dateTime('aws_start_date')->nullable();
            $table->string('aws_rank', 80)->nullable();
            $table->string('parked_to', 80)->nullable();
            $table->string('reason_by_category', 1024)->nullable();
            $table->string('reason_for_decline', 1024)->nullable();
            $table->string('remarks', 1024)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->dateTime('created_time')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->dateTime('updated_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intermediate_applicants_applications');
    }
};