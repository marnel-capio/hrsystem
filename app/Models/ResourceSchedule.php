<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceSchedule extends Model
{
    // Disable default timestamps — using custom fields
    public $timestamps = false;

    protected $fillable = [
        'batch_name',
        'target_location',
        'target_trainees',
        'deployment_date',
        'wbs',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

    protected $casts = [
        'wbs'          => 'array',
        'created_time' => 'datetime',
        'updated_time' => 'datetime',
    ];
}