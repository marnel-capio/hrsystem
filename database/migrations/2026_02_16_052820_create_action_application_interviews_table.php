<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('action_application_interviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('interviewer_id')->unsigned()->comment('user id');
            $table->bigInteger('action_application_id')->unsigned()->comment('action application id');
            $table->tinyInteger('interview_type')->nullable()->comment("1- HR Interview,2- Initial Interview,3- Final Interview");

            // Exam results
            $table->decimal('exam_atpp_result', 8, 2)->nullable();
            $table->decimal('exam_git_result', 8, 2)->nullable();
            $table->decimal('exam_prg_result', 8, 2)->nullable();
            $table->string('exam_remarks', 1024)->nullable();

            // Initial interview
            $table->decimal('initial_interview_result', 8, 2)->nullable();
            $table->string('initial_interview_remark', 1024)->nullable();

            // Final interview
            $table->decimal('final_interview_result', 8, 2)->nullable();
            $table->string('final_interview_remarks', 1024)->nullable();

            // General remarks
            $table->string('remarks', 1024)->nullable();

            // Audit fields
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->dateTime('created_time')->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->dateTime('updated_time')->nullable();

            // Indexes
            $table->index('interviewer_id', 'idx_interviewer_id');
            $table->index('action_application_id', 'idx_action_application_id');

            // Optional foreign keys
            // $table->foreign('interviewer_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('action_application_id')->references('id')->on('action_applicant_applications')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('action_application_interviews');
    }
};
