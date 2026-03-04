<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class ActionBatchModel extends Model
{
    protected $table = 'action_batches';
 
    public $timestamps = false;
 
    protected $fillable = [
        'action_batch',
        'target_trainees',
        'target_date',
        'remarks',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];
 
    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where('action_batch', 'like', "%{$search}%");
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
}