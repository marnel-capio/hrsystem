<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActionApplication extends Model
{
    protected $table = 'action_applicant_applications';
    public $timestamps = false;

    public static function listPageData(?string $search = null)
    {
        return static::query()
            ->select(
                'action_applicant_applications.id',
                'action_applicant_applications.trainees_from',
                'action_applicant_applications.action_applicant_id',
                'action_applicant_applications.action_batch_id',
                'action_batches.action_batch',
                'action_applicants.first_name',
                'action_applicants.last_name'
            )
            ->join('action_batches', 'action_applicant_applications.action_batch_id', '=', 'action_batches.id')
            ->join('action_applicants', 'action_applicant_applications.action_applicant_id', '=', 'action_applicants.id') // <- plural now
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('action_applicants.first_name', 'like', "%{$search}%")
                      ->orWhere('action_applicants.last_name', 'like', "%{$search}%")
                      ->orWhere('action_batches.action_batch', 'like', "%{$search}%")
                      ->orWhereRaw("action_applicant_applications.trainees_from LIKE ?", ["%{$search}%"])
                      ->orWhere('action_applicant_applications.id', 'like', "%{$search}%");
                });
            })
            ->orderBy('action_applicant_applications.created_time', 'desc')
            ->get();
    }
}