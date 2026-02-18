
<?php
// NOTE: Not the final resource table migration. This is being updated. Some columns present in DB tables are still not added here.

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
    Schema::create('resource_schedules', function (Blueprint $table) {
        $table->id();
        $table->string('batch_name');
        $table->string('target_location');
        $table->integer('target_trainees');
$table->string('deployment_date', 7);
        $table->json('wbs');
            $table->timestamp('created_time')->nullable();
            $table->timestamp('updated_time')->nullable();
            
            // Audit fields
            $table->string('created_by')->nullable(); 
            $table->string('updated_by')->nullable(); 
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_schedules');
    }
};
