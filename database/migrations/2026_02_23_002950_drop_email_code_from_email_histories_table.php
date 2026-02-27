<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('email_histories', function (Blueprint $table) {
            $table->dropColumn('email_code');
        });
    }

    public function down(): void
    {
        Schema::table('email_histories', function (Blueprint $table) {
            $table->string('email_code', 80)->after('status');
        });
    }
};