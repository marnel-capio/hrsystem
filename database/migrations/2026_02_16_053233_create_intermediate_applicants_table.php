<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intermediate_applicants', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->dateTime('registered_date');
            $table->bigInteger('registered_by')->unsigned();
            $table->tinyInteger('source_type')->nullable()->comment("1-SP (Service Provider),2-Recruitment Portals,3-Employee Referral,4-Walk-in");
            $table->tinyInteger('source')->nullable()->comment("Recruitment Portal: 1-Mynimo,2-Indeed,3-Kalibrr,4-FoundIt,5-LinkedIn,6-Facebook,7-Jobstreet; SP: 8-AAISI,9-Primover,10-Pan Asia,11-Nityo,12-CPS");
            $table->string('other_source', 80)->nullable()->comment("*for source type that is 3");
            $table->string('last_name', 80);
            $table->string('first_name', 80);
            $table->string('middle_name', 80)->nullable();
            $table->tinyInteger('gender')->comment("1-Male,2-Female");
            $table->tinyInteger('age');
            $table->string('address', 1024)->nullable();
            $table->string('email_address', 80);
            $table->string('contact_no', 20);
            $table->string('school_graduated_from', 80)->nullable();
            $table->string('course', 10)->nullable();
            $table->string('year_attended', 10)->nullable();
            $table->string('others', 1024)->nullable();
            $table->string('spouse_details', 1024)->nullable();
            $table->tinyInteger('children')->nullable();
            $table->string('father_details', 1024)->nullable();
            $table->string('mother_details', 1024)->nullable();
            $table->string('sibling_details', 1024)->nullable();
            $table->string('emergency_contact_name', 120)->nullable();
            $table->string('emergency_contact_number', 20);
            $table->string('emergency_contact_address', 1024)->nullable();
            $table->string('remarks', 1024)->nullable();

            // Audit fields
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->dateTime('created_time')->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->dateTime('updated_time')->nullable();

            // Indexes
            $table->index('last_name', 'idx_last_name');
            $table->index('registered_date', 'idx_registered_date');

            // Optional foreign keys
            // $table->foreign('registered_by')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intermediate_applicants');
    }
};
