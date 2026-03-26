<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActionApplication extends Model
{
    protected $table = 'action_applicant_applications';

    public $timestamps = false;

    protected $fillable = [
        'action_applicant_id',
        'action_batch_id',
        'upload_resume',
        'exam_application_status',
        'exam_plan_date',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];

    public function applicant()
    {
        return $this->belongsTo(ActionApplicant::class, 'action_applicant_id', 'id');
    }
}
