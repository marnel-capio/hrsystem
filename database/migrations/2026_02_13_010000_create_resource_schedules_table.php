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
            $table->bigInteger('action_batch_id')->unsigned()->nullable(); //nullable for now since wala pang action batches na coded
            $table->tinyInteger('target_location'); // 1-Manila, 2-Cebu
            $table->tinyInteger('target_trainees');
            $table->string('deployment_date', 10);
            $table->string('contact_schools_startdate', 10)->nullable();
            $table->string('contact_schools_enddate', 10)->nullable();
            $table->string('source_testing_startdate', 10)->nullable();
            $table->string('source_testing_enddate', 10)->nullable();
            $table->string('initial_interviews_startdate', 10)->nullable();
            $table->string('initial_interviews_enddate', 10)->nullable();
            $table->string('final_interviews_startdate', 10)->nullable();
            $table->string('final_interviews_enddate', 10)->nullable();
            $table->string('contract_offers_startdate', 10)->nullable();
            $table->string('contract_offers_enddate', 10)->nullable();
            $table->string('requirements_startdate', 10)->nullable();
            $table->string('requirements_enddate', 10)->nullable();
            $table->string('training_startdate', 10)->nullable();
            $table->string('training_enddate', 10)->nullable();
            $table->string('remarks', 1024)->nullable();

            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->dateTime('created_time')->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->dateTime('updated_time')->nullable();           
            
            // foreign key references for users and action_batch tables in the future
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