<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('action_applicant_applications', function (Blueprint $table) {
            // DATETIME field for Excel source timestamp
            $table->dateTime('source_date')->nullable()->after('created_time');
        });
    }

    public function down()
    {
        Schema::table('action_applicant_applications', function (Blueprint $table) {
            $table->dropColumn('source_date');
        });
    }
};
