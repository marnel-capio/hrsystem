<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    public $timestamps = false;

    protected $primaryKey = 'id';

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
        'updated_by',
        'create_time',
        'update_time',
    ];

    public function getAuthIdentifierName()
    {
        return 'email_address';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed', 
            'active_status' => 'boolean',
            'create_time' => 'datetime',
            'update_time' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }
}
