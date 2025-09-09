<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsConfiguration extends Model
{
    protected $fillable = [
        'active_provider',
        'smsq_api_key',
        'smsq_client_id',
        'smsq_sender_id',
        'bulksmsbd_api_key',
        'bulksmsbd_sender_id',
    ];

    protected $casts = [
        'smsq_api_key' => 'encrypted',
        'bulksmsbd_api_key' => 'encrypted',
    ];
}