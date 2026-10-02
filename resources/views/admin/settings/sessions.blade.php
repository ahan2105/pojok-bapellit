<div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
    <div class="border-b border-gray-100 px-4 py-4 sm:px-6">
        <div class="flex flex-col gap-3">
            <div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 shrink-0">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </span>
                    Aktivitas Login & Perangkat
                </h2>
                <p class="mt-1 text-xs sm:text-sm text-gray-500">Kelola sesi login — hapus, blokir perangkat/IP, dan buka blokir.</p>
            </div>
            <form method="GET" action="{{ route('admin.settings.sessions') }}" class="flex gap-2 w-full">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama / username..." class="flex-1 min-w-0 rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 outline-none">
                <button class="shrink-0 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-black">Cari</button>
            </form>
        </div>
        {{-- Tabs scrollable --}}
        <div class="mt-4 flex gap-2 overflow-x-auto pb-1 -mx-1 px-1 snap-x">
            <a href="{{ route('admin.settings.sessions', ['tab'=>'aktif','q'=>request('q')]) }}" class="shrink-0 snap-start rounded-full px-4 py-2 text-xs font-semibold border {{ $tab==='aktif' ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-600 border-gray-200' }}">Sesi Aktif & Riwayat</a>
            <a href="{{ route('admin.settings.sessions', ['tab'=>'banned']) }}" class="shrink-0 snap-start rounded-full px-4 py-2 text-xs font-semibold border flex items-center gap-1.5 {{ $tab==='banned' ? 'bg-red-600 text-white border-red-600' : 'bg-white text-gray-600 border-gray-200' }}">🚫 Diblokir <span class="rounded-full bg-white/20 px-1.5 py-0.5 text-[10px]">{{ $stats['banned'] ?? 0 }}</span></a>
        </div>
    </div>

    {{-- Ringkasan --}}
    <div class="grid grid-cols-2 gap-3 px-4 py-4 sm:px-6 bg-gray-50/50 border-b border-gray-100">
        <div class="rounded-xl bg-white border border-gray-200 px-3 py-3 sm:px-4">
            <p class="text-[11px] sm:text-xs text-gray-500">Total Akun</p>
            <p class="text-lg sm:text-xl font-bold text-gray-900 mt-1">{{ $stats['totalUsers'] }}</p>
        </div>
        <div class="rounded-xl bg-white border border-gray-200 px-3 py-3 sm:px-4">
            <p class="text-[11px] sm:text-xs text-gray-500">Online (5 menit)</p>
            <p class="text-lg sm:text-xl font-bold text-emerald-600 mt-1">{{ $stats['online'] }}</p>
        </div>
        <div class="rounded-xl bg-white border border-gray-200 px-3 py-3 sm:px-4">
            <p class="text-[11px] sm:text-xs text-gray-500">Riwayat</p>
            <p class="text-lg sm:text-xl font-bold text-indigo-600 mt-1">{{ $stats['totalHistories'] }}</p>
        </div>
        <div class="rounded-xl bg-white border border-red-100 px-3 py-3 sm:px-4">
            <p class="text-[11px] sm:text-xs text-red-500">Diblokir</p>
            <p class="text-lg sm:text-xl font-bold text-red-600 mt-1">{{ $stats['banned'] ?? 0 }}</p>
        </div>
    </div>

@if($tab==='banned')
    {{-- TAB BANNED --}}
    {{-- Mobile cards --}}
    <div class="sm:hidden divide-y divide-gray-100">
        @forelse($banned as $b)
        <div class="p-4 space-y-3">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <div class="font-semibold text-sm text-gray-900">{{ $b->user->name ?? 'User #'.$b->user_id }}</div>
                    <div class="text-xs text-gray-400">{{ $b->user->username ?? '' }} · ID {{ $b->user_id }}</div>
                </div>
                <span class="text-[10px] text-gray-500">{{ $b->banned_at?->format('d M Y H:i') }}</span>
            </div>
            <div class="flex flex-wrap gap-1.5 items-center text-xs">
                <span class="font-mono bg-gray-100 rounded px-2 py-1 text-xs">{{ $b->ip_address ?? '-' }}</span>
                <span class="text-gray-500">{{ $b->platform }} · {{ $b->device }} · {{ $b->browser }}</span>
            </div>
            @if($b->reason)<div class="text-xs bg-red-50 border border-red-100 rounded-lg px-3 py-2 text-red-700">Alasan: {{ $b->reason }}</div>@endif
            <div class="text-[11px] text-gray-400">Oleh {{ $b->banner->name ?? '-' }}</div>
            <form action="{{ route('admin.settings.sessions.unban', $b) }}" method="POST" onsubmit="return confirm('Buka blokir sesi ini?')">
                @csrf @method('DELETE')
                <button class="w-full rounded-lg bg-emerald-600 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">Buka Blokir</button>
            </form>
        </div>
        @empty
        <div class="px-6 py-10 text-center text-sm text-gray-400">Belum ada sesi yang diblokir.</div>
        @endforelse
    </div>
    {{-- Desktop table --}}
    <div class="hidden sm:block overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-red-50 text-xs font-semibold text-red-700 border-y border-red-100">
                <tr>
                    <th class="px-4 py-3 text-left">Akun</th>
                    <th class="px-4 py-3 text-left">IP / Perangkat</th>
                    <th class="px-4 py-3 text-left">Alasan</th>
                    <th class="px-4 py-3 text-left">Diblokir</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($banned as $b)
                <tr class="hover:bg-red-50/50">
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-900 text-xs">{{ $b->user->name ?? 'User #'.$b->user_id }}</div>
                        <div class="text-[11px] text-gray-400">{{ $b->user->username ?? '' }} · ID {{ $b->user_id }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="font-mono text-xs bg-gray-100 rounded px-1.5 py-0.5 inline-block">{{ $b->ip_address ?? '-' }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $b->platform }} · {{ $b->device }} · {{ $b->browser }}</div>
                        <div class="text-[11px] text-gray-400 truncate max-w-[200px]" title="{{ $b->user_agent }}">{{ Str::limit($b->user_agent ?? '', 40) }}</div>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-600 max-w-[180px] truncate" title="{{ $b->reason }}">{{ $b->reason ?: '-' }}</td>
                    <td class="px-4 py-3 text-xs text-gray-500">
                        {{ $b->banned_at?->format('d M Y H:i') }}
                        <div class="text-[11px] text-gray-400">oleh {{ $b->banner->name ?? '-' }}</div>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <form action="{{ route('admin.settings.sessions.unban', $b) }}" method="POST" onsubmit="return confirm('Buka blokir sesi ini?')">
                            @csrf @method('DELETE')
                            <button class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700">Buka Blokir</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-10 text-center text-sm text-gray-400">Belum ada sesi yang diblokir.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($banned->hasPages())<div class="border-t border-gray-100 px-4 py-4">{{ $banned->withQueryString()->links() }}</div>@endif

@else

    {{-- Sesi Aktif --}}
    @if($activeSessions->count())
    <div class="px-4 py-4 sm:px-6 border-b border-gray-100">
        <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
            Sesi Aktif Saat Ini ({{ $activeSessions->count() }})
        </h3>
        {{-- Mobile --}}
        <div class="sm:hidden space-y-2">
            @foreach($activeSessions as $s)
            <div class="rounded-xl border border-gray-200 p-3 flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <div class="font-medium text-sm text-gray-900 truncate">{{ $s->user_name ?? '—' }}</div>
                    <div class="text-xs text-gray-500 font-mono">{{ $s->ip_address ?? '-' }} · {{ $s->platform }} {{ $s->device }}</div>
                    <div class="text-[11px] text-gray-400">{{ \Carbon\Carbon::createFromTimestamp($s->last_activity)->diffForHumans() }}</div>
                </div>
                <form action="{{ route('admin.settings.sessions.destroy-active', $s->id) }}" method="POST" onsubmit="return confirm('Hapus sesi aktif ini?')" class="shrink-0">
                    @csrf @method('DELETE')
                    <button class="rounded-lg bg-red-50 border border-red-200 px-3 py-2 text-xs font-semibold text-red-600">Kick</button>
                </form>
            </div>
            @endforeach
        </div>
        {{-- Desktop --}}
        <div class="hidden sm:block overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500">
                    <tr>
                        <th class="px-4 py-2.5 text-left">User</th>
                        <th class="px-4 py-2.5 text-left">IP</th>
                        <th class="px-4 py-2.5 text-left">Device</th>
                        <th class="px-4 py-2.5 text-left">Aktif</th>
                        <th class="px-4 py-2.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($activeSessions as $s)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2.5">
                            <div class="font-medium text-gray-900 text-xs">{{ $s->user_name ?? '—' }}</div>
                            <div class="text-[11px] text-gray-400">{{ $s->user_username ?? '' }}</div>
                        </td>
                        <td class="px-4 py-2.5 font-mono text-xs">{{ $s->ip_address ?? '-' }}</td>
                        <td class="px-4 py-2.5 text-xs text-gray-600">{{ $s->platform }} · {{ $s->device }}</td>
                        <td class="px-4 py-2.5 text-xs text-gray-500">{{ \Carbon\Carbon::createFromTimestamp($s->last_activity)->diffForHumans() }}</td>
                        <td class="px-4 py-2.5 text-right">
                            <form action="{{ route('admin.settings.sessions.destroy-active', $s->id) }}" method="POST" onsubmit="return confirm('Hapus sesi aktif ini?')" class="inline">
                                @csrf @method('DELETE')
                                <button class="rounded bg-red-50 border border-red-200 px-2.5 py-1 text-xs font-medium text-red-600 hover:bg-red-100">Kick</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Riwayat Login --}}
    {{-- Mobile cards --}}
    <div class="sm:hidden divide-y divide-gray-100">
        @forelse($histories as $h)
        <div class="p-4 space-y-3">
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-full bg-indigo-100 flex items-center justify-center text-xs font-bold text-indigo-700 shrink-0">{{ strtoupper(substr($h->user->name ?? '?',0,1)) }}</div>
                <div class="min-w-0 flex-1">
                    <div class="font-semibold text-sm text-gray-900 truncate">{{ $h->user->name ?? '—' }}</div>
                    <div class="text-xs text-gray-400 truncate">{{ $h->user->username ?? '' }} · {{ $h->user->role ?? '' }}</div>
                </div>
                @if($h->last_active_at && $h->last_active_at->gt(now()->subMinutes(5)))
                    <span class="rounded-full bg-emerald-50 border border-emerald-200 px-2 py-1 text-[10px] font-bold text-emerald-700 shrink-0">Online</span>
                @else
                    <span class="rounded-full bg-gray-100 border px-2 py-1 text-[10px] text-gray-500 shrink-0">Offline</span>
                @endif
            </div>
            <div class="flex flex-wrap gap-1.5 items-center">
                <span class="font-mono bg-gray-100 rounded px-2 py-1 text-xs">{{ $h->ip_address ?? '-' }}</span>
                <span class="rounded-full border px-2 py-1 text-[11px] {{ $h->device==='Mobile' ? 'bg-amber-50 border-amber-200 text-amber-700' : 'bg-blue-50 border-blue-200 text-blue-700' }}">{{ $h->device }}</span>
                <span class="text-xs text-gray-500">{{ $h->platform }} · {{ $h->browser }}</span>
            </div>
            <div class="text-xs text-gray-500">{{ $h->last_active_at?->format('d/m H:i') ?? '-' }} · {{ $h->last_active_at?->diffForHumans() }}</div>
            <div class="flex gap-2">
                <button onclick="openBanModal({{ $h->user_id }}, '{{ $h->ip_address }}', `{{ addslashes($h->user_agent ?? '') }}`, '{{ $h->user->name ?? '' }}')" class="flex-1 rounded-lg bg-amber-500 py-2.5 text-sm font-semibold text-white hover:bg-amber-600">Ban</button>
                <form action="{{ route('admin.settings.sessions.destroy', $h) }}" method="POST" onsubmit="return confirm('Hapus riwayat sesi ini?')" class="flex-1">
                    @csrf @method('DELETE')
                    <button class="w-full rounded-lg bg-white border border-gray-200 py-2.5 text-sm text-gray-700">Hapus</button>
                </form>
            </div>
        </div>
        @empty
        <div class="px-6 py-10 text-center text-sm text-gray-400">Belum ada riwayat.</div>
        @endforelse
    </div>
    {{-- Desktop table --}}
    <div class="hidden sm:block overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs font-semibold text-gray-500 border-y border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left">Akun</th>
                    <th class="px-4 py-3 text-left">IP</th>
                    <th class="px-4 py-3 text-left">Perangkat</th>
                    <th class="px-4 py-3 text-left">Terakhir Aktif</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($histories as $h)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2.5">
                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-xs font-bold text-indigo-700">{{ strtoupper(substr($h->user->name ?? '?',0,1)) }}</div>
                            <div>
                                <div class="font-medium text-gray-900 leading-none text-xs">{{ $h->user->name ?? '—' }}</div>
                                <div class="text-[11px] text-gray-400">{{ $h->user->username ?? '' }} · {{ $h->user->role ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 font-mono text-xs"><span class="bg-gray-100 rounded px-2 py-1">{{ $h->ip_address ?? '-' }}</span></td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1 flex-wrap">
                            <span class="rounded-full border px-2 py-0.5 text-[11px] {{ $h->device==='Mobile' ? 'bg-amber-50 border-amber-200 text-amber-700' : 'bg-blue-50 border-blue-200 text-blue-700' }}">{{ $h->device }}</span>
                            <span class="text-xs text-gray-500">{{ $h->platform }} · {{ $h->browser }}</span>
                        </div>
                        <div class="text-[11px] text-gray-400 truncate max-w-[180px]" title="{{ $h->user_agent }}">{{ Str::limit($h->user_agent ?? '', 35) }}</div>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-600">
                        {{ $h->last_active_at?->format('d/m H:i') ?? '-' }}
                        <div class="text-[11px] text-gray-400">{{ $h->last_active_at?->diffForHumans() }}</div>
                    </td>
                    <td class="px-4 py-3">
                        @if($h->last_active_at && $h->last_active_at->gt(now()->subMinutes(5)))
                            <span class="rounded-full bg-emerald-50 border border-emerald-200 px-2 py-0.5 text-[11px] font-semibold text-emerald-700">Online</span>
                        @else
                            <span class="rounded-full bg-gray-100 border px-2 py-0.5 text-[11px] text-gray-500">Offline</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex justify-end gap-1">
                            <button onclick="openBanModal({{ $h->user_id }}, '{{ $h->ip_address }}', `{{ addslashes($h->user_agent ?? '') }}`, '{{ $h->user->name ?? '' }}')" class="rounded bg-amber-500 px-2.5 py-1 text-xs font-semibold text-white hover:bg-amber-600">Ban</button>
                            <form action="{{ route('admin.settings.sessions.destroy', $h) }}" method="POST" onsubmit="return confirm('Hapus riwayat sesi ini?')">
                                @csrf @method('DELETE')
                                <button class="rounded bg-white border border-gray-200 px-2.5 py-1 text-xs text-gray-600 hover:bg-gray-50">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-10 text-center text-sm text-gray-400">Belum ada riwayat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($histories->hasPages())<div class="border-t border-gray-100 px-4 py-4">{{ $histories->withQueryString()->links() }}</div>@endif
@endif

    <div class="border-t border-gray-100 bg-amber-50/50 px-4 py-3 text-xs leading-relaxed text-amber-800">
        🚫 <b>Ban</b> = user dipaksa logout & tidak bisa login lagi sampai dibuka. <b>Hapus</b> hanya menghapus riwayat/log.
    </div>
</div>

{{-- Modal Ban - bottom sheet di HP --}}
<div id="banModal" class="hidden fixed inset-0 z-50 items-end sm:items-center justify-center bg-black/50 backdrop-blur-sm p-0 sm:p-4">
    <div class="bg-white rounded-t-2xl sm:rounded-xl shadow-xl w-full sm:max-w-md p-6 pb-8 sm:pb-6">
        <div class="mx-auto mb-3 h-1 w-10 rounded-full bg-gray-300 sm:hidden"></div>
        <h3 class="font-bold text-gray-900">Blokir Sesi</h3>
        <p class="text-sm text-gray-500 mt-1">Akun <b id="banUserName">-</b> akan diblokir di IP/perangkat ini.</p>
        <form id="banForm" action="{{ route('admin.settings.sessions.ban') }}" method="POST" class="mt-4 space-y-3">
            @csrf
            <input type="hidden" name="user_id" id="banUserId">
            <input type="hidden" name="ip_address" id="banIp">
            <input type="hidden" name="user_agent" id="banUa">
            <div>
                <label class="text-xs font-semibold text-gray-700">Alasan (opsional, akan dilihat user)</label>
                <textarea name="reason" rows="2" placeholder="Contoh: terdeteksi login tidak wajar" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-100 focus:border-red-300 outline-none"></textarea>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="button" onclick="closeBanModal()" class="flex-1 rounded-xl border border-gray-200 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 sm:flex-none sm:px-6">Batal</button>
                <button type="submit" class="flex-1 rounded-xl bg-red-600 py-3 text-sm font-semibold text-white hover:bg-red-700 sm:flex-none sm:px-6">Ya, Blokir</button>
            </div>
        </form>
    </div>
</div>
<script>
function openBanModal(uid, ip, ua, name){
    document.getElementById('banUserId').value = uid;
    document.getElementById('banIp').value = ip;
    document.getElementById('banUa').value = ua;
    document.getElementById('banUserName').textContent = name + ' ('+ip+')';
    document.getElementById('banModal').classList.remove('hidden');
    document.getElementById('banModal').classList.add('flex');
}
function closeBanModal(){
    document.getElementById('banModal').classList.add('hidden');
    document.getElementById('banModal').classList.remove('flex');
}
document.getElementById('banModal')?.addEventListener('click', e=>{ if(e.target.id==='banModal') closeBanModal(); });
</script>
