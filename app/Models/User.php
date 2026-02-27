<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $table = 'users';

    // ❌ Disable default timestamps
    public $timestamps = false;

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'email_address',
        'password',
        'address',
        'contact_no',
        'position',
        'permissions',
        'active_status',
        'created_by',
        'updated_by',
        'create_time',
        'update_time',
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
    ];

    public function getAuthIdentifierName()
    {
        return 'email_address';
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
}

