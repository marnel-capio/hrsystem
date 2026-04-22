<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('intermediate_applicants_applications', function (Blueprint $table) {

            $table->dropColumn([
                'testing_datetime',
                'atpp',
                'tech_exam',

                'hr_interview_datetime',
                'hr_interview_week',

                'bu_interview_datetime',
                'bu_interview_week',

                'final_interview_datetime',
                'final_interview_week',

                'exam_status',
                'hr_interview_status',
                'bu_interview_status',
                'final_interview_status',

                'job_offer_status',
                'job_offer_accepted_date',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('intermediate_applicants_applications', function (Blueprint $table) {

            $table->dateTime('testing_datetime')->nullable();
            $table->decimal('atpp', 8, 2)->nullable();
            $table->decimal('tech_exam', 8, 2)->nullable();

            $table->dateTime('hr_interview_datetime')->nullable();
            $table->string('hr_interview_week', 20)->nullable();

            $table->dateTime('bu_interview_datetime')->nullable();
            $table->string('bu_interview_week', 20)->nullable();

            $table->dateTime('final_interview_datetime')->nullable();
            $table->string('final_interview_week', 20)->nullable();

            $table->tinyInteger('exam_status')->nullable()
                ->comment('1-Pending,2-Done,3-Passed,4-P2,5-Failed');

            $table->tinyInteger('hr_interview_status')->nullable()
                ->comment('1-Pending,2-Done,3-Passed,4-P2,5-Failed');

            $table->tinyInteger('bu_interview_status')->nullable()
                ->comment('1-Pending,2-Done,3-Passed,4-P2,5-Failed');

            $table->tinyInteger('final_interview_status')->nullable()
                ->comment('1-Pending,2-Done,3-Passed,4-P2,5-Failed');

            $table->tinyInteger('job_offer_status')->nullable()
                ->comment('1-Pending,2-Done,3-Accept,4-Decline,5-Withdraw,6-Retracted');

            $table->dateTime('job_offer_accepted_date')->nullable();
        });
    }
};
