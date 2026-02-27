<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Change permissions column to integer
            $table->tinyInteger('permissions')->unsigned()->change();
        });

        // Optional: Set default values for existing rows (must be between 1-7)
        \DB::table('users')->whereNotBetween('permissions', [1, 7])->update(['permissions' => 1]);
    }
};

