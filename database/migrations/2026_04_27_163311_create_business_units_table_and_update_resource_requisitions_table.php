<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    public function up(): void
{
    // 1. Create business_units table
    Schema::create('business_units', function (Blueprint $table) {
        $table->id();
        $table->string('business_unit', 20);
        $table->timestamps();
    });

    // 2. Add business_unit_id column with a default value to resource_requisitions table
    Schema::table('resource_requisitions', function (Blueprint $table) {
        $table->unsignedBigInteger('business_unit_id')->default(1); // Use a default value, like 1 for the first business unit
    });

    // 3. Update the existing records to ensure there is a valid business_unit_id for all rows
    DB::table('resource_requisitions')->update(['business_unit_id' => 1]); // Assign 1 as the default business unit_id

    // 4. Add the foreign key constraint to the business_unit_id column
    Schema::table('resource_requisitions', function (Blueprint $table) {
        $table->foreign('business_unit_id')
            ->references('id')
            ->on('business_units')
            ->onDelete('restrict');
    });
}
};