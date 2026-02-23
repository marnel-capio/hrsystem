<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailHistory extends Model
{
    protected $table = 'email_histories';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'status',
        'subject',
        'from',
        'email_from',
        'email_to',
        'email_body',
        'created_by',
        'updated_by',
        'create_time',
        'update_time',
    ];
}