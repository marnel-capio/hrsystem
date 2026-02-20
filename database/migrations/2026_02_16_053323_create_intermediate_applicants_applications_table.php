<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intermediate_applicants_applications', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->tinyInteger('application_stage')->default(1)->comment("1-New,2-For Exam,3-For Initial Interview,4-For Final Interview,5-For Job Offer");
            $table->bigInteger('intermediate_applicant_id')->unsigned();
            $table->bigInteger('resource_schedule_id')->unsigned()->nullable();
            $table->string('fy_week', 20);
            $table->string('position', 80)->nullable();
            $table->bigInteger('source_project_id')->unsigned()->nullable();

            // Screening questions
            $table->tinyInteger('answer_q1')->nullable()->comment("1-Yes,0-No");
            $table->tinyInteger('answer_q2')->nullable()->comment("1-Yes,0-No");
            $table->tinyInteger('answer_q3')->nullable()->comment("1-Yes,0-No");
            $table->tinyInteger('answer_q4')->nullable()->comment("1-Yes,0-No");
            $table->tinyInteger('answer_q5')->nullable()->comment("1-Yes,0-No");
            $table->tinyInteger('answer_q6')->nullable()->comment("1-Yes,0-No");
            $table->tinyInteger('answer_q7')->nullable()->comment("1-Yes,0-No");

            $table->dateTime('availability_date')->nullable();
            $table->string('desired_salary_range', 80)->nullable();
            $table->string('work_preference', 20)->nullable()->comment("1-Regular Full-Time,2-Temporary Full-Time,3-Project-Based,4-Regular Part-Time,5-Temporary Part-Time");
            $table->string('basic_pay', 20)->nullable();
            $table->string('bonuses', 1024)->nullable();
            $table->string('hmo', 80)->nullable();
            $table->string('leaves', 80)->nullable();
            $table->string('allowances', 250)->nullable();
            $table->string('other_benefits', 250)->nullable();
            $table->string('targeted_company', 80)->nullable();
            $table->string('industry_experience', 80)->nullable();

            $table->bigInteger('contacted_by')->unsigned()->nullable();
            $table->dateTime('contacted_date')->nullable();
            $table->tinyInteger('replied')->nullable()->comment("1-Yes,0-No");
            $table->dateTime('replied_date')->nullable();
            $table->string('current_employer', 80)->nullable();
            $table->string('asking_rate', 20)->nullable();
            $table->string('site_assignment', 20)->nullable();

            // Exam results
            $table->dateTime('testing_datetime')->nullable();
            $table->decimal('atpp', 8,2)->nullable();
            $table->decimal('tech_exam', 8,2)->nullable();

            // Interviews
            $table->dateTime('hr_interview_datetime')->nullable();
            $table->string('hr_interview_week', 20)->nullable();
            $table->dateTime('bu_interview_datetime')->nullable();
            $table->string('bu_interview_week', 20)->nullable();
            $table->dateTime('final_interview_datetime')->nullable();
            $table->string('final_interview_week', 20)->nullable();

            // Statuses
            $table->tinyInteger('paper_screening_status')->nullable()->comment("1-Pending,2-Done,3-Passed,4-P2,5-Failed");
            $table->tinyInteger('exam_status')->nullable()->comment("1-Pending,2-Done,3-Passed,4-P2,5-Failed");
            $table->tinyInteger('hr_interview_status')->nullable()->comment("1-Pending,2-Done,3-Passed,4-P2,5-Failed");
            $table->tinyInteger('bu_interview_status')->nullable()->comment("1-Pending,2-Done,3-Passed,4-P2,5-Failed");
            $table->tinyInteger('final_interview_status')->nullable()->comment("1-Pending,2-Done,3-Passed,4-P2,5-Failed");
            $table->tinyInteger('job_offer_status')->nullable()->comment("1-Pending,2-Done,3-Accept,4-Decline,5-Withdraw,6-Retracted");

            $table->dateTime('job_offer_accepted_date')->nullable();
            $table->dateTime('aws_start_date')->nullable();
            $table->string('aws_rank', 80)->nullable();
            $table->string('parked_to', 80)->nullable();
            $table->string('reason_by_category', 1024)->nullable();
            $table->string('reason_for_decline', 1024)->nullable();
            $table->string('remarks', 1024)->nullable();

            // Audit fields
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->dateTime('created_time')->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->dateTime('updated_time')->nullable();

            // Optional foreign keys
            // $table->foreign('intermediate_applicant_id')->references('id')->on('intermediate_applicants')->onDelete('cascade');
            // $table->foreign('resource_schedule_id')->references('id')->on('resource_schedules')->onDelete('set null');
            // $table->foreign('source_project_id')->references('id')->on('projects')->onDelete('set null');
            // $table->foreign('contacted_by')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intermediate_applicants_applications');
    }
};
