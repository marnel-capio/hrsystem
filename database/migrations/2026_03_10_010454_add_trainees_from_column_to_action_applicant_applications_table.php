<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('action_applicant_applications', function (Blueprint $table) {
            $table->tinyInteger('trainees_from')->nullable()->after('job_offer_remarks')
                  ->comment('1 = Manila, 2 = Cebu');
        });
    }

    public function down(): void
    {
        Schema::table('action_applicant_applications', function (Blueprint $table) {
            $table->dropColumn('trainees_from');
        });
    }
};