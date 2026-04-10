<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resource_schedules', function (Blueprint $table) {
            $table->dropColumn('training_enddate');
        });
    }

    public function down(): void
    {
        Schema::table('resource_schedules', function (Blueprint $table) {
            $table->string('training_enddate', 10)->nullable();
        });
    }
};
