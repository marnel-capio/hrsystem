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
        Schema::table('intermediate_applicants', function (Blueprint $table) {
            // Add/Update gender column with comment
            if (!Schema::hasColumn('intermediate_applicants', 'gender')) {
                $table->tinyInteger('gender')->default(0)
                      ->comment('1-Male, 2-Female')
                      ->after('middle_name');
            } else {
                // Update existing gender column comment
                DB::statement("ALTER TABLE intermediate_applicants MODIFY COLUMN gender TINYINT(4) NOT NULL DEFAULT 0 COMMENT '1-Male, 2-Female'");
            }
            
            // Add birthdate column (only if it doesn't exist)
            if (!Schema::hasColumn('intermediate_applicants', 'birthdate')) {
                $table->string('birthdate', 20)->after('gender');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('intermediate_applicants', function (Blueprint $table) {
            if (Schema::hasColumn('intermediate_applicants', 'birthdate')) {
                $table->dropColumn('birthdate');
            }
            
            // Reset gender to basic tinyint without comment
            DB::statement("ALTER TABLE intermediate_applicants MODIFY COLUMN gender TINYINT(4) NOT NULL DEFAULT 0");
        });
    }
};