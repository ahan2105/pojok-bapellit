<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BannedLoginSession extends Model
{
    protected $fillable = [
        'user_id', 'ip_address', 'user_agent',
        'device', 'platform', 'browser',
        'banned_by', 'reason', 'banned_at',
    ];

    protected $casts = [
        'banned_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function banner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'banned_by');
    }
}
