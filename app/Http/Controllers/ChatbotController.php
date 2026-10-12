<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use App\Models\ChatbotTemplate;
use App\Models\ChatHistory;
use App\Models\Kendaraan;
use App\Models\User;
use App\Services\GroqRateLimit;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    // ── Pengaturan hemat token ────────────────────────────────────
    private const CACHE_TTL_NORMAL = 7200;   // 2 jam

    private const CACHE_TTL_LIVE = 600;    // 10 menit

    private const CACHE_TTL_KB = 600;    // 10 menit cache knowledge base

    private const MAX_TOKENS_NORMAL = 800;

    private const MAX_TOKENS_LIVE = 1500;

    private const GROQ_CALLS_PER_MIN_PER_IP = 8;

    private const KB_TOP_TEMPLATES = 3;

    private const KB_CHARS_PER_TEMPLATE = 500;

    private const KB_MAX_VEHICLES = 100;

    private const KB_MAX_USERS = 200;

    // Batas history yang dikirim ke AI
    private const HISTORY_LIMIT = 10;

    /**
     * Endpoint utama chat.
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = trim($request->message);
        $lower = mb_strtolower($message);
        $sessionId = $request->session()->getId();
        $userId = Auth::id();

        ChatHistory::log($sessionId, 'user', $message, $userId);

        // LAPIS 0: CEK LIMIT
        $limitKeywords = [
            'cek limit', 'sisa limit', 'limit ai', 'limit groq',
            'rate limit', 'berapa limit', 'quota', 'kuota',
            'sisa kuota', 'cek kuota',
        ];

        foreach ($limitKeywords as $kw) {
            if (str_contains($lower, $kw)) {
                $reply = GroqRateLimit::formatForChat();
                ChatHistory::log($sessionId, 'assistant', $reply, $userId);

                return response()->json([
                    'reply' => $reply,
                    'source' => 'limit',
                ]);
            }
        }

        // LAPIS 1: PENCARIAN UMUM DI DATABASE (Aula dll)
        $generalDatabaseReply = $this->searchGeneralDatabase($lower);
        if ($generalDatabaseReply !== null) {
            ChatHistory::log($sessionId, 'assistant', $generalDatabaseReply, $userId);

            return response()->json([
                'reply' => $generalDatabaseReply,
                'source' => 'database_general',
            ]);
        }

        // LAPIS 2: DATA KENDARAAN SPESIFIK (plat / merk / pemakai)
        $vehicleReply = $this->answerVehicleQuestion($message, $lower);
        if ($vehicleReply !== null) {
            ChatHistory::log($sessionId, 'assistant', $vehicleReply, $userId);

            return response()->json([
                'reply' => $vehicleReply,
                'source' => 'database_vehicle',
            ]);
        }

        // LAPIS 3: RINGKASAN KENDARAAN (jumlah, pajak jatuh tempo)
        $summaryReply = $this->answerVehicleSummaryQuestion(
            $lower,
            Kendaraan::with(['pajakTahunanAktif', 'pajakLimaTahunanAktif'])->get()
        );
        if ($summaryReply !== null) {
            ChatHistory::log($sessionId, 'assistant', $summaryReply, $userId);

            return response()->json([
                'reply' => $summaryReply,
                'source' => 'database_vehicle_summary',
            ]);
        }

        // LAPIS 4: DATA PEGAWAI DAN ORGANISASI
        $organizationReply = $this->answerOrganizationQuestion($lower);
        if ($organizationReply !== null) {
            ChatHistory::log($sessionId, 'assistant', $organizationReply, $userId);

            return response()->json([
                'reply' => $organizationReply,
                'source' => 'database_users',
            ]);
        }

        // LAPIS 5: TEMPLATE
        $template = $this->matchTemplate($message);
        if ($template) {
            ChatHistory::log($sessionId, 'assistant', $template['reply'], $userId);

            return response()->json([
                'reply' => $template['reply'],
                'source' => $template['source'] ?? 'template',
                'id' => $template['id'] ?? null,
            ]);
        }

        // LAPIS 6: CACHE
        $cacheKey = 'chatbot:ans:'.md5(preg_replace('/\s+/u', ' ', $lower));
        if ($cached = Cache::get($cacheKey)) {
            ChatHistory::log($sessionId, 'assistant', $cached, $userId);

            return response()->json([
                'reply' => $cached,
                'source' => 'cache',
            ]);
        }

        // LAPIS 7: GROQ
        return $this->askGroq($message, $lower, $cacheKey, $sessionId, $userId);
    }

    /**
     * Endpoint untuk hapus history session user.
     */
    public function clearHistory(Request $request)
    {
        $sessionId = $request->session()->getId();
        ChatHistory::where('session_id', $sessionId)->delete();

        return response()->json(['success' => true, 'message' => 'History dibersihkan.']);
    }

    /**
     * Pencarian umum ke tabel non-spesifik (Aula dll).
     */
    private function searchGeneralDatabase(string $lower): ?string
    {
        if (strlen($lower) < 5 || preg_match('/\b(halo|hi|hai|terima kasih|ok|oke|iya|tidak)\b/iu', $lower)) {
            return null;
        }

        // ── AULA ─────────────────────────────────────────
        if (preg_match('/\b(aula|ruangan|ruang|gedung)\b/iu', $lower)) {
            $isGeneral = (bool) preg_match('/\b(info|daftar|list|semua|apa saja|ada apa|berapa|jumlah|total)\b/iu', $lower);

            if ($isGeneral) {
                $aulas = Aula::all(['nama', 'lokasi']);

                if ($aulas->isEmpty()) {
                    return 'Belum ada data aula di database.';
                }

                $lines = $aulas->map(function ($a, $i) {
                    $nama = trim((string) $a->nama);
                    $lokasi = trim(preg_replace('/[📍📌]/u', '', (string) $a->lokasi));

                    return ($i + 1).'. '.$nama.($lokasi !== '' ? ' — Lokasi: '.$lokasi : ' — Lokasi belum diisi');
                });

                return "Ada {$aulas->count()} aula terdaftar:\n".$lines->implode("\n");
            }

            // Pencarian spesifik
            $searchTerm = trim(preg_replace(
                '/\b(info|daftar|list|aula|ruangan|ruang|lokasi|gedung|apa|saja|ada|berapa|dimana|di mana|yang)\b/iu',
                '',
                $lower
            ));

            if ($searchTerm !== '' && mb_strlen($searchTerm) >= 3) {
                $aulas = Aula::where('nama', 'LIKE', "%$searchTerm%")
                    ->orWhere('lokasi', 'LIKE', "%$searchTerm%")
                    ->get();

                if ($aulas->isNotEmpty()) {
                    $lines = $aulas->map(function ($a, $i) {
                        $nama = trim((string) $a->nama);
                        $lokasi = trim(preg_replace('/[📍📌]/u', '', (string) $a->lokasi));

                        return ($i + 1).'. '.$nama.($lokasi !== '' ? ' — Lokasi: '.$lokasi : ' — Lokasi belum diisi');
                    });

                    return "Ditemukan {$aulas->count()} aula:\n".$lines->implode("\n");
                }
            }

            // Pertanyaan tentang aula tapi tidak ada match — STOP
            return 'Belum ada data aula yang cocok. Coba sebutkan nama atau lokasi aula lebih spesifik.';
        }

        return null;
    }

    /**
     * Daftar menu cepat (DB + Config).
     */
    public function menus()
    {
        $dbMenus = ChatbotTemplate::where('is_active', true)
            ->get(['id', 'label'])
            ->map(fn ($tpl) => [
                'id' => 'db_'.$tpl->id,
                'label' => $tpl->label,
            ])
            ->values();

        $configMenus = collect(config('chatbot_templates', []))
            ->map(fn ($tpl) => [
                'id' => $tpl['id'],
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
                    'reply' => $tpl->reply,
                    'source' => 'database_template',
                    'id' => $id,
                ]);
            }
        } else {
            $tpl = collect(config('chatbot_templates', []))->firstWhere('id', $id);

            if ($tpl) {
                return response()->json([
                    'reply' => $tpl['reply'],
                    'source' => 'config_template',
                    'id' => $id,
                ]);
            }
        }

        return response()->json(['error' => 'Template tidak ditemukan'], 404);
    }

    /**
     * Menjawab pertanyaan kendaraan SPESIFIK (plat / merk / nama pemakai).
     * Kalau cuma pertanyaan umum, return null biar diteruskan ke summary.
     */
    private function answerVehicleQuestion(string $message, string $lower): ?string
    {
        $generalTopicWords = [
            'kendaraan', 'mobil', 'motor', 'plat', 'pajak', 'pemakai', 'dipakai', 'pakai',
            'jatuh', 'tempo', 'merk', 'habis', 'abis', 'berakhir', 'kadaluarsa', 'expired',
            'pkb', 'stnk', 'samsat', 'cek', 'info', 'data', 'daftar', 'list', 'semua',
            'apa', 'yang', 'siapa', 'berapa', 'jumlah', 'total', 'segera', 'dekat', 'mau',
            'mana', 'dimana', 'kapan', 'tolong', 'lihat', 'cari', 'dong', 'nih', 'ya',
            'untuk', 'ini', 'itu', 'dengan', 'dan', 'atau',
        ];

        $queryTokens = collect(preg_split('/[^\p{L}\p{N}]+/u', $lower, -1, PREG_SPLIT_NO_EMPTY))
            ->filter(fn (string $token): bool => mb_strlen($token) >= 3)
            ->values();

        $meaningfulTokens = $queryTokens
            ->reject(fn (string $token): bool => in_array($token, $generalTopicWords, true))
            ->values();

        if ($meaningfulTokens->isEmpty()) {
            return null;
        }

        $hasNumericToken = $meaningfulTokens->contains(fn (string $t): bool => (bool) preg_match('/\d/', $t));
        $hasStrongKeyword = $meaningfulTokens->contains(fn (string $t): bool => mb_strlen($t) >= 4);

        if (! $hasNumericToken && ! $hasStrongKeyword) {
            return null;
        }

        $normalize = static fn (string $value): string => preg_replace('/[^\p{L}\p{N}]/u', '', mb_strtolower($value)) ?? '';
        $normalizedQueryTokens = $meaningfulTokens->map($normalize)->filter()->values();

        $vehicles = Kendaraan::with(['pajakTahunanAktif', 'pajakLimaTahunanAktif'])->get();

        $matches = $vehicles->filter(function (Kendaraan $item) use ($normalizedQueryTokens, $normalize): bool {
            if ($normalizedQueryTokens->isEmpty()) {
                return false;
            }

            $plat = $normalize((string) $item->plat_nomor);
            $pemakai = $normalize((string) $item->pemakai);
            $merk = $normalize((string) $item->merk);

            foreach ($normalizedQueryTokens as $token) {
                if ($plat !== '' && preg_match('/\b'.preg_quote($token, '/').'\b/u', $plat)) {
                    return true;
                }

                if ($merk !== '' && mb_strlen($token) >= 3 && preg_match('/\b'.preg_quote($token, '/').'\b/u', $merk)) {
                    return true;
                }

                if ($pemakai !== '' && mb_strlen($token) >= 4 && preg_match('/\b'.preg_quote($token, '/').'\b/u', $pemakai)) {
                    return true;
                }
            }

            return false;
        })->values();

        if ($matches->isEmpty()) {
            return null;
        }

        $answers = $matches->map(function (Kendaraan $vehicle): string {
            $answer = "Plat {$vehicle->plat_nomor}: dipakai oleh ".($vehicle->pemakai ?: 'belum diisi');
            if ($vehicle->merk) {
                $answer .= ", merk {$vehicle->merk}";
            }
            if ($vehicle->unit_kerja) {
                $answer .= ", unit {$vehicle->unit_kerja}";
            }
            $answer .= '.';

            $taxes = collect([
                ['label' => 'Pajak tahunan',   'tax' => $vehicle->pajakTahunanAktif],
                ['label' => 'Pajak 5 tahunan', 'tax' => $vehicle->pajakLimaTahunanAktif],
            ])->filter(fn (array $item): bool => $item['tax'] !== null);

            if ($taxes->isEmpty()) {
                return $answer.' Belum ada data pajak aktif.';
            }

            foreach ($taxes as $item) {
                $tax = $item['tax'];
                $days = $tax->sisa_hari;
                $remaining = $days < 0
                    ? 'terlambat '.abs($days).' hari'
                    : ($days === 0 ? 'jatuh tempo hari ini' : "tersisa {$days} hari");
                $answer .= " {$item['label']} sampai {$tax->tanggal_jatuh_tempo->format('d-m-Y')} ({$remaining}).";
            }

            return $answer;
        });

        return $matches->count() === 1
            ? $answers->first()
            : "Ditemukan {$matches->count()} kendaraan:\n- ".$answers->implode("\n- ");
    }

    /**
     * Ringkasan kendaraan (jumlah, pajak terdekat).
     */
    private function answerVehicleSummaryQuestion(string $lower, Collection $vehicles): ?string
    {
        $asksCount = preg_match('/\b(berapa|jumlah|ada berapa|total)\b/iu', $lower) === 1;

        if ($asksCount && preg_match('/\b(kendaraan|motor|mobil|unit)\b/iu', $lower) === 1) {
            $motorCount = $vehicles->filter(fn (Kendaraan $v): bool => preg_match('/\b(motor|zx|vario|beat|scoopy|nmax|matic)\b/iu', (string) $v->merk) === 1)->count();
            $carCount = $vehicles->filter(fn (Kendaraan $v): bool => preg_match('/\b(mobil|toyota|daihatsu|gran|max|avanza|inova|suv)\b/iu', (string) $v->merk) === 1)->count();

            return "Saat ini ada {$vehicles->count()} kendaraan terdaftar"
                .($motorCount || $carCount ? ": {$motorCount} motor dan {$carCount} mobil." : '.');
        }

        $mentionsPajak = preg_match('/\b(pajak|pkb|stnk|samsat)\b/iu', $lower) === 1;

        if ($mentionsPajak) {
            $limitDays = 30;
            if (preg_match('/\b(60|dua bulan|2 bulan)\b/iu', $lower) === 1) {
                $limitDays = 60;
            } elseif (preg_match('/\b(7|tujuh|seminggu|1 minggu|satu minggu)\b/iu', $lower) === 1) {
                $limitDays = 7;
            } elseif (preg_match('/\b(14|dua minggu|2 minggu)\b/iu', $lower) === 1) {
                $limitDays = 14;
            } elseif (preg_match('/\b(90|tiga bulan|3 bulan)\b/iu', $lower) === 1) {
                $limitDays = 90;
            }

            $taxes = $vehicles->flatMap(fn (Kendaraan $v) => collect([
                ['vehicle' => $v, 'type' => 'Pajak tahunan',   'tax' => $v->pajakTahunanAktif],
                ['vehicle' => $v, 'type' => 'Pajak 5 tahunan', 'tax' => $v->pajakLimaTahunanAktif],
            ]))
                ->filter(fn (array $item): bool => $item['tax'] !== null
                    && $item['tax']->sisa_hari >= 0
                    && $item['tax']->sisa_hari <= $limitDays
                )
                ->sortBy(fn (array $item): int => $item['tax']->sisa_hari);

            if ($taxes->isEmpty()) {
                return "Belum ada pajak kendaraan yang jatuh tempo dalam {$limitDays} hari ke depan. Semua pajak masih aman.";
            }

            $lines = $taxes->map(function (array $item, int $i): string {
                $tax = $item['tax'];
                $sisa = $tax->sisa_hari;

                if ($sisa === 0) {
                    $status = 'JATUH TEMPO HARI INI';
                } elseif ($sisa <= 7) {
                    $status = "{$sisa} hari lagi (URGENT)";
                } else {
                    $status = "{$sisa} hari lagi";
                }

                $pemakai = $item['vehicle']->pemakai ?: 'belum diisi';

                return ($i + 1).". {$item['vehicle']->plat_nomor} ({$pemakai})\n"
                     ."   {$item['type']} — jatuh tempo {$tax->tanggal_jatuh_tempo->format('d-m-Y')} ({$status})";
            });

            return "Ada {$taxes->count()} pajak yang jatuh tempo dalam {$limitDays} hari ke depan:\n"
                .$lines->implode("\n");
        }

        return null;
    }

    /**
     * Pertanyaan pegawai / organisasi.
     */
    private function answerOrganizationQuestion(string $lower): ?string
    {
        $organizationTerms = ['user', 'pegawai', 'orang', 'karyawan', 'kepala', 'sekretaris', 'sekretariat', 'jabatan', 'bidang', 'staf'];
        $isOrganizationQuestion = collect($organizationTerms)->contains(fn (string $term): bool => str_contains($lower, $term));

        if (! $isOrganizationQuestion) {
            return null;
        }

        $users = User::query()
            ->where('status', 'aktif')
            ->where(function ($query): void {
                $query->where('role', '!=', 'admin')->orWhereNull('role');
            })
            ->get(['name', 'jabatan', 'bidang', 'username']);

        if ($users->isEmpty()) {
            return 'Belum ada data pegawai aktif di database.';
        }

        $asksCount = preg_match('/\b(berapa|jumlah|total|ada)\b/iu', $lower) === 1;
        if ($asksCount && preg_match('/\b(user|pegawai|orang|karyawan|staf)\b/iu', $lower) === 1) {
            return "Ada {$users->count()} pegawai aktif yang tercatat di database.";
        }

        if (preg_match('/\b(kepala|pimpinan|kepala badan)\b/iu', $lower) === 1) {
            $leaders = $users->filter(fn (User $user): bool => str_contains(mb_strtolower((string) $user->bidang), 'kepala badan')
                || str_contains(mb_strtolower((string) $user->jabatan), 'kepala badan')
            );

            return $leaders->isEmpty()
                ? 'Data kepala badan belum ditemukan di database.'
                : 'Kepala Badan: '.$leaders->map(fn (User $u): string => $u->name.' ('.($u->jabatan ?: 'jabatan belum diisi').')')->implode('; ').'.';
        }

        if (preg_match('/\b(sekretaris|sekretariat)\b/iu', $lower) === 1) {
            $secretaries = $users->filter(fn (User $user): bool => str_contains(mb_strtolower((string) $user->jabatan), 'sekretaris')
                || (str_contains(mb_strtolower((string) $user->bidang), 'sekretariat')
                    && str_contains(mb_strtolower((string) $user->jabatan), 'sekret'))
            );

            return $secretaries->isEmpty()
                ? 'Data sekretaris belum ditemukan di database.'
                : 'Sekretaris: '.$secretaries->map(fn (User $u): string => $u->name.' ('.($u->jabatan ?: 'jabatan belum diisi').')')->implode('; ').'.';
        }

        if (preg_match('/\b(bidang|jabatan)\b/iu', $lower) === 1) {
            return 'Data pegawai tersedia berdasarkan bidang dan jabatan. Sebutkan nama bidang atau jabatan yang ingin dicari.';
        }

        return null;
    }

    /**
     * Normalisasi keywords.
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
            array_map(fn ($k) => mb_strtolower(trim((string) $k)), (array) $raw),
            fn ($k) => $k !== ''
        ));
    }

    /**
     * Match template. Prioritas: Database > Config.
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
                'reply' => $dbTemplate->reply,
                'id' => 'db_'.$dbTemplate->id,
                'source' => 'database_template',
            ];
        }

        foreach (config('chatbot_templates', []) as $tpl) {
            foreach ($this->normalizeKeywords($tpl['keywords'] ?? []) as $keyword) {
                if (str_contains($lower, $keyword)) {
                    return [
                        'reply' => $tpl['reply'],
                        'id' => $tpl['id'],
                        'source' => 'config_template',
                    ];
                }
            }
        }

        return null;
    }

    // ═══════════════════════════════════════════════════════════
    // KNOWLEDGE BASE
    // ═══════════════════════════════════════════════════════════

    private function allTemplates(): array
    {
        $list = [];

        foreach (ChatbotTemplate::where('is_active', true)->get(['label', 'reply', 'keywords']) as $t) {
            $list[] = [
                'label' => (string) $t->label,
                'reply' => (string) $t->reply,
                'keywords' => $t->keywords,
            ];
        }

        foreach (config('chatbot_templates', []) as $t) {
            if (empty($t['label']) || empty($t['reply'])) {
                continue;
            }
            $list[] = [
                'label' => (string) $t['label'],
                'reply' => (string) $t['reply'],
                'keywords' => $t['keywords'] ?? [],
            ];
        }

        return $list;
    }

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
            fn ($w) => mb_strlen($w) >= 3 && ! in_array($w, $stop, true)
        )));
    }

    private function needsDatabaseContext(string $lower): bool
    {
        $keywords = [
            'kendaraan', 'mobil', 'motor', 'plat', 'pajak', 'pemakai', 'dipakai', 'pakai',
            'merk', 'jatuh tempo', 'pajak tahunan', 'pajak 5',
            'pegawai', 'user', 'karyawan', 'staf', 'bidang', 'jabatan', 'kepala', 'sekretaris',
            'aula', 'ruangan', 'ruang', 'lokasi', 'gedung',
        ];

        foreach ($keywords as $kw) {
            if (str_contains($lower, $kw)) {
                return true;
            }
        }

        return false;
    }

    private function buildDynamicKnowledgeBase(): string
    {
        return Cache::remember('chatbot:kb:dynamic', self::CACHE_TTL_KB, function () {
            $kb = [];

            $vehicles = Kendaraan::with(['pajakTahunanAktif', 'pajakLimaTahunanAktif'])
                ->limit(self::KB_MAX_VEHICLES)
                ->get();

            if ($vehicles->isNotEmpty()) {
                $kb[] = 'DATA KENDARAAN ('.$vehicles->count().' unit):';
                foreach ($vehicles as $k) {
                    $line = "- {$k->plat_nomor}";
                    if ($k->merk) {
                        $line .= " ({$k->merk})";
                    }
                    if ($k->pemakai) {
                        $line .= " — pemakai: {$k->pemakai}";
                    }
                    if ($k->unit_kerja) {
                        $line .= " — unit: {$k->unit_kerja}";
                    }

                    if ($k->pajakTahunanAktif) {
                        $line .= ' — pajak tahunan jatuh tempo: '.$k->pajakTahunanAktif->tanggal_jatuh_tempo->format('d-m-Y');
                    }
                    if ($k->pajakLimaTahunanAktif) {
                        $line .= ' — pajak 5 tahunan: '.$k->pajakLimaTahunanAktif->tanggal_jatuh_tempo->format('d-m-Y');
                    }
                    $kb[] = $line;
                }
            }

            $users = User::where('status', 'aktif')
                ->limit(self::KB_MAX_USERS)
                ->get(['name', 'jabatan', 'bidang']);

            if ($users->isNotEmpty()) {
                $kb[] = "\nDATA PEGAWAI AKTIF (".$users->count().' orang):';
                foreach ($users as $u) {
                    $kb[] = "- {$u->name}".($u->jabatan ? " — {$u->jabatan}" : '').($u->bidang ? " — {$u->bidang}" : '');
                }
            }

            $aulas = Aula::all(['nama', 'lokasi']);
            if ($aulas->isNotEmpty()) {
                $kb[] = "\nDATA AULA:";
                foreach ($aulas as $a) {
                    $lokasi = trim(preg_replace('/[📍📌]/u', '', (string) $a->lokasi));
                    $kb[] = "- {$a->nama}".($lokasi !== '' ? " (Lokasi: {$lokasi})" : '');
                }
            }

            return implode("\n", $kb);
        });
    }

    private function buildTemplateKnowledgeBase(string $message): string
    {
        $words = $this->tokenize($message);
        $scored = [];

        foreach ($this->allTemplates() as $t) {
            $hay = mb_strtolower(
                $t['label'].' '.implode(' ', $this->normalizeKeywords($t['keywords'])).' '.$t['reply']
            );

            $score = 0;
            foreach ($words as $w) {
                if (str_contains($hay, $w)) {
                    $score++;
                }
            }
            $scored[] = ['score' => $score, 't' => $t];
        }

        usort($scored, fn ($a, $b) => $b['score'] <=> $a['score']);

        $titles = Str::limit(implode(', ', array_map(fn ($s) => $s['t']['label'], $scored)), 400);
        $out = "Topik tersedia di sistem: {$titles}";

        $top = array_slice(array_filter($scored, fn ($s) => $s['score'] > 0), 0, self::KB_TOP_TEMPLATES);
        foreach ($top as $s) {
            $out .= "\n\n### ".$s['t']['label']."\n"
                .Str::limit(trim($s['t']['reply']), self::KB_CHARS_PER_TEMPLATE);
        }

        return $out;
    }

    private function buildKnowledgeBase(string $message): string
    {
        $out = $this->buildTemplateKnowledgeBase($message);

        if ($this->needsDatabaseContext(mb_strtolower($message))) {
            $dynamic = $this->buildDynamicKnowledgeBase();
            if ($dynamic !== '') {
                $out .= "\n\n=== DATA DATABASE ===\n".$dynamic;
            }
        }

        return $out;
    }

    private function needsWebSearch(string $lower): bool
    {
        return (bool) preg_match(
            '/\b(jadwal|skor|klasemen|hasil pertandingan|hari ini|besok|kemarin|terbaru|terkini|berita|cuaca|harga|kurs|saham|update|live|covid|vaksin|penyakit|resep|restoran|hotel)\b/iu',
            $lower
        );
    }

    /**
     * System prompt: 4 mode jawaban (internal, umum, obrolan santai, curhat berat).
     */
    private function buildSystemPrompt(string $message, bool $live): string
    {
        $kb = $this->buildKnowledgeBase($message);
        $date = now('Asia/Jakarta')->locale('id')->translatedFormat('l, d F Y H:i').' WIB';

        $searchRule = $live
            ? 'Pertanyaan ini butuh data terbaru: GUNAKAN pencarian web untuk jawaban akurat dan terkini.'
            : 'Jawab dari pengetahuanmu. Jika ragu soal data yang bisa berubah, katakan datanya mungkin sudah berubah.';

        return <<<PROMPT
Kamu Asisten Bapelit: asisten AI untuk sistem internal "Pojok Bapelit" sekaligus teman ngobrol yang wajar. Sekarang: {$date}.

CARA MENJAWAB (pilih sesuai konteks):

A. PERTANYAAN DATA INTERNAL (kendaraan, pegawai, aula, pajak, prosedur Pojok Bapelit):
   Jawab dari KNOWLEDGE BASE di bawah. Kalau tidak ada, bilang belum ada dan sarankan hubungi admin. JANGAN mengarang.

B. PERTANYAAN UMUM (sejarah, sains, teknologi, budaya, resep, tips):
   Jawab dari pengetahuanmu sendiri dengan lengkap. Kalau butuh data terbaru (jadwal, harga, berita, cuaca), pakai web search. {$searchRule}

C. OBROLAN SANTAI / SINGKAT (contoh: "oke deh", "thanks", "halo", "apasi", "gajelas", "wkwk", "hehe", komentar singkat, feedback):
   - Balas SINGKAT, santai, wajar seperti teman ngobrol. 1-2 kalimat aja.
   - JANGAN sok jadi konselor. JANGAN tawarin bantuan psikolog/darurat kecuali user JELAS-JELAS curhat berat.
   - Contoh balasan untuk "oke deh" -> "Sip!" atau "Oke, kalau ada yang mau ditanya lagi, tinggal chat aja ya."
   - Contoh balasan untuk "apasi gajelas" -> "Maaf ya, aku coba perjelas. Bagian mana yang bikin bingung?" atau "Haha sorry, aku jelasin ulang ya maksudnya..."

D. CURHAT BERAT (contoh: "aku capek banget hidupku", "aku pengen nyerah", "aku sedih banget, gak sanggup lagi"):
   - HANYA di kasus ini baru pakai mode empatik: paragraf natural, hangat, tidak menghakimi.
   - Dengar dulu, validasi perasaannya, baru beri respons ringan.
   - Maksimal 3-4 kalimat. Jangan menggurui.
   - Kalau ada indikasi bahaya serius (menyakiti diri), baru sebutkan layanan bantuan profesional.
   - INGAT konteks percakapan sebelumnya.

ATURAN GAYA (berlaku semua):
- Langsung ke inti. Tanpa salam pembuka berlebihan.
- JANGAN pakai markdown (**, ##, dsb).
- List bernomor/dash HANYA untuk informasi faktual (A atau B). Untuk obrolan (C atau D), pakai PARAGRAF.
- Bahasa Indonesia santai-sopan, wajar, jangan berlebihan.
- JANGAN menawarkan "layanan darurat 119" atau "psikolog" kecuali user benar-benar menunjukkan tanda-tanda krisis.
- JANGAN mengulang-ulang "aku di sini untuk mendengarkan" — itu bikin cringe.

KNOWLEDGE BASE:
{$kb}
PROMPT;
    }

    // ═══════════════════════════════════════════════════════════
    // GROQ
    // ═══════════════════════════════════════════════════════════

    private function askGroq(string $message, string $lower, string $cacheKey, string $sessionId, ?int $userId)
    {
        $apiKey = config('services.groq.key');
        $baseUrl = config('services.groq.url');
        $model = config('services.groq.model', 'openai/gpt-oss-20b');

        if (empty($apiKey)) {
            return response()->json([
                'error' => 'GROQ_API_KEY belum di-set di .env',
            ], 500);
        }

        $rlKey = 'chatbot-groq:'.request()->ip();
        if (RateLimiter::tooManyAttempts($rlKey, self::GROQ_CALLS_PER_MIN_PER_IP)) {
            $wait = RateLimiter::availableIn($rlKey);

            return response()->json([
                'error' => "Terlalu banyak pertanyaan. Coba lagi dalam {$wait} detik.",
            ], 429);
        }
        RateLimiter::hit($rlKey, 60);

        $isGptOss = str_contains($model, 'gpt-oss');
        $live = $isGptOss && $this->needsWebSearch($lower);

        try {
            $history = ChatHistory::recentForSession($sessionId, self::HISTORY_LIMIT);

            if (! empty($history) && end($history)['role'] === 'user' && end($history)['content'] === $message) {
                array_pop($history);
            }

            $messages = array_merge(
                [['role' => 'system', 'content' => $this->buildSystemPrompt($message, $live)]],
                $history,
                [['role' => 'user', 'content' => $message]]
            );

            $payload = [
                'model' => $model,
                'messages' => $messages,
                'temperature' => 0.4,
                'max_tokens' => $live ? self::MAX_TOKENS_LIVE : self::MAX_TOKENS_NORMAL,
            ];

            if ($isGptOss) {
                $payload['reasoning_effort'] = 'low';
            }

            if ($live) {
                $payload['tools'] = [['type' => 'browser_search']];
                $payload['tool_choice'] = 'auto';
            }

            $response = Http::timeout($live ? 30 : 15)
                ->retry(2, 500, function ($exception) {
                    if ($exception instanceof ConnectionException) {
                        return true;
                    }

                    return $exception instanceof RequestException
                        && in_array($exception->response->status(), [429, 500, 502, 503]);
                }, throw: false)
                ->withToken($apiKey)
                ->acceptJson()
                ->post(rtrim($baseUrl, '/').'/chat/completions', $payload);

            try {
                GroqRateLimit::capture($response);
            } catch (\Throwable $e) {
                Log::warning('GroqRateLimit capture gagal', ['message' => $e->getMessage()]);
            }

            if ($response->failed()) {
                Log::error('Groq API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                if ($response->status() === 429) {
                    $reply = 'Maaf, saya sedang sibuk sebentar ini. Coba tanya lagi dalam 1-2 menit ya.';
                    ChatHistory::log($sessionId, 'assistant', $reply, $userId);

                    return response()->json([
                        'reply' => $reply,
                        'source' => 'rate_limited',
                    ]);
                }

                return response()->json([
                    'error' => 'Gagal menghubungi AI (HTTP '.$response->status().')',
                    'detail' => app()->isLocal() ? $response->json() : null,
                ], 500);
            }

            $reply = (string) $response->json('choices.0.message.content');
            $reply = trim(preg_replace('/【[^】]*】/u', '', $reply));

            if ($reply === '') {
                Log::warning('Groq balas kosong', ['body' => $response->json()]);

                $reply = 'Maaf, saya belum bisa menjawab itu. Coba ulangi dengan kalimat lain.';
                ChatHistory::log($sessionId, 'assistant', $reply, $userId);

                return response()->json([
                    'reply' => $reply,
                    'source' => 'groq',
                ]);
            }

            ChatHistory::log($sessionId, 'assistant', $reply, $userId);

            Cache::put($cacheKey, $reply, $live ? self::CACHE_TTL_LIVE : self::CACHE_TTL_NORMAL);

            return response()->json([
                'reply' => $reply,
                'source' => $live ? 'groq_search' : 'groq',
                'usage' => app()->isLocal() ? $response->json('usage') : null,
            ]);

        } catch (\Throwable $e) {
            Log::error('Chatbot Exception', ['message' => $e->getMessage()]);

            return response()->json([
                'error' => 'Terjadi kesalahan: '.$e->getMessage(),
            ], 500);
        }
    }
}
