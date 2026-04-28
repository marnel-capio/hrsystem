<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Temporarily disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

        // 1. Create the business_units table
        Schema::create('business_units', function (Blueprint $table) {
            $table->id();
            $table->string('business_unit', 20);
            $table->timestamps();
        });

        // 2. Add business_unit_id column to the resource_requisitions table
        Schema::table('resource_requisitions', function (Blueprint $table) {
            $table->unsignedBigInteger('business_unit_id')->default(1); // Default to 1 for existing records
        });

        // 3. Update the existing records to ensure there is a valid business_unit_id
        DB::table('resource_requisitions')->update(['business_unit_id' => 1]); // Assign 1 as default

        // 4. Add the foreign key constraint to the business_unit_id column
        Schema::table('resource_requisitions', function (Blueprint $table) {
            $table->foreign('business_unit_id')
                ->references('id')
                ->on('business_units')
                ->onDelete('restrict');
        });

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }

    public function down(): void
    {
        // Drop the foreign key and the business_unit_id column if rolling back
        Schema::table('resource_requisitions', function (Blueprint $table) {
            $table->dropForeign(['business_unit_id']);
            $table->dropColumn('business_unit_id');
        });

        // Drop the business_units table
        Schema::dropIfExists('business_units');
    }
};