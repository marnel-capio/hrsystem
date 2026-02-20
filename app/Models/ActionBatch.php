<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class ActionBatch extends Model
{
    protected $table = 'action_batches';
 
    protected $fillable = [
        'action_batch',
        'status',
        'remarks',
        'created_by',
        'updated_by',
        'created_time',
        'updated_time'
    ];
 
    public $timestamps = false; // Using custom timestamps
}