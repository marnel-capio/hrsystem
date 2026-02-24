<?php
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class ActionBatchModel extends Model
{
    protected $table = 'action_batches';
 
    protected $fillable = [
        'action_batch',
        'remarks'
    ];
}