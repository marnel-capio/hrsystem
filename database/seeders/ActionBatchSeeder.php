<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ActionBatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert 30 records into the action_batches table
        $data = [];
        for ($i = 1; $i <= 30; $i++) {
            $data[] = [
                'action_batch' => 'Batch_' . $i,
                'status' => 1,  // Default status (active)
                'remarks' => 'Test data for batch ' . $i,
                'created_by' => 1, // You can change this as needed
                'created_time' => Carbon::now(),
                'updated_by' => 1, // You can change this as needed
                'updated_time' => Carbon::now(),
                'target_trainees' => 'Trainee_' . $i,
                'target_date' => Carbon::now()->addDays($i)->toDateString(),
            ];
        }

        DB::table('action_batches')->insert($data);
    }
}