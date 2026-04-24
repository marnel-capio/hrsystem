<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('action_applicants', function (Blueprint $table) {
            $table->dropColumn('source_origin');
        });
    }

    public function down(): void
    {
        Schema::table('action_applicants', function (Blueprint $table) {
            $table->tinyInteger('source_origin')
                ->default(1)
                ->comment('1-Internal, 2-External');
        });
    }
};
