<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GroqRateLimit
{
    private const CACHE_KEY = 'groq_rate_limit';

    /**
     * Simpan info rate limit dari response Groq.
     * Selalu simpan (walau header kosong) biar formatForChat() bisa kasih feedback jelas.
     */
    public static function capture(Response $response): void
    {
        // Coba beberapa varian nama header (Groq kadang berubah)
        $get = function (array $names) use ($response): ?string {
            foreach ($names as $name) {
                $val = $response->header($name);
                if (! empty($val)) {
                    return $val;
                }
            }

            return null;
        };

        $requestsLimit = $get([
            'x-ratelimit-limit-requests',
            'x-ratelimit-limit-requests-day',
            'x-ratelimit-limit-requests-minute',
        ]);
        $requestsRemaining = $get([
            'x-ratelimit-remaining-requests',
            'x-ratelimit-remaining-requests-day',
            'x-ratelimit-remaining-requests-minute',
        ]);
        $requestsReset = $get([
            'x-ratelimit-reset-requests',
            'x-ratelimit-reset-requests-day',
            'x-ratelimit-reset-requests-minute',
        ]);

        $tokensLimit = $get([
            'x-ratelimit-limit-tokens',
            'x-ratelimit-limit-tokens-minute',
        ]);
        $tokensRemaining = $get([
            'x-ratelimit-remaining-tokens',
            'x-ratelimit-remaining-tokens-minute',
        ]);
        $tokensReset = $get([
            'x-ratelimit-reset-tokens',
            'x-ratelimit-reset-tokens-minute',
        ]);

        // Retry-After (kalau kena 429)
        $retryAfter = $get(['retry-after']);

        $data = [
            'requests_limit' => $requestsLimit,
            'requests_remaining' => $requestsRemaining,
            'requests_reset' => $requestsReset,
            'tokens_limit' => $tokensLimit,
            'tokens_remaining' => $tokensRemaining,
            'tokens_reset' => $tokensReset,
            'retry_after' => $retryAfter,
            'header_available' => ! empty($requestsLimit) || ! empty($tokensLimit),
            'http_status' => $response->status(),
            'captured_at' => now()->toIso8601String(),
        ];

        Cache::put(self::CACHE_KEY, $data, now()->addHours(24));

        // Log kalau header kosong (biar bisa debug)
        if (empty($data['header_available'])) {
            Log::info('Groq rate limit header kosong', [
                'status' => $response->status(),
                'headers' => $response->headers(),
            ]);
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

        // Belum ada data sama sekali
        if (! $data) {
            return " *Info Limit Groq*\n\n"
                ."Belum ada data limit yang tercatat.\n"
                .'Coba kirim pesan ke AI dulu (misal: "halo"), '
                .'baru ketik "cek limit" lagi ya. 😊';
        }

        // Ada data tapi header tidak tersedia
        if (empty($data['header_available'])) {
            $status = $data['http_status'] ?? '?';
            $time = isset($data['captured_at'])
                ? Carbon::parse($data['captured_at'])->diffForHumans()
                : 'baru saja';

            $msg = " *Info Limit Groq*\n\n"
                 ."Provider Groq tidak mengirim header rate limit untuk model ini.\n"
                 ."Status HTTP terakhir: *{$status}*\n"
                 ."Data per: {$time}\n\n"
                 ."Artinya: limit tetap berlaku, tapi aplikasi tidak bisa membaca sisa kuota secara langsung.\n"
                 .'Kalau kena error 429, tunggu 1-2 menit lalu coba lagi.';

            if (! empty($data['retry_after'])) {
                $msg .= "\n\n⏳ Retry-After: {$data['retry_after']} detik";
            }

            return $msg;
        }

        // Data lengkap
        $reqLimit = $data['requests_limit'] ?? '?';
        $reqRemaining = $data['requests_remaining'] ?? '?';
        $reqReset = $data['requests_reset'] ?? '-';
        $tokLimit = $data['tokens_limit'] ?? '?';
        $tokRemaining = $data['tokens_remaining'] ?? '?';
        $tokReset = $data['tokens_reset'] ?? '-';

        $reqPercent = (is_numeric($reqLimit) && is_numeric($reqRemaining) && $reqLimit > 0)
            ? round(($reqRemaining / $reqLimit) * 100)
            : null;

        $tokPercent = (is_numeric($tokLimit) && is_numeric($tokRemaining) && $tokLimit > 0)
            ? round(($tokRemaining / $tokLimit) * 100)
            : null;

        $capturedAt = isset($data['captured_at'])
            ? Carbon::parse($data['captured_at'])->diffForHumans()
            : 'baru saja';

        $out = " *Info Limit Groq*\n\n";

        $out .= "🔹 *Request*\n";
        $out .= "   Sisa: *{$reqRemaining}* / {$reqLimit}";
        if ($reqPercent !== null) {
            $out .= " ({$reqPercent}%)";
        }
        $out .= "\n   Reset dalam: {$reqReset}\n\n";

        $out .= "🔹 *Token*\n";
        $out .= "   Sisa: *{$tokRemaining}* / {$tokLimit}";
        if ($tokPercent !== null) {
            $out .= " ({$tokPercent}%)";
        }
        $out .= "\n   Reset dalam: {$tokReset}\n\n";

        $out .= " Data per: {$capturedAt}";

        return $out;
    }
}
