<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BannedLoginSession;
use App\Models\ChatbotTemplate;
use App\Models\LoginHistory;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        $soundFile = Setting::get('notification_sound_file', '/sounds/notif.mp3');
        $soundType = Setting::get('notification_sound_type', 'default');

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
        if (! empty($validated['keywords'])) {
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
        if (! empty($validated['keywords'])) {
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
     * Sesi & Perangkat — pantau login semua akun
     */
    public function sessions(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        $tab = $request->input('tab', 'aktif');

        $historiesQuery = LoginHistory::with('user:id,name,username,email,role')
            ->when($q !== '', function ($query) use ($q) {
                $query->whereHas('user', function ($u) use ($q) {
                    $u->where('name', 'like', "%{$q}%")
                        ->orWhere('username', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->latest('last_active_at');

        $histories = $historiesQuery->paginate(15)->withQueryString();

        // Sesi aktif dari tabel sessions (driver database)
        $activeSessions = collect();
        try {
            $activeSessions = DB::table('sessions')
                ->leftJoin('users', 'users.id', '=', 'sessions.user_id')
                ->whereNotNull('sessions.user_id')
                ->where('sessions.last_activity', '>', now()->subMinutes(30)->getTimestamp())
                ->select('sessions.*', 'users.name as user_name', 'users.username as user_username')
                ->orderByDesc('sessions.last_activity')
                ->limit(20)
                ->get()
                ->map(function ($s) {
                    $ua = $s->user_agent ?? '';
                    $s->platform = str_contains($ua, 'Windows') ? 'Windows' : (str_contains($ua, 'Android') ? 'Android' : (str_contains($ua, 'iPhone') ? 'iOS' : (str_contains($ua, 'Mac') ? 'macOS' : '')));
                    $s->device = str_contains(strtolower($ua), 'mobile') ? 'Mobile' : 'Desktop';

                    return $s;
                });
        } catch (\Throwable $e) {
            // abaikan jika driver bukan database
        }

        $stats = [
            'totalUsers' => User::count(),
            'online' => LoginHistory::where('last_active_at', '>', now()->subMinutes(5))->distinct('user_id')->count('user_id'),
            'totalHistories' => LoginHistory::count(),
            'banned' => BannedLoginSession::count(),
        ];

        $banned = BannedLoginSession::with(['user:id,name,username', 'banner:id,name'])
            ->latest('banned_at')->paginate(15, ['*'], 'banned_page')->withQueryString();

        $templates = ChatbotTemplate::latest()->get();

        return view('admin.settings.index', compact('histories', 'activeSessions', 'stats', 'templates', 'banned', 'tab'));
    }

    public function destroySession(Request $request, LoginHistory $history)
    {
        // hapus sesi DB juga kalau cocok
        try {
            DB::table('sessions')->where('user_id', $history->user_id)
                ->where('ip_address', $history->ip_address)->delete();
        } catch (\Throwable $e) {
        }
        $history->delete();

        return back()->with('success', 'Sesi login berhasil dihapus.');
    }

    public function destroyActiveSession(Request $request, string $id)
    {
        DB::table('sessions')->where('id', $id)->delete();

        return back()->with('success', 'Sesi aktif dihapus.');
    }

    public function banSession(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'ip_address' => 'nullable|string|max:45',
            'user_agent' => 'nullable|string|max:2000',
            'reason' => 'nullable|string|max:500',
        ]);

        $ua = $request->input('user_agent') ?? '';
        $ip = $request->input('ip_address');

        // detect device
        $device = str_contains(strtolower($ua), 'mobile') ? 'Mobile' : (str_contains(strtolower($ua), 'tablet') || str_contains(strtolower($ua), 'ipad') ? 'Tablet' : 'Desktop');
        $platform = str_contains($ua, 'Windows') ? 'Windows' : (str_contains($ua, 'Android') ? 'Android' : (str_contains($ua, 'iPhone') ? 'iOS' : (str_contains($ua, 'Mac') ? 'macOS' : 'Unknown')));
        $browser = str_contains($ua, 'Edg/') ? 'Edge' : (str_contains($ua, 'Chrome/') ? 'Chrome' : (str_contains($ua, 'Firefox/') ? 'Firefox' : (str_contains($ua, 'Safari') ? 'Safari' : 'Unknown')));

        BannedLoginSession::create([
            'user_id' => $request->user_id,
            'ip_address' => $ip,
            'user_agent' => $ua ?: null,
            'device' => $device,
            'platform' => $platform,
            'browser' => $browser,
            'banned_by' => $request->user()->id,
            'reason' => $request->reason,
            'banned_at' => now(),
        ]);

        // tendang sesi aktif yang cocok
        try {
            $q = DB::table('sessions')->where('user_id', $request->user_id);
            if ($ip) {
                $q->where('ip_address', $ip);
            }
            $q->delete();
        } catch (\Throwable $e) {
        }

        // notif ke user
        try {
            DB::table('notifications')->insert([
                'user_id' => $request->user_id,
                'type' => 'session_banned',
                'title' => 'Sesi diblokir admin',
                'message' => 'Sesi '.($ip ?? '-').' diblokir. Alasan: '.($request->reason ?: '-'),
                'data' => json_encode(['ip' => $ip, 'reason' => $request->reason]),
                'url' => null,
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
        }

        return back()->with('success', 'Sesi/IP berhasil diblokir. User akan dipaksa logout.');
    }

    public function unbanSession(BannedLoginSession $banned)
    {
        $banned->delete();

        return back()->with('success', 'Blokir sesi berhasil dibuka.');
    }

    /**
     * Reset Suara ke Default
     */
    public function resetSound()
    {
        // 1. Ambil path lama dari database
        $oldSound = Setting::get('notification_sound_file');

        // 2. Hapus file fisik jika custom
        if ($oldSound && str_contains($oldSound, 'custom-sounds')) {
            $relativePath = str_replace(['/storage/', 'storage/'], '', $oldSound);
            if (Storage::disk('public')->exists($relativePath)) {
                Storage::disk('public')->delete($relativePath);
                Log::info('File suara custom berhasil dihapus: '.$relativePath);
            }
        }

        // 3. Reset ke default
        Setting::set('notification_sound_file', '/sounds/notif.mp3');
        Setting::set('notification_sound_type', 'default');

        // 4. Hapus cache
        Cache::flush();

        return back()->with('success', 'Suara custom telah dihapus dan dikembalikan ke default.');
    }
}
