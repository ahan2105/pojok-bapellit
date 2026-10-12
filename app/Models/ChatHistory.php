<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatHistory extends Model
{
    protected $fillable = [
        'session_id',
        'user_id',
        'role',
        'message',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Ambil history terakhir untuk session tertentu.
     * Default 5 pasang (user + assistant) = 10 baris.
     */
    public static function recentForSession(string $sessionId, int $limit = 10): array
    {
        return static::where('session_id', $sessionId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->reverse()
            ->map(fn ($row) => [
                'role' => $row->role,
                'content' => $row->message,
            ])
            ->values()
            ->toArray();
    }

    /**
     * Simpan 1 baris history.
     */
    public static function log(string $sessionId, string $role, string $message, ?int $userId = null): void
    {
        static::create([
            'session_id' => $sessionId,
            'user_id' => $userId,
            'role' => $role,
            'message' => $message,
        ]);
    }
}
