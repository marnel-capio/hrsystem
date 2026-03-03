<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class ActionBatchModel extends Model
{
    protected $table = 'action_batches';
 
    protected $fillable = [
        'action_batch',
        'remarks',
        'status',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];
 
    public $timestamps = false;
}