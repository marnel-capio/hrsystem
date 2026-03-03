<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Log extends Model
{
    protected $table = 'logs';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'module',
        'activity',
        'ip_address',
        'created_by',
        'updated_by',
        'create_time',
        'update_time',
    ];

    protected $casts = [
        'create_time' => 'datetime',
        'update_time' => 'datetime',
    ];

    /**
     * Static function to create logs easily
     */
    public static function createLog(
        string $module,
        string $activity,
        ?int $userId = null,
        ?string $ipAddress = null
    ): void {

        $userId = $userId ?? Auth::id(); 

        $ipAddress = $ipAddress ?? request()->ip();

        self::create([
            'module' => $module,
            'activity' => $activity,
            'ip_address' => $ipAddress,
            'created_by' => $userId,
            'updated_by' => $userId,
            'create_time' => now(),
            'update_time' => now(),
        ]);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
