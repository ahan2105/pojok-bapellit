<?php

namespace App\Http\Controllers;

use App\Models\ChatbotTemplate;
use App\Services\GroqRateLimit;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    // ── Pengaturan hemat token (silakan disesuaikan) ──────────────
    private const CACHE_TTL_NORMAL = 7200;  // 2 jam: jawaban umum / seputar sistem
    private const CACHE_TTL_LIVE   = 600;   // 10 menit: jawaban hasil search (data cepat basi)
    private const MAX_TOKENS_NORMAL = 700;
    private const MAX_TOKENS_LIVE   = 1800; // search butuh ruang lebih
    private const GROQ_CALLS_PER_MIN_PER_IP = 8;
    private const KB_TOP_TEMPLATES = 3;     // maksimal template detail yang dikirim ke AI
    private const KB_CHARS_PER_TEMPLATE = 500;

    /**
     * Endpoint utama chat.
     * 0. Keyword "cek limit"   → info limit (0 token)
     * 1. Template DB & Config  → 0 token
     * 2. Cache jawaban sama    → 0 token
     * 3. Groq (konteks relevan saja, search hanya jika perlu)
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = trim($request->message);
        $lower   = mb_strtolower($message);

        // LAPIS 0: CEK LIMIT
        $limitKeywords = [
            'cek limit', 'sisa limit', 'limit ai', 'limit groq',
            'rate limit', 'berapa limit', 'quota', 'kuota',
            'sisa kuota', 'cek kuota',
        ];

        foreach ($limitKeywords as $kw) {
            if (str_contains($lower, $kw)) {
                return response()->json([
                    'reply'  => GroqRateLimit::formatForChat(),
                    'source' => 'limit',
                ]);
            }
        }

        // LAPIS 1: TEMPLATE
        $template = $this->matchTemplate($message);
        if ($template) {
            return response()->json([
                'reply'  => $template['reply'],
                'source' => $template['source'] ?? 'template',
                'id'     => $template['id'] ?? null,
            ]);
        }

        // LAPIS 2: CACHE (pertanyaan sama dari siapa pun → 0 token)
        $cacheKey = 'chatbot:ans:' . md5(preg_replace('/\s+/u', ' ', $lower));
        if ($cached = Cache::get($cacheKey)) {
            return response()->json([
                'reply'  => $cached,
                'source' => 'cache',
            ]);
        }

        // LAPIS 3: GROQ
        return $this->askGroq($message, $lower, $cacheKey);
    }

    /**
     * Daftar menu cepat (DB + Config).
     */
    public function menus()
    {
        $dbMenus = ChatbotTemplate::where('is_active', true)
            ->get(['id', 'label'])
            ->map(fn($tpl) => [
                'id'    => 'db_' . $tpl->id,
                'label' => $tpl->label,
            ])
            ->values();

        $configMenus = collect(config('chatbot_templates', []))
            ->map(fn($tpl) => [
                'id'    => $tpl['id'],
                'label' => $tpl['label'],
            ])
            ->values();

        return response()->json([
            'menus' => $dbMenus->merge($configMenus)->values(),
        ]);
    }

    /**
     * Ambil template berdasarkan ID (tombol menu).
     */
    public function template(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
        ]);

        $id = $request->id;

        if (str_starts_with($id, 'db_')) {
            $tpl = ChatbotTemplate::find(substr($id, 3));

            if ($tpl && $tpl->is_active) {
                return response()->json([
                    'reply'  => $tpl->reply,
                    'source' => 'database_template',
                    'id'     => $id,
                ]);
            }
        } else {
            $tpl = collect(config('chatbot_templates', []))->firstWhere('id', $id);

            if ($tpl) {
                return response()->json([
                    'reply'  => $tpl['reply'],
                    'source' => 'config_template',
                    'id'     => $id,
                ]);
            }
        }

        return response()->json(['error' => 'Template tidak ditemukan'], 404);
    }

    // ═══════════════════════════════════════════════════════════
    // TEMPLATE MATCHING
    // ═══════════════════════════════════════════════════════════

    /**
     * Normalisasi keywords apa pun bentuknya (array, "a, b", JSON string,
     * JSON ter-encode dua kali) jadi array lowercase tanpa string kosong.
     */
     /**
     * Normalisasi keywords apa pun bentuknya (array, "a, b", JSON string,
     * JSON ter-encode dua kali) jadi array lowercase tanpa string kosong.
     */
    private function normalizeKeywords(mixed $raw): array
    {
        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            if (is_string($decoded)) {
                $decoded = json_decode($decoded, true);
            }
            $raw = is_array($decoded) ? $decoded : preg_split('/\s*,\s*/', $raw);
        }

        return array_values(array_filter(
            array_map(fn($k) => mb_strtolower(trim((string) $k)), (array) $raw),
            fn($k) => $k !== ''
        ));
    }

    /**
     * Cocokkan pesan dengan template. Prioritas: Database > Config.
     */
    private function matchTemplate(string $message): ?array
    {
        $lower = mb_strtolower($message);

        $dbTemplate = ChatbotTemplate::where('is_active', true)
            ->get()
            ->first(function ($t) use ($lower) {
                foreach ($this->normalizeKeywords($t->keywords) as $keyword) {
                    if (str_contains($lower, $keyword)) {
                        return true;
                    }
                }
                return false;
            });

        if ($dbTemplate) {
            return [
                'reply'  => $dbTemplate->reply,
                'id'     => 'db_' . $dbTemplate->id,
                'source' => 'database_template',
            ];
        }

        foreach (config('chatbot_templates', []) as $tpl) {
            foreach ($this->normalizeKeywords($tpl['keywords'] ?? []) as $keyword) {
                if (str_contains($lower, $keyword)) {
                    return [
                        'reply'  => $tpl['reply'],
                        'id'     => $tpl['id'],
                        'source' => 'config_template',
                    ];
                }
            }
        }

        return null;
    }

    // ═══════════════════════════════════════════════════════════
    // KONTEKS UNTUK AI (dibuat sekecil mungkin)
    // ═══════════════════════════════════════════════════════════

    /** Gabungan template aktif dari DB + Config. */
    private function allTemplates(): array
    {
        $list = [];

        foreach (ChatbotTemplate::where('is_active', true)->get(['label', 'reply', 'keywords']) as $t) {
            $list[] = [
                'label'    => (string) $t->label,
                'reply'    => (string) $t->reply,
                'keywords' => $t->keywords,
            ];
        }

        foreach (config('chatbot_templates', []) as $t) {
            if (empty($t['label']) || empty($t['reply'])) {
                continue;
            }
            $list[] = [
                'label'    => (string) $t['label'],
                'reply'    => (string) $t['reply'],
                'keywords' => $t['keywords'] ?? [],
            ];
        }

        return $list;
    }

    /** Pecah teks jadi kata kunci bermakna (tanpa kata umum). */
    private function tokenize(string $text): array
    {
        static $stop = [
            'yang', 'dan', 'untuk', 'apa', 'itu', 'ini', 'saya', 'aku', 'kamu', 'dong',
            'nih', 'tolong', 'bisa', 'ada', 'dengan', 'atau', 'kapan', 'siapa', 'mau',
            'pengen', 'gimana', 'bagaimana', 'cara', 'dari', 'ke', 'di', 'yah', 'deh',
        ];

        $words = preg_split('/[^\p{L}\p{N}]+/u', mb_strtolower($text), -1, PREG_SPLIT_NO_EMPTY);

        return array_values(array_unique(array_filter(
            $words,
            fn($w) => mb_strlen($w) >= 3 && !in_array($w, $stop, true)
        )));
    }

    /**
     * Knowledge base hemat token:
     * - selalu kirim daftar judul topik (murah)
     * - detail isi hanya untuk template yang paling relevan dengan pertanyaan
     */
    private function buildKnowledgeBase(string $message): string
    {
        $words  = $this->tokenize($message);
        $scored = [];

        foreach ($this->allTemplates() as $t) {
            $hay = mb_strtolower(
                $t['label'] . ' ' . implode(' ', $this->normalizeKeywords($t['keywords'])) . ' ' . $t['reply']
            );

            $score = 0;
            foreach ($words as $w) {
                if (str_contains($hay, $w)) {
                    $score++;
                }
            }
            $scored[] = ['score' => $score, 't' => $t];
        }

        usort($scored, fn($a, $b) => $b['score'] <=> $a['score']);

        $titles = Str::limit(implode(', ', array_map(fn($s) => $s['t']['label'], $scored)), 400);
        $out    = "Topik yang tersedia di sistem: {$titles}";

        $top = array_slice(array_filter($scored, fn($s) => $s['score'] > 0), 0, self::KB_TOP_TEMPLATES);
        foreach ($top as $s) {
            $out .= "\n\n### " . $s['t']['label'] . "\n"
                . Str::limit(trim($s['t']['reply']), self::KB_CHARS_PER_TEMPLATE);
        }

        return $out;
    }

    /** Apakah pertanyaan butuh data terbaru dari web? (search itu mahal, jadi bersyarat) */
    private function needsWebSearch(string $lower): bool
    {
        return (bool) preg_match(
            '/\b(jadwal|skor|klasemen|hasil pertandingan|hari ini|besok|kemarin|minggu ini|bulan ini|tahun ini|terbaru|terkini|berita|kabar|cuaca|harga|kurs|saham|update|live|latest|news|lawan|pertandingan|siapa (presiden|gubernur|bupati|walikota|menteri|ketua))\b/iu',
            $lower
        );
    }

    /**
     * System prompt: fokus ke sistem, boleh di luar itu, jawaban tuntas dalam satu balasan.
     */
    private function buildSystemPrompt(string $message, bool $live): string
    {
        $kb   = $this->buildKnowledgeBase($message);
        $date = now('Asia/Jakarta')->locale('id')->translatedFormat('l, d F Y H:i') . ' WIB';

        $searchRule = $live
            ? "Pertanyaan ini butuh data terbaru: gunakan pencarian web, jangan menebak dari ingatan, dan sebutkan tanggal data yang ditemukan. Jika tidak ketemu, katakan terus terang."
            : "Jawab dari pengetahuanmu. Jika ragu soal data yang bisa berubah, katakan datanya mungkin sudah berubah.";

        return <<<PROMPT
Kamu Asisten Bapelit: asisten AI serbaguna sekaligus asisten resmi Bappeda dan sistem internal "Pojok Bapelit". Sekarang: {$date}.

ATURAN ISI:
1. Topik Pojok Bapelit/Bappeda: jawab berdasarkan KNOWLEDGE BASE. Jangan mengarang fitur, aturan, jam, atau prosedur yang tidak tertulis. Jika tidak ada infonya, bilang belum ada dan sarankan hubungi admin.
2. Topik lain: jawab seperti asisten AI pada umumnya. {$searchRule}

GAYA JAWABAN (hemat tapi tuntas):
- Langsung ke inti. Tanpa salam pembuka, basa-basi, mengulang pertanyaan, atau penutup seperti "semoga membantu".
- Beri jawaban lengkap dalam SATU balasan: sertakan detail yang biasanya ditanyakan lanjutan (tanggal, jam, lokasi, syarat, langkah, angka penting) agar pengguna tidak perlu bertanya lagi.
- Maksimal sekitar 120 kata. Untuk langkah atau banyak item pakai baris pendek diawali "-" atau angka.
- Bahasa Indonesia santai-sopan. Jangan pakai markdown (tanpa **, #, tabel).

KNOWLEDGE BASE:
{$kb}
PROMPT;
    }

    // ═══════════════════════════════════════════════════════════
    // GROQ
    // ═══════════════════════════════════════════════════════════

    private function askGroq(string $message, string $lower, string $cacheKey)
    {
        $apiKey  = config('services.groq.key');
        $baseUrl = config('services.groq.url');
        $model   = config('services.groq.model', 'openai/gpt-oss-20b');

        if (empty($apiKey)) {
            return response()->json([
                'error' => 'GROQ_API_KEY belum di-set di .env',
            ], 500);
        }

        // Batasi pemanggilan AI per IP supaya token tidak terkuras
        $rlKey = 'chatbot-groq:' . request()->ip();
        if (RateLimiter::tooManyAttempts($rlKey, self::GROQ_CALLS_PER_MIN_PER_IP)) {
            $wait = RateLimiter::availableIn($rlKey);
            return response()->json([
                'error' => "Terlalu banyak pertanyaan. Coba lagi dalam {$wait} detik.",
            ], 429);
        }
        RateLimiter::hit($rlKey, 60);

        $isGptOss = str_contains($model, 'gpt-oss');
        $live     = $isGptOss && $this->needsWebSearch($lower);

        try {
            $payload = [
                'model'       => $model,
                'messages'    => [
                    ['role' => 'system', 'content' => $this->buildSystemPrompt($message, $live)],
                    ['role' => 'user',   'content' => $message],
                ],
                'temperature' => 0.4,
                'max_tokens'  => $live ? self::MAX_TOKENS_LIVE : self::MAX_TOKENS_NORMAL,
            ];

            if ($isGptOss) {
                $payload['reasoning_effort'] = 'low';
            }

            // Search hanya diaktifkan kalau pertanyaannya memang butuh data terbaru
            if ($live) {
                $payload['tools']       = [['type' => 'browser_search']];
                $payload['tool_choice'] = 'auto';
            }

            $response = Http::timeout($live ? 60 : 30)
                ->retry(3, 1000, function ($exception) {
                    if ($exception instanceof ConnectionException) {
                        return true;
                    }
                    return $exception instanceof RequestException
                        && in_array($exception->response->status(), [429, 500, 502, 503]);
                }, throw: false)
                ->withToken($apiKey)
                ->acceptJson()
                ->post(rtrim($baseUrl, '/') . '/chat/completions', $payload);

            try {
                GroqRateLimit::capture($response);
            } catch (\Throwable $e) {
                Log::warning('GroqRateLimit capture gagal', ['message' => $e->getMessage()]);
            }

            if ($response->failed()) {
                Log::error('Groq API Error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                return response()->json([
                    'error'  => 'Gagal menghubungi AI (HTTP ' . $response->status() . ')',
                    'detail' => app()->isLocal() ? $response->json() : null,
                ], 500);
            }

            $reply = (string) $response->json('choices.0.message.content');
            // Buang penanda sitasi hasil browsing, contoh: 【2†L55-L60】
            $reply = trim(preg_replace('/【[^】]*】/u', '', $reply));

            if ($reply === '') {
                Log::warning('Groq balas kosong', ['body' => $response->json()]);

                return response()->json([
                    'reply'  => 'Maaf, saya belum bisa menjawab itu. Coba ulangi dengan kalimat lain.',
                    'source' => 'groq',
                ]);
            }

            // Simpan ke cache (jawaban kosong/gagal tidak di-cache)
            Cache::put($cacheKey, $reply, $live ? self::CACHE_TTL_LIVE : self::CACHE_TTL_NORMAL);

            return response()->json([
                'reply'  => $reply,
                'source' => $live ? 'groq_search' : 'groq',
                'usage'  => app()->isLocal() ? $response->json('usage') : null,
            ]);

        } catch (\Throwable $e) {
            Log::error('Chatbot Exception', ['message' => $e->getMessage()]);

            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}