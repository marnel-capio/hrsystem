<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('intermediate_applicants_applications', function (Blueprint $table) {
            $table->tinyInteger('paper_screening_status')
                ->default(1)
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('intermediate_applicants_applications', function (Blueprint $table) {
            $table->tinyInteger('paper_screening_status')
                ->default(null)
                ->change();
        });
    }
};