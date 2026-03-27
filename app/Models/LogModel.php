<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LogModel extends Model
{
    protected $table = 'logs';

    public $timestamps = true;

    protected $fillable = [
        'module',
        'activity',
        'ip_address',
        'created_by',
        'create_time',
    ];

    public static function listPageData()
    {
        return static::query()
            ->leftJoin('users', 'users.id', '=', 'logs.created_by')
            ->select(
                'logs.*',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as created_by_name")
            )
            ->orderBy('logs.create_time', 'desc')
            ->get();
    }

    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}