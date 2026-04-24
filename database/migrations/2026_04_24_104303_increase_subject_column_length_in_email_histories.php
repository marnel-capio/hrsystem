<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('email_histories', function (Blueprint $table) {
        // Increase column length to 512 characters (or adjust as needed)
        $table->string('subject', 1024)->change();
    });
}

public function down()
{
    Schema::table('email_histories', function (Blueprint $table) {
        // Revert back to the previous length
        $table->string('subject', 80)->change();
    });
}
};
