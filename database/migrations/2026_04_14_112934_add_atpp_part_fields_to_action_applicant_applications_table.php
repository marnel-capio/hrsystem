<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('action_applicant_applications', function (Blueprint $table) {
            $table->unsignedSmallInteger('exam_atpp_part1_correct')
                ->nullable()
                ->after('exam_venue');

            $table->unsignedSmallInteger('exam_atpp_part1_wrong')
                ->nullable()
                ->after('exam_atpp_part1_correct');

            $table->unsignedSmallInteger('exam_atpp_part2_correct')
                ->nullable()
                ->after('exam_atpp_part1_wrong');

            $table->unsignedSmallInteger('exam_atpp_part2_wrong')
                ->nullable()
                ->after('exam_atpp_part2_correct');

            $table->unsignedSmallInteger('exam_atpp_part3_correct')
                ->nullable()
                ->after('exam_atpp_part2_wrong');

            $table->unsignedSmallInteger('exam_atpp_part3_wrong')
                ->nullable()
                ->after('exam_atpp_part3_correct');
        });
    }

    public function down(): void
    {
        Schema::table('action_applicant_applications', function (Blueprint $table) {
            $table->dropColumn([
                'exam_atpp_part1_correct',
                'exam_atpp_part1_wrong',
                'exam_atpp_part2_correct',
                'exam_atpp_part2_wrong',
                'exam_atpp_part3_correct',
                'exam_atpp_part3_wrong',
            ]);
        });
    }
};
