<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('action_applicant_applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('action_applicant_id')->unsigned()->comment('action applicant id');

            // Uploaded files
            $table->string('upload_resume', 80)->nullable();
            $table->string('upload_tor', 80)->nullable();
            $table->string('upload_pic', 80)->nullable();

            // Exam
            $table->dateTime('exam_plan_date')->nullable();
            $table->dateTime('exam_actual_date')->nullable();
            $table->tinyInteger('exam_venue')->nullable()->comment("1-Gmeet,2-Zoom,3-USJ-R Basak,4-AdDU");
            $table->decimal('exam_atpp_result', 8, 2)->nullable();
            $table->decimal('exam_git_result', 8, 2)->nullable();
            $table->decimal('exam_prg_result', 8, 2)->nullable();
            $table->tinyInteger('exam_result')->nullable()->comment("1-Pending,2-Passed,3-Failed");
            $table->tinyInteger('exam_application_status')->nullable()->comment("1-Pending,2-1st Priority (Passed),3-2nd Priority (P2),4-Done,5-Passed,6-Failed");
            $table->string('exam_remarks', 1024)->nullable()->comment('*visible for recruiter only');

            // Initial Interview
            $table->dateTime('initial_interview_plan_date')->nullable();
            $table->dateTime('initial_interview_actual_date')->nullable();
            $table->tinyInteger('initial_interview_venue')->nullable();
            $table->decimal('initial_interview_final', 8, 2)->nullable();
            $table->tinyInteger('initial_interview_result')->nullable()->comment("1-Pending,2-Passed,3-Failed");
            $table->tinyInteger('initial_interview_application_status')->nullable()->comment("1-Pending,2-Done,3-Passed,4-P2,5-Failed");
            $table->string('initial_interview_remarks', 1024)->nullable()->comment('*visible for recruiter only');

            // Final Interview
            $table->dateTime('final_interview_date')->nullable();
            $table->decimal('final_interview_sf', 8, 2)->nullable();
            $table->decimal('final_interview_ib', 8, 2)->nullable();
            $table->decimal('final_interview_rv', 8, 2)->nullable();
            $table->decimal('final_interview_ma', 8, 2)->nullable();
            $table->decimal('final_interview_final', 8, 2)->nullable();
            $table->tinyInteger('final_interview_result')->nullable()->comment("1-Pending,2-Passed,3-Failed");
            $table->tinyInteger('finall_interview_application_status')->nullable()->comment("1-Pending,2-Done,3-Passed,4-P2,5-Failed");
            $table->string('final_interview_remarks', 1024)->nullable()->comment('*visible for recruiter only');

            // Job Offer
            $table->dateTime('job_offer_schedule')->nullable();
            $table->tinyInteger('job_offer_status')->nullable()->comment("1-Pending,2-Done,3-Accept,4-Decline,5-Withdraw,6-Retracted");
            $table->string('job_offer_remarks', 1024)->nullable()->comment('*visible for recruiter only');

            $table->string('remarks', 1024)->nullable();

            // Audit fields
            $table->bigInteger('created_by')->unsigned();
            $table->dateTime('created_time');
            $table->bigInteger('updated_by')->unsigned();
            $table->dateTime('updated_time');

            // Indexes
            $table->index('action_applicant_id', 'idx_action_applicant_id');

            // Optional foreign key
            // $table->foreign('action_applicant_id')->references('id')->on('action_applicants')->onDelete('cascade');
            // $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('action_applicant_applications');
    }
};
