<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('intermediate_applicants_applications');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is irreversible unless you recreate the table structure
    }
};