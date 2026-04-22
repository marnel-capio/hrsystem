<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resource_requisitions', function (Blueprint $table) {
            $table->string('custom_location', 255)->nullable()->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('resource_requisitions', function (Blueprint $table) {
            $table->dropColumn('custom_location');
        });
    }
};