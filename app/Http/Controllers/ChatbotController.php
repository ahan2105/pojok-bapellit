<?php

namespace App\Http\Controllers;

use App\Services\GroqRateLimit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    /**
     * Endpoint utama chat.
     * 0. Cek keyword "cek limit" → balas info limit (0 token)
     * 1. Cek template (exact match keyword) → 0 token
     * 2. Kalau gak ketemu → panggil Groq
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = trim($request->message);
        $lower   = mb_strtolower($message);

        // ═══════════════════════════════════════════════════════
        // LAPIS 0: CEK LIMIT — deteksi keyword
        // ═══════════════════════════════════════════════════════
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

        // ═══════════════════════════════════════════════════════
        // LAPIS 1: CEK TEMPLATE
        // ═══════════════════════════════════════════════════════
        $template = $this->matchTemplate($message);
        if ($template) {
            return response()->json([
                'reply'  => $template['reply'],
                'source' => 'template',
                'id'     => $template['id'],
            ]);
        }

        // ═══════════════════════════════════════════════════════
        // LAPIS 2: FALLBACK KE GROQ
        // ═══════════════════════════════════════════════════════
        return $this->askGroq($message);
    }

    /**
     * Return daftar menu buat ditampilin di awal chat.
     */
    public function menus()
    {
        $menus = collect(config('chatbot_templates', []))
            ->map(fn($tpl) => [
                'id'    => $tpl['id'],
                'label' => $tpl['label'],
            ])
            ->values();

        return response()->json(['menus' => $menus]);
    }

    /**
     * Ambil template berdasarkan ID (buat tombol menu).
     */
    public function template(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
        ]);

        $template = collect(config('chatbot_templates', []))
            ->firstWhere('id', $request->id);

        if (!$template) {
            return response()->json(['error' => 'Template tidak ditemukan'], 404);
        }

        return response()->json([
            'reply'  => $template['reply'],
            'source' => 'template',
            'id'     => $template['id'],
        ]);
    }

    /**
     * Cocokin pesan user dengan template.
     */
    private function matchTemplate(string $message): ?array
    {
        $lower     = mb_strtolower($message);
        $templates = config('chatbot_templates', []);

        foreach ($templates as $tpl) {
            foreach ($tpl['keywords'] as $keyword) {
                if (str_contains($lower, mb_strtolower($keyword))) {
                    return $tpl;
                }
            }
        }

        return null;
    }

    /**
     * Panggil Groq API — fallback.
     */
    private function askGroq(string $message)
    {
        $apiKey  = config('services.groq.key');
        $baseUrl = config('services.groq.url');
        $model   = config('services.groq.model', 'openai/gpt-oss-20b');

        if (empty($apiKey)) {
            return response()->json([
                'error' => 'GROQ_API_KEY belum di-set di .env'
            ], 500);
        }

        try {
            $response = Http::timeout(30)
                ->retry(3, 1000, function ($exception) {
                    return $exception instanceof \Illuminate\Http\Client\RequestException
                        && in_array($exception->response->status(), [429, 500, 502, 503]);
                }, throw: false)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json',
                ])
                ->post($baseUrl . '/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        [
                            'role'    => 'system',
                            'content' => 'Kamu adalah Asisten Bapelit, asisten AI untuk Bappeda (Badan Perencanaan Pembangunan Daerah). Jawab dengan ramah, singkat, dan informatif dalam Bahasa Indonesia. Maksimal 3 paragraf pendek.'
                        ],
                        [
                            'role'    => 'user',
                            'content' => $message
                        ]
                    ],
                    'temperature' => 0.7,
                    'max_tokens'  => 300,
                ]);

            // 🎯 Capture rate limit headers dari Groq (sebelum cek failed)
            GroqRateLimit::capture($response);

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

            $data  = $response->json();
            $reply = $data['choices'][0]['message']['content'] ?? 'Maaf, saya tidak mengerti.';

            return response()->json([
                'reply'  => $reply,
                'source' => 'groq',
            ]);

        } catch (\Exception $e) {
            Log::error('Chatbot Exception', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}