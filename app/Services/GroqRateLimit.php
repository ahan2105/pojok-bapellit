<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;

class GroqRateLimit
{
    private const CACHE_KEY = 'groq_rate_limit';

    /**
     * Simpan info rate limit dari response Groq.
     */
    public static function capture(Response $response): void
    {
        $data = [
            'requests_limit'     => $response->header('x-ratelimit-limit-requests'),
            'requests_remaining' => $response->header('x-ratelimit-remaining-requests'),
            'requests_reset'     => $response->header('x-ratelimit-reset-requests'),
            'tokens_limit'       => $response->header('x-ratelimit-limit-tokens'),
            'tokens_remaining'   => $response->header('x-ratelimit-remaining-tokens'),
            'tokens_reset'       => $response->header('x-ratelimit-reset-tokens'),
            'captured_at'        => now()->toIso8601String(),
        ];

        // Cuma simpan kalau header-nya ada (bukan null)
        if ($data['requests_limit'] || $data['requests_remaining']) {
            Cache::put(self::CACHE_KEY, $data, now()->addHours(24));
        }
    }

    /**
     * Ambil info rate limit terakhir.
     */
    public static function get(): ?array
    {
        return Cache::get(self::CACHE_KEY);
    }

    /**
     * Format info rate limit jadi teks buat chatbot.
     */
    public static function formatForChat(): string
    {
        $data = self::get();

        if (!$data || empty($data['requests_remaining'])) {
            return "📊 *Info Limit*\n\n" .
                   "Maaf, data limit belum tersedia. Coba kirim pesan ke AI dulu " .
                   "(misal \"halo\"), baru ketik \"cek limit\" lagi ya. 😊";
        }

        $reqLimit     = $data['requests_limit'] ?? '?';
        $reqRemaining = $data['requests_remaining'] ?? '?';
        $reqReset     = $data['requests_reset'] ?? '-';
        $tokLimit     = $data['tokens_limit'] ?? '?';
        $tokRemaining = $data['tokens_remaining'] ?? '?';
        $tokReset     = $data['tokens_reset'] ?? '-';

        // Hitung persentase
        $reqPercent = ($reqLimit && is_numeric($reqLimit) && $reqLimit > 0)
            ? round(($reqRemaining / $reqLimit) * 100)
            : 0;

        $capturedAt = isset($data['captured_at'])
            ? \Carbon\Carbon::parse($data['captured_at'])->diffForHumans()
            : 'baru saja';

        return "📊 *Info Limit Groq*\n\n" .
               "🔹 *Request (RPD)*\n" .
               "   Sisa: *{$reqRemaining}* / {$reqLimit} ({$reqPercent}%)\n" .
               "   Reset dalam: {$reqReset}\n\n" .
               "🔹 *Token (TPM)*\n" .
               "   Sisa: *{$tokRemaining}* / {$tokLimit}\n" .
               "   Reset dalam: {$tokReset}\n\n" .
               "📅 Data per: {$capturedAt}";
    }
}