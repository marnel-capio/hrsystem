<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('action_applicants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('source_type')->nullable()->comment("1-Campus Recruitment, 2-Academe Partner, 3-Recruitment Portals, 4-Employee Referral, 5-Walk-in");
            $table->tinyInteger('source')->nullable()->comment("Recruitment Portal: 1-Mynimo, 2-Indeed, 3-Kalibrr, 4-FoundIt, 5-LinkedIn, 6-Facebook, 7-Jobstreet");
            $table->string('other_source', 80)->nullable()->comment("*for source type that is 1,2,4 & 5");
            $table->string('last_name', 80);
            $table->string('first_name', 80);
            $table->string('middle_name', 80)->nullable();
            $table->string('email_address', 80);
            $table->tinyInteger('gender')->comment("1-Male, 2-Female");
            $table->tinyInteger('age');
            $table->string('school', 80);
            $table->string('degree', 80);
            $table->string('others_degree', 80);
            $table->string('expected_graduation', 20);
            $table->string('awards_recognition', 1024);
            $table->string('other_examination_certificate', 1024);
            $table->string('thesis_project', 1024)->nullable();
            $table->string('extra_curricular', 1024)->nullable();
            $table->string('remarks', 1024)->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->dateTime('created_time')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->dateTime('updated_time')->nullable();

            // Index for time-based queries
            $table->index('created_time', 'idx_created_time');

            // Optional: foreign keys if you want
            // $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('action_applicants');
    }
};
