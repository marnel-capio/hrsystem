<?php
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class ActionBatchModel extends Model
{
    protected $table = 'action_batches';
    public $timestamps = false;
 
    protected $fillable = [
        'action_batch',
        'remarks',
        'created_by',
        'created_time',
        'updated_by',
        'updated_time',
    ];
}