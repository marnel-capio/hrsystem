<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $table = 'users';

    public $timestamps = false;

    protected $fillable = [
        'first_name', 'last_name', 'middle_name', 'address',
        'contact_no', 'email_address', 'password',
        'position', 'permissions', 'active_status',
        'created_by', 'updated_by',
    ];

    protected $hidden = [
        'password', 'remember_token',
        'two_factor_secret', 'two_factor_recovery_codes',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'active_status' => 'boolean',
    ];

    protected $appends = [
        'position_label', 'permission_label',
    ];

    public static function register(array $data): self
    {
        return self::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'address' => $data['address'],
            'contact_no' => $data['contact_no'],
            'email_address' => $data['email_address'],
            'password' => $data['password'],
            'position' => $data['position'],
            'permissions' => $data['permissions'],
            'active_status' => (int) $data['active_status'],
        ]);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }

    public static function getUsersForIndex()
    {
        return self::select(
            'id',
            'first_name',
            'middle_name',
            'last_name',
            'address',
            'contact_no',
            'email_address',
            'position',
            'active_status',
            'create_time'
        )
        ->orderBy('create_time', 'desc')
        ->get();
    }

    // ✅ Automatically handle create/update timestamps and by-user
    protected static function booted()
    {
        static::creating(function ($user) {
            $user->create_time = now();
            $user->update_time = now();
            $user->created_by = $user->created_by ?? auth()->id();
            $user->updated_by = $user->updated_by ?? auth()->id();
        });

        static::updating(function ($user) {
            $user->update_time = now();
            $user->updated_by = $user->updated_by ?? auth()->id();
        });
    }

    public static function findByEmail(string $email): ?self
    {
        return self::where('email_address', $email)->first();
    }

    public function getPositionLabelAttribute(): string
    {
        return config('constants.positions')[$this->position] ?? '';
    }

    public function getPermissionLabelAttribute(): string
    {
        return config('constants.permissionsList')[$this->permissions] ?? '';
    }

    //function used in resource schedule
    public static function hrRecruiters()
    {
    return self::where('permissions', 3)
               ->where('active_status', 1)
               ->get(['email_address', 'first_name', 'id']);
    }

    //functions used in action applications
    public function getRoleLabelAttribute(): string
{
    return match ((int) $this->permissions) {
        config('constants.HR_ADMIN_PERMISSION.value') => 'HR Admin',
        config('constants.HR_MANAGER_PERMISSION.value') => 'HR Manager',
        config('constants.HR_RECRUITER_PERMISSION.value') => 'HR Recruiter',
        config('constants.BU_MANAGER_PERMISSION.value') => 'BU Manager',
        config('constants.INTERVIEWER_PERMISSION.value') => 'Interviewer',
        config('constants.HR_PERMISSION.value') => 'HR',
        config('constants.WALKIN_PERMISSION.value') => 'Walk-in',
        default => 'User',
    };
}

public function scopeActionInterviewers($query)
{
    return $query->whereIn('permissions', [
        config('constants.HR_MANAGER_PERMISSION.value'),
        config('constants.HR_RECRUITER_PERMISSION.value'),
        config('constants.BU_MANAGER_PERMISSION.value'),
        config('constants.INTERVIEWER_PERMISSION.value'),
    ]);
}

public function toInterviewerOption(): array
{
    return [
        'id' => $this->id,
        'name' => $this->full_name ?: 'N/A',
        'role_label' => $this->role_label,
        'position' => $this->position,
        'permissions' => (int) $this->permissions,
        'email_address' => $this->email_address,
    ];
}

public function getFullNameAttribute(): string
{
    return trim($this->first_name . ' ' . $this->last_name);
}

    public function updateUser(array $data): array
    {
        // Step 1: old snapshot
        $oldData = $this->getOriginal();

        // Step 2: hash password if provided
        $rawPassword = null;
        if (! empty($data['password'])) {
            $rawPassword = $data['password'];
            $data['password'] = Hash::make($rawPassword);
        } else {
            // Remove password from $data so it doesn't overwrite old password
            unset($data['password']);
        }

        $data['updated_by'] = auth()->id();

        // Step 3: update the user
        $this->update($data);

        // Step 4: prepare new data for logging
        $newData = $this->fresh()->toArray();

        // Keep raw password for logging only if provided
        if ($rawPassword) {
            $newData['password'] = $rawPassword;
        }

        return [
            'old' => $oldData,
            'new' => $newData,
        ];
    }

}

