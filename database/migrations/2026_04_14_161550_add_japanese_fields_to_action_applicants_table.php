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
            $table->tinyInteger('japanese_background')
                ->comment("1-None, 2-Self-study, 3-University level")
                ->after('awards_recognition');

            $table->tinyInteger('japanese_level')
                ->comment("5-N5 (lowest), 4-N4, 3-N3, 2-N2, 1-N1")
                ->after('japanese_background');

            // Note: column name cannot have spaces → use underscore
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
        Schema::table('action_applicants', function (Blueprint $table) {
            $table->dropColumn([
                'japanese_background',
                'japanese_level',
                'background_remarks',
            ]);
        });
    }
};
