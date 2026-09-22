{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- Bell Notifikasi — Realtime SSE + Toast + Suara              --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div x-data="notifBell()" x-init="init()" class="relative">

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

    {{-- Dropdown --}}
    <div x-show="open"
         x-cloak
         @click.outside="open = false"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-[9999] overflow-hidden">

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

        <div class="max-h-96 overflow-y-auto divide-y divide-gray-100">
            <template x-for="n in notifications" :key="n.id">
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
{{-- Alpine Component + Suara                                     --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<script>
function notifBell() {
    // Disimpan di luar state reaktif Alpine (supaya tidak dibungkus Proxy)
    let audio = null;
    let lastFetch = 0;
    let stream = null;

    return {
        open: false,
        notifications: [],
        unreadCount: {{ auth()->user()->unreadNotificationsCount() ?? 0 }},
        connected: false,
        soundEnabled: true,
        soundBlocked: false,   // true = browser menolak play() (belum ada interaksi di halaman ini)
        audioUrl: '/sounds/notif.mp3',
        _initialized: false,

        // Alpine 3 memanggil init() otomatis, dan x-init="init()" memanggilnya lagi.
        // Guard ini membuat pemanggilan kedua tidak melakukan apa-apa.
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

                // Bell adalah satu-satunya pemilik unreadCount.
                onNotification: (notif) => {
                    console.log('🔔 Notif SSE masuk:', notif);

                    this.notifications.unshift(notif);
                    this.unreadCount++;

                    if (this.soundEnabled) this.playSound();
                    this.showToast(notif);
                },
                onStatusChange: (ok) => { this.connected = ok; },
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

        // ------------------------------------------------------------ Suara

        setupAudio() {
            const saved = localStorage.getItem('notification_sound_enabled');
            if (saved !== null) this.soundEnabled = saved === 'true';

            // Satu elemen Audio dipakai berulang. Penting untuk Safari/iOS: yang
            // di-"unlock" saat klik harus elemen yang sama dengan yang nanti di-play.
            audio = new Audio(this.audioUrl);
            audio.preload = 'auto';
            audio.volume = 0.7;

            const events = ['click', 'keydown', 'touchstart'];

            const unlock = () => {
                audio.volume = 0.01;
                audio.play().then(() => {
                    audio.pause();
                    audio.currentTime = 0;
                    audio.volume = 0.7;
                    events.forEach(ev => document.removeEventListener(ev, unlock));
                    console.log('[Sound] Audio unlocked');

                    // Ada notif yang tadi ditolak browser? Bunyikan sekarang.
                    if (this.soundBlocked) {
                        this.soundBlocked = false;
                        if (this.soundEnabled) this.playSound();
                    }
                }).catch((err) => {
                    audio.volume = 0.7;
                    console.warn('[Sound] Unlock gagal:', err.message);
                });
            };

            events.forEach(ev => document.addEventListener(ev, unlock));
        },

        playSound() {
            if (!audio) return;

            audio.currentTime = 0;
            audio.volume = 0.7;
            audio.play()
                .then(() => {
                    this.soundBlocked = false;
                    console.log('[Sound] 🔊 Bunyi');
                })
                .catch((err) => {
                    if (err.name === 'NotAllowedError') {
                        // Halaman baru di-load & belum ada klik/tombol → browser blokir.
                        // Ditandai, dan dibunyikan otomatis pada interaksi berikutnya.
                        this.soundBlocked = true;
                        console.warn('[Sound] Diblokir browser, menunggu interaksi (klik sekali di halaman)');
                    } else {
                        console.warn('[Sound] Gagal play:', err.message);
                    }
                });
        },

        toggleSound() {
            this.soundEnabled = !this.soundEnabled;
            localStorage.setItem('notification_sound_enabled', this.soundEnabled);
            if (this.soundEnabled) this.playSound();
        },

        // ------------------------------------------------------------ Toast

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

        // ------------------------------------------------------------ Data

        async fetchNotifs(force = false) {
            // Throttle 2 detik, kecuali dipaksa (mis. dari resync)
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

        // Dipanggil stream setelah koneksi putus lama / halaman dipulihkan
        async resync() {
            const before = this.unreadCount;
            await this.fetchNotifs(true);
            if (this.unreadCount > before && this.soundEnabled) {
                this.playSound();
            }
        },

        toggle() {
            this.open = !this.open;
            if (this.open) this.fetchNotifs();
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
</script>