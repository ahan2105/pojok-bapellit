
<<<<<<< HEAD
    {{-- Tombol Bell --}}
    <button @click="toggle()"
            type="button"
            class="relative p-2 text-gray-600 hover:text-indigo-600 hover:bg-gray-100 rounded-full focus:outline-none transition-colors"
            title="Notifikasi">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>

        <span x-show="unreadCount > 0"
              x-cloak
              x-text="unreadCount > 99 ? '99+' : unreadCount"
              class="absolute top-0 right-0 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[10px] font-bold leading-none text-white bg-red-500 rounded-full ring-2 ring-white">
        </span>

        <span x-show="unreadCount > 0"
              x-cloak
              class="absolute top-0 right-0 inline-flex h-[18px] w-[18px] rounded-full bg-red-400 opacity-75 animate-ping">
        </span>
    </button>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- MOBILE: Overlay + Panel Full Width                           --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}

    <div x-show="open"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="open = false"
         class="sm:hidden fixed inset-0 bg-black/40 z-[9998]">
    </div>

    <div x-show="open"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="sm:hidden fixed z-[9999] bg-white rounded-t-2xl shadow-2xl flex flex-col"
         style="top: 4rem; left: 0.5rem; right: 0.5rem; max-height: calc(100vh - 5rem);">

        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3 border-b bg-gray-50 rounded-t-2xl flex-shrink-0">
            <div class="flex items-center gap-2">
                <span class="font-semibold text-gray-800">Notifikasi</span>
                <span x-show="unreadCount > 0"
                      x-cloak
                      x-text="'(' + unreadCount + ' baru)'"
                      class="text-xs text-red-600 font-medium"></span>
            </div>
            <button @click="open = false"
                    type="button"
                    class="p-1.5 text-gray-500 hover:text-gray-800 hover:bg-gray-200 rounded-full transition-colors"
                    title="Tutup">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Aksi --}}
        <div class="flex items-center justify-between px-4 py-2 border-b bg-white flex-shrink-0">
            <button @click="toggleSound()"
                    type="button"
                    class="inline-flex items-center gap-1.5 text-xs text-gray-600 hover:text-gray-900">
                <svg x-show="soundEnabled" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                </svg>
                <svg x-show="!soundEnabled" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/>
                </svg>
                <span x-text="soundEnabled ? 'Suara aktif' : 'Suara mati'"></span>
            </button>

            <button @click="markAllRead()"
                    type="button"
                    x-show="unreadCount > 0"
                    x-cloak
                    class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                Tandai semua
            </button>
        </div>

        {{-- Daftar Notifikasi (grouped) --}}
        <div class="flex-1 overflow-y-auto">
            <template x-for="group in groupedNotifications" :key="group.label">
                <div>
                    {{-- Group Label --}}
                    <div class="sticky top-0 z-10 px-4 py-1.5 bg-gray-50/95 backdrop-blur-sm border-y border-gray-100">
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider"
                              x-text="group.label"></span>
                    </div>

                    <div class="divide-y divide-gray-100">
                        <template x-for="n in group.items" :key="n.id">
                            <a :href="n.url || '#'"
                               @click.prevent="openNotif(n)"
                               class="block px-4 py-3 hover:bg-gray-50 cursor-pointer transition-colors"
                               :class="!n.is_read ? 'bg-blue-50/50' : ''">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center"
                                         :class="{
                                             'bg-blue-100 text-blue-600': n.type === 'booking',
                                             'bg-green-100 text-green-600': n.type === 'surat',
                                             'bg-gray-100 text-gray-600': !['booking','surat'].includes(n.type)
                                         }">
                                        <svg x-show="n.type === 'booking'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <svg x-show="n.type === 'surat'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <svg x-show="!['booking','surat'].includes(n.type)" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                        </svg>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold text-sm text-gray-800 truncate" x-text="n.title"></span>
                                            <span x-show="!n.is_read" class="flex-shrink-0 w-2 h-2 bg-red-500 rounded-full"></span>
                                        </div>
                                        <p class="text-xs text-gray-600 mt-0.5 line-clamp-2" x-text="n.message"></p>
                                        <p x-show="n.data && n.data.tanggal"
                                           class="text-[11px] text-indigo-600 font-medium mt-1"
                                           x-text="'📅 ' + n.data.tanggal + (n.data.sesi ? ' • ' + n.data.sesi : '')"></p>
                                        <p class="text-[11px] text-gray-400 mt-1" x-text="formatTime(n.created_at)"></p>
                                    </div>
                                </div>
                            </a>
                        </template>
                    </div>
                </div>
            </template>

            <div x-show="notifications.length === 0" class="px-4 py-12 text-center">
                <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <p class="text-sm text-gray-400 mt-2">Belum ada notifikasi</p>
            </div>
        </div>

        {{-- Status --}}
        <div class="px-4 py-2 border-t bg-gray-50 text-center flex items-center justify-center gap-2 rounded-b-2xl flex-shrink-0">
            <span class="inline-block w-2 h-2 rounded-full"
                  :class="connected ? 'bg-green-500' : 'bg-gray-300'"></span>
            <span class="text-xs text-gray-500"
                  x-text="connected ? 'Realtime aktif' : 'Menghubungkan...'"></span>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- DESKTOP: Dropdown Compact                                    --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div x-show="open"
         x-cloak
         @click.outside="open = false"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="hidden sm:block absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-[9999] overflow-hidden">

        <div class="flex items-center justify-between px-4 py-3 border-b bg-gray-50">
            <div class="flex items-center gap-2">
                <span class="font-semibold text-gray-800">Notifikasi</span>
                <span x-show="unreadCount > 0"
                      x-cloak
                      x-text="'(' + unreadCount + ' baru)'"
                      class="text-xs text-red-600 font-medium"></span>
            </div>
            <div class="flex items-center gap-2">
                <button @click="toggleSound()"
                        type="button"
                        :title="soundEnabled ? 'Matikan suara' : 'Nyalakan suara'"
                        class="text-gray-500 hover:text-gray-700">
                    <svg x-show="soundEnabled" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                    </svg>
                    <svg x-show="!soundEnabled" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/>
                    </svg>
                </button>

                <button @click="markAllRead()"
                        type="button"
                        x-show="unreadCount > 0"
                        x-cloak
                        class="text-xs text-indigo-600 hover:text-indigo-800 hover:underline font-medium">
                    Tandai semua
                </button>
            </div>
        </div>

        <div class="max-h-96 overflow-y-auto">
            <template x-for="group in groupedNotifications" :key="group.label">
                <div>
                    {{-- Group Label --}}
                    <div class="sticky top-0 z-10 px-4 py-1.5 bg-gray-50/95 backdrop-blur-sm border-y border-gray-100">
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider"
                              x-text="group.label"></span>
                    </div>

                    <div class="divide-y divide-gray-100">
                        <template x-for="n in group.items" :key="n.id">
                            <a :href="n.url || '#'"
                               @click.prevent="openNotif(n)"
                               class="block px-4 py-3 hover:bg-gray-50 cursor-pointer transition-colors"
                               :class="!n.is_read ? 'bg-blue-50/50' : ''">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center"
                                         :class="{
                                             'bg-blue-100 text-blue-600': n.type === 'booking',
                                             'bg-green-100 text-green-600': n.type === 'surat',
                                             'bg-gray-100 text-gray-600': !['booking','surat'].includes(n.type)
                                         }">
                                        <svg x-show="n.type === 'booking'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <svg x-show="n.type === 'surat'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <svg x-show="!['booking','surat'].includes(n.type)" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                        </svg>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold text-sm text-gray-800 truncate" x-text="n.title"></span>
                                            <span x-show="!n.is_read" class="flex-shrink-0 w-2 h-2 bg-red-500 rounded-full"></span>
                                        </div>
                                        <p class="text-xs text-gray-600 mt-0.5 line-clamp-2" x-text="n.message"></p>
                                        <p x-show="n.data && n.data.tanggal"
                                           class="text-[11px] text-indigo-600 font-medium mt-1"
                                           x-text="'📅 ' + n.data.tanggal + (n.data.sesi ? ' • ' + n.data.sesi : '')"></p>
                                        <p class="text-[11px] text-gray-400 mt-1" x-text="formatTime(n.created_at)"></p>
                                    </div>
                                </div>
                            </a>
                        </template>
                    </div>
                </div>
            </template>

            <div x-show="notifications.length === 0" class="px-4 py-12 text-center">
                <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <p class="text-sm text-gray-400 mt-2">Belum ada notifikasi</p>
            </div>
        </div>

        <div class="px-4 py-2 border-t bg-gray-50 text-center flex items-center justify-center gap-2">
            <span class="inline-block w-2 h-2 rounded-full"
                  :class="connected ? 'bg-green-500' : 'bg-gray-300'"></span>
            <span class="text-xs text-gray-500"
                  x-text="connected ? 'Realtime aktif' : 'Menghubungkan...'"></span>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- Alpine Component + Suara + Grouping                          --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<script>
function notifBell() {
    let audio = null;
    let lastFetch = 0;
    let stream = null;
    let lastPlayTime = 0;   // ⬅️ cegah double play

    return {
        open: false,
        notifications: [],
        unreadCount: {{ auth()->user()->unreadNotificationsCount() ?? 0 }},
        connected: false,
        soundEnabled: true,
        audioUrl: '{{ \App\Models\Setting::getNotificationSound() }}',
        _initialized: false,

        init() {
            if (this._initialized) return;
            this._initialized = true;

            this.setupAudio();
            this.fetchNotifs();

            if (!window.NotificationStream) {
                console.error('❌ NotificationStream belum ke-load. Cek app.js');
                return;
            }

            stream = new window.NotificationStream({
                streamUrl: '{{ route('notifications.stream') }}',

                // 🔊 CUMA DI SINI suara dibunyikan — kecuali tipe yang di-silent
                onNotification: (notif) => {
                    console.log('🔔 Notif SSE masuk:', notif);
                    this.notifications.unshift(notif);
                    this.unreadCount++;
                    // jangan bunyi untuk notif blokir sesi (biar nggak ganggu)
                    const silentTypes = ['session_banned'];
                    if (!silentTypes.includes(notif.type)) {
                        this.playSound();
                    }
                    this.showToast(notif);
                },

                onStatusChange: (ok) => { this.connected = ok; },

                // Resync JANGAN bunyi
                onResync: () => this.resync(),
            });

            stream.connect();
        },

        destroy() {
            if (stream) {
                stream.disconnect();
                stream = null;
            }
        },

        // ═══════════════════════════════════════════════════════
        // GROUPING — hari ini, kemarin, minggu lalu, bulan lalu
        // ═══════════════════════════════════════════════════════
        get groupedNotifications() {
            const now = new Date();
            const startOfToday = new Date(now.getFullYear(), now.getMonth(), now.getDate());
            const startOfYesterday = new Date(startOfToday);
            startOfYesterday.setDate(startOfYesterday.getDate() - 1);
            const startOfWeek = new Date(startOfToday);
            startOfWeek.setDate(startOfWeek.getDate() - 7);
            const startOfMonth = new Date(startOfToday);
            startOfMonth.setDate(startOfMonth.getDate() - 30);

            const groups = {
                'Hari Ini': [],
                'Kemarin': [],
                'Minggu Lalu': [],
                'Bulan Lalu': [],
                'Lebih Lama': [],
            };

            this.notifications.forEach(n => {
                const d = new Date(n.created_at);
                if (isNaN(d.getTime())) {
                    groups['Lebih Lama'].push(n);
                    return;
                }
                if (d >= startOfToday)          groups['Hari Ini'].push(n);
                else if (d >= startOfYesterday) groups['Kemarin'].push(n);
                else if (d >= startOfWeek)      groups['Minggu Lalu'].push(n);
                else if (d >= startOfMonth)     groups['Bulan Lalu'].push(n);
                else                            groups['Lebih Lama'].push(n);
            });

            return Object.entries(groups)
                .filter(([, items]) => items.length > 0)
                .map(([label, items]) => ({ label, items }));
        },

        // ═══════════════════════════════════════════════════════
        // AUDIO — cuma prepare, GAK bunyi saat unlock
        // ═══════════════════════════════════════════════════════
        setupAudio() {
            const saved = localStorage.getItem('notification_sound_enabled');
            if (saved !== null) this.soundEnabled = saved === 'true';

            audio = new Audio(this.audioUrl);
            audio.preload = 'auto';
            audio.volume = 0.7;

            // Unlock audio TANPA bunyi — pakai volume 0
            const events = ['click', 'keydown', 'touchstart'];
            const unlock = () => {
                audio.volume = 0;              // ⬅️ volume 0 = silent
                audio.play().then(() => {
                    audio.pause();
                    audio.currentTime = 0;
                    audio.volume = 0.7;
                    events.forEach(ev => document.removeEventListener(ev, unlock));
                    console.log('[Sound] Audio unlocked (silent)');
                }).catch(() => {
                    audio.volume = 0.7;
                });
            };
            events.forEach(ev => document.addEventListener(ev, unlock, { once: true }));
        },

        // ═══════════════════════════════════════════════════════
        // PLAY SOUND — cuma dipanggil dari onNotification
        // ═══════════════════════════════════════════════════════
        playSound() {
            if (!audio || !this.soundEnabled) return;

            // Throttle: skip kalau belum 800ms sejak bunyi terakhir
            const now = Date.now();
            if (now - lastPlayTime < 800) {
                console.log('[Sound] Skip — terlalu cepat');
                return;
            }
            lastPlayTime = now;

            audio.currentTime = 0;
            audio.volume = 0.7;
            audio.play()
                .then(() => console.log('[Sound] 🔊 Bunyi (notif baru)'))
                .catch((err) => console.warn('[Sound] Gagal play:', err.name));
        },

        // Toggle suara — TIDAK bunyi
        toggleSound() {
            this.soundEnabled = !this.soundEnabled;
            localStorage.setItem('notification_sound_enabled', this.soundEnabled);
            // ❌ JANGAN panggil playSound() di sini
        },

        showToast(notif) {
            if (!window.Swal) return;
            const icon = { booking: 'info', surat: 'success' }[notif.type] || 'info';
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: icon,
                title: notif.title,
                text: notif.message,
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
            });
        },

        async fetchNotifs(force = false) {
            const now = Date.now();
            if (!force && now - lastFetch < 2000) return;
            lastFetch = now;

            try {
                const res = await fetch('{{ route('notifications.index') }}', {
                    headers: { 'Accept': 'application/json' },
                });
                if (!res.ok) throw new Error('HTTP ' + res.status);

                const data = await res.json();
                this.notifications = data.notifications;
                this.unreadCount = data.unread_count;
            } catch (e) {
                console.error('❌ Gagal fetch notif:', e);
            }
        },

        // Resync JANGAN bunyi — cuma refresh list
        async resync() {
            await this.fetchNotifs(true);
        },

        toggle() {
            this.open = !this.open;
            if (this.open) this.fetchNotifs();

            if (this.open && window.innerWidth < 640) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        },

        async openNotif(n) {
            if (!n.is_read) {
                try {
                    const res = await fetch(`/notifications/${n.id}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                    });
                    if (res.ok) {
                        n.is_read = true;
                        this.unreadCount = Math.max(0, this.unreadCount - 1);
                    }
                } catch (e) {
                    console.error('❌ Gagal tandai baca:', e);
                }
            }
            if (n.url) window.location.href = n.url;
        },

        async markAllRead() {
            try {
                const res = await fetch('{{ route('notifications.read-all') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                });
                if (res.ok) {
                    this.notifications.forEach(n => n.is_read = true);
                    this.unreadCount = 0;
                }
            } catch (e) {
                console.error('❌ Gagal tandai semua:', e);
            }
        },

        async deleteNotification(id) {
            try {
                const res = await fetch(`/notifications/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                });
                if (res.ok) {
                    // Remove dari array
                    this.notifications = this.notifications.filter(n => n.id !== id);
                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                    console.log('[Notif] Deleted notification:', id);
                }
            } catch (e) {
                console.error('❌ Gagal hapus notif:', e);
            }
        },

        formatTime(iso) {
            if (!iso) return '';
            const d = new Date(iso);
            if (isNaN(d.getTime())) return '';
            const diff = Math.floor((new Date() - d) / 1000);
            if (diff < 60) return 'Baru saja';
            if (diff < 3600) return `${Math.floor(diff / 60)} menit lalu`;
            if (diff < 86400) return `${Math.floor(diff / 3600)} jam lalu`;
            return d.toLocaleDateString('id-ID');
        },
    }
}
=======
<div
    x-data="notifBell()"
    x-init="init()"
    class="relative"
>
    <!-- Tombol notifikasi -->
    <button
        type="button"
        @click="toggleDropdown()"
        class="relative flex items-center justify-center rounded-xl p-2.5
               text-slate-600 transition hover:bg-blue-50
               hover:text-blue-600"
        aria-label="Notifikasi"
    >
        <svg xmlns="http://www.w3.org/2000/svg" 
             width="23" height="23" viewBox="0 0 24 24" 
             fill="none" stroke="currentColor" stroke-width="1.8" 
             stroke-linecap="round" stroke-linejoin="round"> 
            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/> 
            <path d="M10 21h4"/> 
        </svg> 
 
        <span 
            x-show="unreadCount > 0" 
            x-cloak 
            x-text="unreadCount > 99 ? '99+' : unreadCount" 
            class="absolute -right-1 -top-1 flex min-w-5 h-5 
                   items-center justify-center rounded-full 
                   bg-red-500 px-1 text-[10px] font-bold text-white" 
        ></span> 
    </button> 
 
    <!-- Dropdown --> 
    <div 
        x-show="open" 
        x-cloak 
        @click.outside="open = false" 
        x-transition 
        class="absolute right-0 z-50 mt-3 w-80 max-w-[90vw] 
               overflow-hidden rounded-2xl border border-slate-200 
               bg-white shadow-xl sm:w-96" 
    > 
        <div class="flex items-center justify-between border-b 
                    border-slate-100 p-4"> 
            <div> 
                <h3 class="font-semibold text-slate-800">Notifikasi</h3> 
                <p class="text-xs text-slate-500"> 
                    <span x-text="unreadCount"></span> belum dibaca 
                </p> 
            </div> 
 
            <button 
                type="button" 
                @click="toggleSound()" 
                class="rounded-lg px-3 py-2 text-xs font-medium" 
                :class="soundEnabled 
                    ? 'bg-blue-50 text-blue-600' 
                    : 'bg-slate-100 text-slate-500'" 
                x-text="soundEnabled ? '🔊 Suara aktif' : '🔇 Suara mati'" 
            ></button> 
        </div> 
 
        <div class="max-h-96 overflow-y-auto"> 
            <template x-if="notifications.length === 0"> 
                <div class="px-5 py-10 text-center"> 
                    <p class="text-sm text-slate-500"> 
                        Belum ada notifikasi. 
                    </p> 
                </div> 
            </template> 
 
            <template x-for="notif in notifications" :key="notif.id"> 
                <div 
                    class="flex gap-3 border-b border-slate-100 p-4" 
                    :class="notif.read_at ? 'bg-white' : 'bg-blue-50/60'" 
                > 
                    <div class="flex h-10 w-10 shrink-0 items-center 
                                justify-center rounded-full bg-blue-100 
                                text-blue-600"> 
                        <span>🔔</span> 
                    </div> 
 
                    <div class="min-w-0 flex-1"> 
                        <p 
                            class="text-sm font-semibold text-slate-800" 
                            x-text="notif.title || 'Notifikasi baru'" 
                        ></p> 
 
                        <p 
                            class="mt-1 break-words text-xs text-slate-600" 
                            x-text="notif.message || notif.data?.message || ''" 
                        ></p> 
 
                        <div class="mt-3 flex flex-wrap gap-3"> 
                            <button 
                                x-show="!notif.read_at" 
                                type="button" 
                                @click="markRead(notif)" 
                                class="text-xs font-semibold text-blue-600" 
                            > 
                                Tandai dibaca 
                            </button> 
 
                            <button 
                                type="button" 
                                @click="deleteNotif(notif)" 
                                class="text-xs font-semibold text-red-500" 
                            > 
                                Hapus 
                            </button> 
                        </div> 
                    </div> 
                </div> 
            </template> 
        </div> 
 
        <div class="flex items-center justify-between border-t 
                    border-slate-100 px-4 py-3"> 
            <span 
                class="flex items-center gap-2 text-xs text-slate-500" 
            > 
                <span 
                    class="h-2 w-2 rounded-full" 
                    :class="connected ? 'bg-green-500' : 'bg-red-400'" 
                ></span> 
                <span x-text="connected ? 'Terhubung' : 'Menghubungkan...'"></span> 
            </span> 
 
            <button 
                type="button" 
                @click="markAllRead()" 
                class="text-xs font-semibold text-blue-600 hover:text-blue-800" 
            > 
                Baca semua 
            </button> 
        </div> 
    </div> 
 
    <!-- Toast notifikasi baru --> 
    <div 
        x-show="toast.show" 
        x-cloak 
        x-transition 
        class="fixed right-4 top-20 z-[100] w-80 max-w-[90vw] 
               rounded-2xl border border-blue-100 bg-white p-4 shadow-2xl" 
    > 
        <div class="flex gap-3"> 
            <div class="text-2xl">🔔</div> 
            <div class="min-w-0 flex-1"> 
                <p class="font-semibold text-slate-800" 
                   x-text="toast.title"></p> 
                <p class="mt-1 text-sm text-slate-600" 
                   x-text="toast.message"></p> 
            </div> 
            <button 
                type="button" 
                @click="toast.show = false" 
                class="text-slate-400" 
            >✕</button> 
        </div> 
    </div> 
</div> 
 
<script> 
function notifBell() { 
    return { 
        open: false, 
        notifications: [], 
        unreadCount: 0, 
        connected: false, 
        soundEnabled: localStorage.getItem('notification_sound_enabled') !== 'false', 
        audio: null, 
        audioUnlocked: false, 
        stream: null, 
        toast: { 
            show: false, 
            title: '', 
            message: '' 
        }, 
 
        init() { 
            this.setupAudio(); 
            this.fetchNotifs(); 
 
            // Browser harus menerima interaksi pengguna sebelum audio 
            // dapat diputar secara andal. 
            const unlock = async () => { 
                if (this.audioUnlocked || !this.audio) return; 
 
                try { 
                    this.audio.muted = true; 
                    await this.audio.play(); 
                    this.audio.pause(); 
                    this.audio.currentTime = 0; 
                    this.audio.muted = false; 
                    this.audioUnlocked = true; 
 
                    ['click', 'touchstart', 'keydown'].forEach(event => { 
                        document.removeEventListener(event, unlock, true); 
                    }); 
                } catch (error) { 
                    this.audio.muted = false; 
                } 
            }; 
 
            ['click', 'touchstart', 'keydown'].forEach(event => { 
                document.addEventListener(event, unlock, true); 
            }); 
 
            // Gunakan NotificationStream yang sudah tersedia di proyek. 
            if (window.NotificationStream) { 
                this.stream = new window.NotificationStream({ 
                    streamUrl: '{{ route('notifications.stream') }}', 
 
                    onNotification: (notif) => { 
                        if (!notif || notif.id == null) return; 
 
                        if (this.notifications.some(item => 
                            String(item.id) === String(notif.id) 
                        )) return; 
 
                        this.notifications.unshift(notif); 
 
                        if (!notif.read_at) this.unreadCount++; 
 
                        this.playSound(); 
                        this.showToast(notif); 
                    }, 
 
                    onStatusChange: (ok) => { 
                        this.connected = ok; 
                    }, 
 
                    onResync: () => { 
                        this.fetchNotifs(); 
                    } 
                }); 
 
                this.stream.connect(); 
            } else { 
                console.warn( 
                    'NotificationStream belum dimuat. Periksa script SSE proyek.' 
                ); 
            } 
        }, 
 
        setupAudio() { 
            this.audio = new Audio('{{ asset('sounds/notif.mp3') }}'); 
            this.audio.preload = 'auto'; 
            this.audio.volume = 0.8; 
 
            this.audio.addEventListener('error', () => { 
                console.error( 
                    'File suara gagal dimuat:', 
                    this.audio.src 
                ); 
            }); 
 
            this.audio.load(); 
        }, 
 
        async playSound() { 
            if (!this.soundEnabled || !this.audio) return; 
 
            try { 
                this.audio.pause(); 
                this.audio.currentTime = 0; 
                this.audio.muted = false; 
                this.audio.volume = 0.8; 
                await this.audio.play(); 
                console.log('Suara notifikasi berhasil diputar.'); 
            } catch (error) { 
                console.warn( 
                    'Suara tidak dapat diputar:', 
                    error.name, 
                    error.message 
                ); 
            } 
        }, 
 
        toggleSound() { 
            this.soundEnabled = !this.soundEnabled; 
 
            localStorage.setItem( 
                'notification_sound_enabled', 
                String(this.soundEnabled) 
            ); 
 
            // Interaksi pada tombol ini juga membantu membuka izin audio. 
            if (this.soundEnabled) { 
                this.playSound(); 
            } 
        }, 
 
        async fetchNotifs() { 
            try { 
                const response = await fetch( 
                    '{{ route('notifications.index') }}', 
                    { 
                        headers: { 
                            'Accept': 'application/json', 
                            'X-Requested-With': 'XMLHttpRequest' 
                        }, 
                        credentials: 'same-origin' 
                    } 
                ); 
 
                if (!response.ok) { 
                    throw new Error('HTTP ' + response.status); 
                } 
 
                const result = await response.json(); 
 
                const items = Array.isArray(result) 
                    ? result 
                    : (result.data || result.notifications || []); 
 
                this.notifications = items; 
                this.unreadCount = items.filter(item => !item.read_at).length; 
            } catch (error) { 
                console.error('Gagal memuat notifikasi:', error); 
            } 
        }, 
 
        async markRead(notif) { 
            try { 
                const response = await fetch( 
                    `/notifications/${notif.id}/read`, 
                    { 
                        method: 'PATCH', 
                        headers: this.requestHeaders(), 
                        credentials: 'same-origin', 
                        body: JSON.stringify({}) 
                    } 
                ); 
 
                if (!response.ok) throw new Error('HTTP ' + response.status); 
 
                if (!notif.read_at) { 
                    notif.read_at = new Date().toISOString(); 
                    this.unreadCount = Math.max(0, this.unreadCount - 1); 
                } 
            } catch (error) { 
                console.error('Gagal menandai notifikasi:', error); 
            } 
        }, 
 
        async markAllRead() { 
            try { 
                const response = await fetch( 
                    '{{ route('notifications.read-all') }}', 
                    { 
                        method: 'PATCH', 
                        headers: this.requestHeaders(), 
                        credentials: 'same-origin', 
                        body: JSON.stringify({}) 
                    } 
                ); 
 
                if (!response.ok) throw new Error('HTTP ' + response.status); 
 
                this.notifications.forEach(notif => { 
                    notif.read_at = notif.read_at || new Date().toISOString(); 
                }); 
 
                this.unreadCount = 0; 
            } catch (error) { 
                console.error('Gagal membaca semua notifikasi:', error); 
            } 
        }, 
 
        async deleteNotif(notif) { 
            try { 
                const response = await fetch( 
                    `/notifications/${notif.id}`, 
                    { 
                        method: 'DELETE', 
                        headers: this.requestHeaders(), 
                        credentials: 'same-origin' 
                    } 
                ); 
 
                if (!response.ok) throw new Error('HTTP ' + response.status); 
 
                this.notifications = this.notifications.filter( 
                    item => String(item.id) !== String(notif.id) 
                ); 
 
                if (!notif.read_at) { 
                    this.unreadCount = Math.max(0, this.unreadCount - 1); 
                } 
            } catch (error) { 
                console.error('Gagal menghapus notifikasi:', error); 
            } 
        }, 
 
        requestHeaders() { 
            return { 
                'Accept': 'application/json', 
                'Content-Type': 'application/json', 
                'X-Requested-With': 'XMLHttpRequest', 
                'X-CSRF-TOKEN': 
                    document.querySelector('meta[name="csrf-token"]')?.content || '' 
            }; 
        }, 
 
        toggleDropdown() { 
            this.open = !this.open; 
 
            if (this.open) this.fetchNotifs(); 
        }, 
 
        showToast(notif) { 
            this.toast.title = notif.title || 'Notifikasi baru'; 
            this.toast.message = 
                notif.message || notif.data?.message || 'Ada notifikasi baru.'; 
            this.toast.show = true; 
 
            setTimeout(() => { 
                this.toast.show = false; 
            }, 5000); 
        } 
    }; 
} 
>>>>>>> 122a28b5cb04190b06effe3b1ad95b9dd59c95f8
</script>