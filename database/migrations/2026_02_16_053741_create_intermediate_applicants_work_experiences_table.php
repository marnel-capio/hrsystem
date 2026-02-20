<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intermediate_applicants_work_experiences', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('intermediate_applicant_id')->unsigned();
            $table->string('employer', 20)->nullable();
            $table->string('company_address', 80)->nullable();
            $table->string('job_title', 20)->nullable();
            $table->string('date_employed', 40)->nullable();
            $table->string('work_description', 1024)->nullable();
            $table->string('salary', 40)->nullable();
            $table->string('reason_for_leaving', 1024)->nullable();
            $table->string('name_supervisor', 80)->nullable();
            $table->string('remarks', 1024)->nullable();
            $table->tinyInteger('is_deleted')->default(0)->comment("0-not deleted,1-deleted");

            // Audit fields
            $table->bigInteger('created_by')->unsigned();
            $table->dateTime('created_time');
            $table->bigInteger('updated_by')->unsigned();
            $table->dateTime('updated_time');

            // Optional foreign keys
            // $table->foreign('intermediate_applicant_id')->references('id')->on('intermediate_applicants')->onDelete('cascade');
            // $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intermediate_applicants_work_experiences');
    }
};
