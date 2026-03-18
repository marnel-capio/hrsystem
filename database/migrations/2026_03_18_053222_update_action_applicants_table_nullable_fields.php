<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('action_applicants', function (Blueprint $table) {

            // Make optional fields nullable
            $table->tinyInteger('source')->nullable()->change();
            $table->string('other_source', 80)->nullable()->change();
            $table->string('middle_name', 80)->nullable()->change();
            $table->string('others_degree', 80)->nullable()->change();

            $table->string('awards_recognition', 1024)->nullable()->change();
            $table->string('other_examination_certificate', 1024)->nullable()->change();

            $table->string('thesis_project', 1024)->nullable()->change();
            $table->string('extra_curricular', 1024)->nullable()->change();
            $table->string('remarks', 1024)->nullable()->change();

            // Optional audit fields
            $table->unsignedBigInteger('created_by')->nullable()->change();
            $table->dateTime('created_time')->nullable()->change();
            $table->unsignedBigInteger('updated_by')->nullable()->change();
            $table->dateTime('updated_time')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('action_applicants', function (Blueprint $table) {

            // Revert back to NOT NULL if needed
            $table->tinyInteger('source')->nullable(false)->change();
            $table->string('other_source', 80)->nullable(false)->change();
            $table->string('middle_name', 80)->nullable(false)->change();
            $table->string('others_degree', 80)->nullable(false)->change();

            $table->string('awards_recognition', 1024)->nullable(false)->change();
            $table->string('other_examination_certificate', 1024)->nullable(false)->change();

            $table->string('thesis_project', 1024)->nullable(false)->change();
            $table->string('extra_curricular', 1024)->nullable(false)->change();
            $table->string('remarks', 1024)->nullable(false)->change();

            $table->unsignedBigInteger('created_by')->nullable(false)->change();
            $table->dateTime('created_time')->nullable(false)->change();
            $table->unsignedBigInteger('updated_by')->nullable(false)->change();
            $table->dateTime('updated_time')->nullable(false)->change();
        });
    }
};
