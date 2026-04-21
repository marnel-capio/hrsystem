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
        Schema::table('intermediate_applicants', function (Blueprint $table) {

            $table->tinyInteger('japanese_background')
                ->comment("1-None, 2-Self Study / University Level, 3-JLPT Certification")
                ->after('remarks');

            $table->tinyInteger('japanese_level')
                ->nullable() // ✅ already nullable (no need for separate migration)
                ->comment("5-N5, 4-N4, 3-N3, 2-N2, 1-N1")
                ->after('japanese_background');

            $table->string('background_remarks', 255)
                ->nullable()
                ->after('japanese_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('intermediate_applicants', function (Blueprint $table) {
            $table->dropColumn([
                'japanese_background',
                'japanese_level',
                'background_remarks',
            ]);
        });
    }
};
