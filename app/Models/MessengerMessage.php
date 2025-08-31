<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessengerMessage extends Model
{
    protected $fillable = ['sender_id', 'recipient_id', 'message', 'raw_payload'];
    protected $casts = [
        'raw_payload' => 'array',
    ];
}
