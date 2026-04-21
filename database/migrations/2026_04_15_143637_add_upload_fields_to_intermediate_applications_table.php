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
        Schema::table('intermediate_applicants_applications', function (Blueprint $table) {
            $table->string('upload_resume', 80)->nullable()->after('source_project_id');
            $table->string('upload_pic', 80)->nullable()->after('upload_resume');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('intermediate_applicants_applications', function (Blueprint $table) {
            $table->dropColumn(['upload_resume', 'upload_pic']);
        });
    }
};
