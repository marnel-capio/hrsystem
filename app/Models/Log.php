<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

    // Existing log methods...
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

    /**
     * Create a detailed log for updated user fields
     *
     * @param  array  $oldData  Original user data
     * @param  array  $newData  Updated user data
     * @param  int|null  $userId  ID of the user performing the update
     */
    public static function createUserUpdateLog(array $oldData, array $newData, ?int $userId = null): void
    {
        $userId = $userId ?? Auth::id();
        $ipAddress = request()->ip();
        $activityLines = [];
        $activityLines[] = "Updated user details for {$oldData['first_name']} {$oldData['last_name']}.";
        $activityLines[] = 'Details:';

        // Compare old vs new for each relevant field
        $fields = [
            'first_name', 'last_name', 'middle_name', 'address',
            'contact_no', 'email_address', 'password',
            'position', 'permissions', 'active_status',
        ];

        foreach ($fields as $field) {
    $oldValue = $oldData[$field] ?? null;
    $newValue = $newData[$field] ?? null;

    // --- Password handling ---
    if ($field === 'password') {
        if (!empty($newValue)) {
            // Only log that password was updated
            $activityLines[] = "password: [HIDDEN] -> [UPDATED]";
        }
        continue; // skip normal logging for password
    }

    // Cast values to string for comparison (for active_status and others)
    $oldValueStr = is_bool($oldValue) ? (int) $oldValue : (string) $oldValue;
    $newValueStr = is_bool($newValue) ? (int) $newValue : (string) $newValue;

    // Only log if changed
    if ($oldValueStr !== $newValueStr) {
        $activityLines[] = "{$field}: {$oldValueStr} -> {$newValueStr}";
    }
}

        $activity = implode("\n", $activityLines);

        // Insert directly into DB
        DB::table('logs')->insert([
            'module' => 'Users',
            'activity' => $activity,
            'ip_address' => $ipAddress,
            'created_by' => $userId,
            'updated_by' => $userId,
            'create_time' => now(),
            'update_time' => now(),
        ]);
    }

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
