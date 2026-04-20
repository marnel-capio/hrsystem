<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('action_application_interviews', function (Blueprint $table) {
            $table->decimal('score', 8, 2)->nullable()->after('final_interview_result');
            $table->tinyInteger('evaluation_result')->nullable()
                ->comment('1-Pending,2-Passed,3-Failed')
                ->after('score');
            $table->string('evaluation_remarks', 1024)->nullable()->after('evaluation_result');
        });
    }

    public function down(): void
    {
        Schema::table('action_application_interviews', function (Blueprint $table) {
            $table->dropColumn([
                'score',
                'evaluation_result',
                'evaluation_remarks',
            ]);
        });
    }
};
