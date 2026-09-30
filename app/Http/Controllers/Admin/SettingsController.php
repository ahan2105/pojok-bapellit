<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    /**
     * Tampilkan halaman pengaturan — Template Chatbot
     */
    public function index()
    {
        $templates = ChatbotTemplate::latest()->get();

        return view('admin.settings.index', compact('templates'));
    }

    /**
     * Tampilkan halaman Suara Notifikasi
     */
    public function sound()
    {
        // Ambil data langsung dari DB untuk menghindari cache model yang membandel
        $soundFile = DB::table('settings')->where('key', 'notification_sound_file')->value('value') ?? '/sounds/default-bell.mp3';
        $soundType = DB::table('settings')->where('key', 'notification_sound_type')->value('value') ?? 'default_bell';

        return view('admin.settings.index', compact('soundFile', 'soundType'));
    }

    /**
     * Update Template Chatbot
     */
    public function updateTemplate(Request $request, ChatbotTemplate $template)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'reply' => 'required|string',
            'keywords' => 'nullable|string',
        ]);

        $keywordsArray = [];
        if (!empty($validated['keywords'])) {
            $keywordsArray = array_filter(array_map('trim', explode(',', $validated['keywords'])));
        }

        $template->update([
            'label' => $validated['label'],
            'reply' => $validated['reply'],
            'keywords' => $keywordsArray,
        ]);

        return back()->with('success', 'Template chatbot berhasil diperbarui.');
    }

    /**
     * Tambah Template Chatbot Baru
     */
    public function storeTemplate(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'reply' => 'required|string',
            'keywords' => 'nullable|string',
        ]);

        $keywordsArray = [];
        if (!empty($validated['keywords'])) {
            $keywordsArray = array_filter(array_map('trim', explode(',', $validated['keywords'])));
        }

        ChatbotTemplate::create([
            'label' => $validated['label'],
            'reply' => $validated['reply'],
            'keywords' => $keywordsArray,
        ]);

        return back()->with('success', 'Template chatbot berhasil ditambahkan.');
    }

    /**
     * Hapus Template Chatbot
     */
    public function destroyTemplate(ChatbotTemplate $template)
    {
        $template->delete();
        return back()->with('success', 'Template berhasil dihapus.');
    }

    /**
     * Upload / Ganti Suara Notifikasi
     */
    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'notification_sound' => 'nullable|file|mimes:mp3,wav|max:2048',
        ]);

        if ($request->hasFile('notification_sound')) {
            // 1. Hapus file lama jika ada
            $oldSound = DB::table('settings')->where('key', 'notification_sound_file')->value('value');
            if ($oldSound && str_contains($oldSound, 'custom-sounds')) {
                $relativePath = str_replace(['/storage/', 'storage/'], '', $oldSound);
                Storage::disk('public')->delete($relativePath);
            }

            // 2. Simpan file baru
            $path = $request->file('notification_sound')->store('custom-sounds', 'public');

            // 3. Update DB secara langsung
            DB::table('settings')->updateOrInsert(
                ['key' => 'notification_sound_file'],
                ['value' => "/storage/{$path}", 'updated_at' => now()]
            );
            DB::table('settings')->updateOrInsert(
                ['key' => 'notification_sound_type'],
                ['value' => 'custom', 'updated_at' => now()]
            );

            Cache::flush();
        }

        return back()->with('success', 'Suara notifikasi berhasil diperbarui!');
    }

    /**
     * Reset Suara ke Default (Brute Force + Hapus File Pasti Berhasil)
     */
    public function resetSound()
    {
        // 1. Ambil path lama dari database
        $oldSound = DB::table('settings')->where('key', 'notification_sound_file')->value('value');

        // 2. Hapus file fisik dengan logika path yang aman
        if ($oldSound) {
            $relativePath = str_replace(['/storage/', 'storage/'], '', $oldSound);

            if (str_contains($relativePath, 'custom-sounds/') && Storage::disk('public')->exists($relativePath)) {
                Storage::disk('public')->delete($relativePath);

                if (Storage::disk('public')->exists($relativePath)) {
                    Log::warning('Gagal menghapus file suara custom: ' . $relativePath);
                } else {
                    Log::info('File suara custom berhasil dihapus: ' . $relativePath);
                }
            }
        }

        // 3. Update database secara langsung (tanpa model)
        DB::table('settings')->updateOrInsert(
            ['key' => 'notification_sound_file'],
            ['value' => '/sounds/default-bell.mp3', 'updated_at' => now()]
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'notification_sound_type'],
            ['value' => 'default_bell', 'updated_at' => now()]
        );

        // 4. Hapus semua cache agar perubahan instan
        Cache::flush();

        return back()->with('success', 'Suara custom telah dihapus permanen dan dikembalikan ke default.');
    }
}