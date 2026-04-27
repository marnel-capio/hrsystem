<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IntermediateProjectModel extends Model
{
    protected $table = 'projects';
 
    public $timestamps = true;
 
    protected $fillable = [
        'project_name',
        'project_description',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];


    //TO BE USED IN RESOURCE REQS
    public static function getProjects()
    {
        return DB::table('projects')
            ->select('id', 'project_name', 'project_description')
            ->orderBy('project_name')
            ->get();
    }


    public function requisitions()
    {
        return $this->hasMany(IntermediateRequisitionModel::class, 'project_id');
    }
    


    const CREATED_AT = 'created_time';
    const UPDATED_AT = 'updated_time';
}