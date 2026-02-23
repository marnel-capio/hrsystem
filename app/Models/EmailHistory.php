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

    /**
     * Record an email attempt
     */
    public static function logEmail(
        int $status,
        string $subject,
        string $fromName,
        string $emailFrom,
        string $emailTo,
        string $emailBody,
        ?int $userId = null
    ) {
        return self::create([
            'status' => $status,
            'subject' => $subject,
            'from' => $fromName,
            'email_from' => $emailFrom,
            'email_to' => $emailTo,
            'email_body' => $emailBody,
            'created_by' => $userId,
            'updated_by' => $userId,
            'create_time' => now(),
            'update_time' => now(),
        ]);
    }
}