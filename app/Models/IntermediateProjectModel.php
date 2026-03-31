<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class IntermediateProjectModel extends Model
{
    protected $table = 'projects';
 
    public $timestamps = true;
 
    protected $fillable = [
        'project_name',
        'remarks',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];
 
    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where('project_name', 'like', "%{$search}%");
        }
 
        return $query;
    }
 
    public static function getPaginated($search = null, $perPage = 20)
    {
        return self::query()
            ->search($search)
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }


    //TO BE USED IN RESOURCE REQS
    public static function getProjects($excludeScheduled = true)
    {
        $requisitionIds = IntermediateRequisitionModel::pluck('project_id')->toArray();

        $query = DB::table('projects')->select('id', 'project_name');

        if ($excludeScheduled) {
            $query->whereNotIn('id', $requisitionIds);
        } else {
            $query->whereIn('id', $requisitionIds);
        }

        return $query->get();
    }


    public function requisitions()
    {
        return $this->hasMany(IntermediateRequisitionModel::class, 'project_id');
    }

    const CREATED_AT = 'created_time';
    const UPDATED_AT = 'updated_time';
}