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
        'first_name',
        'last_name',
        'middle_name',
        'address',
        'contact_no',
        'email_address',
        'password',
        'position',
        'permissions',
        'active_status',
        'created_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'active_status' => 'boolean',
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

    // ✅ Automatically handle create/update timestamps and by-user
    protected static function booted()
    {
        static::creating(function ($user) {
            $user->create_time = now();
            $user->update_time = now();
            $user->created_by = auth()->id() ?? null;
            $user->updated_by = auth()->id() ?? null;
        });

        static::updating(function ($user) {
            $user->update_time = now();
            $user->updated_by = auth()->id() ?? null;
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
}

