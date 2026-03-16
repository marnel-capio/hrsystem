<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resource_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('action_batch_id')->unsigned();
            $table->bigInteger('prev_batch_id')->nullable();;  
            $table->tinyInteger('target_location'); // 1-Manila, 2-Cebu
            $table->tinyInteger('target_trainees');
            $table->string('deployment_date', 10);
            $table->string('contact_schools_startdate', 10);
            $table->string('contact_schools_enddate', 10);
            $table->string('source_testing_startdate', 10);
            $table->string('source_testing_enddate', 10);
            $table->string('initial_interviews_startdate', 10);
            $table->string('initial_interviews_enddate', 10);
            $table->string('final_interviews_startdate', 10);
            $table->string('final_interviews_enddate', 10);
            $table->string('contract_offers_startdate', 10);
            $table->string('contract_offers_enddate', 10);
            $table->string('requirements_startdate', 10);
            $table->string('requirements_enddate', 10);
            $table->string('training_startdate', 10);
            $table->string('training_enddate', 10);
            $table->string('remarks', 1024)->nullable();
            $table->bigInteger('created_by')->unsigned();
            $table->dateTime('created_time');
            $table->bigInteger('updated_by')->unsigned();
            $table->dateTime('updated_time');

            // Optional: foreign key references if you have users and action_batch tables
            // $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('action_batch_id')->references('id')->on('action_batches')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resource_schedules');
    }
};
