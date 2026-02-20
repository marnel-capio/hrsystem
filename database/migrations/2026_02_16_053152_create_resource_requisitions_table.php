<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resource_requisitions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->tinyInteger('engagement_type')->comment("1- Permanent,2- Temporary(Consultant),3- OJT");
            $table->tinyInteger('sourcing_type')->comment("1- Internal,2- External,3- Either");
            $table->tinyInteger('request_type')->comment("1- New Requirement,2- Replacement");
            $table->tinyInteger('replacement_due_to')->nullable()->comment("1- Promotion,2- Attrition,3- Backfill,4- Transfer");
            $table->string('person_to_replace', 80)->nullable();
            $table->tinyInteger('location_assignement')->comment("1- Alabang,2- Makati,3- Cebu,4- Japan,5- China");
            $table->bigInteger('project_id')->unsigned();
            $table->string('project_description', 1024)->nullable();
            $table->string('business_unit', 20);
            $table->string('resource', 1024)->nullable();
            $table->string('practice', 1024)->nullable();
            $table->string('no_resources_needed', 20)->nullable();
            $table->dateTime('start_date')->nullable();
            $table->string('duration_project_engagement', 20)->nullable();
            $table->string('required_skills', 1024)->nullable();
            $table->string('preferred_skilss', 1024)->nullable();
            $table->string('role', 1024)->nullable();
            $table->string('remarks', 1024)->nullable();

            // Audit fields
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->dateTime('created_time')->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->dateTime('updated_time')->nullable();

            // Optional foreign keys
            // $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            // $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resource_requisitions');
    }
};
