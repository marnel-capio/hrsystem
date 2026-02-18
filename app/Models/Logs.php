<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    protected $table = 'logs';

    protected $primaryKey = 'id';

    public $timestamps = false; // because you're not using created_at / updated_at

    protected $fillable = [
        'module',
        'activity',
        'ip_address',
        'created_by',
        'updated_by',
        'create_time',
        'update_time',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
