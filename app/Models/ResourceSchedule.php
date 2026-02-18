<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ResourceSchedule Model
 *
 * Represents a resource schedule entity with batch details, location, trainees, deployment date,
 * and a work breakdown schedule (WBS). Includes audit fields for tracking creation and updates.
 */
class ResourceSchedule extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * These fields can be filled via mass assignment (e.g., create() or update()).
     */
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

    /**
     * The attributes that should be cast.
     *
     * 'wbs' is cast to an array for easy manipulation.
     * 'created_time' and 'updated_time' are cast to datetime for Carbon instances.
     */
    protected $casts = [
        'wbs' => 'array',
        'created_time' => 'datetime',
        'updated_time' => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * Set to false because we use custom audit fields ('created_time', 'updated_time')
     * instead of Laravel's default 'created_at' and 'updated_at'.
     */
    public $timestamps = false;

    /**
     * Boot the model and set up event listeners.
     *
     * Automatically sets audit fields ('created_by', 'created_time', 'updated_by', 'updated_time')
     * based on the authenticated user when creating or updating records.
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $user = auth()->user();
            if ($user) {
                $model->created_by = $user->name;  // Store user's name (or ID if preferred)
                $model->created_time = now();
            }
        });

        static::updating(function ($model) {
            $user = auth()->user();
            if ($user) {
                $model->updated_by = $user->name;  // Store user's name (or ID if preferred)
                $model->updated_time = now();
            }
        });
    }
}