<?php

namespace App\Services;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LogService
{
    /**
     * Create a detailed log for updated user fields
     */
    public function createUserUpdateLog(array $oldData, array $newData, ?int $userId = null): void
    {
        $userId = $userId ?? Auth::id();
        $ipAddress = request()->ip();
        $activityLines = [];
        $activityLines[] = "Updated user details for {$oldData['first_name']} {$oldData['last_name']}.";
        $activityLines[] = 'Details:';

        $fields = [
            'first_name', 'last_name', 'middle_name', 'address',
            'contact_no', 'email_address', 'password',
            'position', 'permissions', 'active_status',
        ];

        foreach ($fields as $field) {
            $oldValue = $oldData[$field] ?? null;
            $newValue = $newData[$field] ?? null;

            if ($field === 'password') {
                if (!empty($newValue)) {
                    $activityLines[] = "password: [HIDDEN] -> [UPDATED]";
                }
                continue;
            }

            $oldValueStr = is_bool($oldValue) ? (int) $oldValue : (string) $oldValue;
            $newValueStr = is_bool($newValue) ? (int) $newValue : (string) $newValue;

            if ($oldValueStr !== $newValueStr) {
                $activityLines[] = "{$field}: {$oldValueStr} -> {$newValueStr}";
            }
        }

        $activity = implode("\n", $activityLines);

        Log::create([
            'module' => 'Users',
            'activity' => $activity,
            'ip_address' => $ipAddress,
            'created_by' => $userId,
            'updated_by' => $userId,
            'create_time' => now(),
            'update_time' => now(),
        ]);
    }
}