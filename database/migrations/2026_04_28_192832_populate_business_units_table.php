<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class PopulateBusinessUnitsTable extends Migration
{
    public function up(): void
    {
        // Insert the provided business units with specific IDs
        DB::table('business_units')->insert([
            ['id' => 1, 'business_unit' => 'DEV 2', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'business_unit' => 'DEV 5', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'business_unit' => 'DEV A', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'business_unit' => 'DEV B', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'business_unit' => 'DEV C', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'business_unit' => 'DEV D', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'business_unit' => 'DEV G', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'business_unit' => 'DEV H', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'business_unit' => 'DEV I', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'business_unit' => 'DEV J', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'business_unit' => 'DEV M', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'business_unit' => 'DEV O', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 13, 'business_unit' => 'DEV P', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 14, 'business_unit' => 'DEV Q', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 15, 'business_unit' => 'DX', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 16, 'business_unit' => 'ACTION', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 17, 'business_unit' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 18, 'business_unit' => 'Finance', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 19, 'business_unit' => 'HRD', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 20, 'business_unit' => 'MIS', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        // Rollback: Remove the inserted rows (optional)
        DB::table('business_units')->whereIn('id', [
            1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20
        ])->delete();
    }
}