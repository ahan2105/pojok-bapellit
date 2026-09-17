{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- Bell Notifikasi — Admin & User                                 --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div x-data="notifBell()" x-init="init()" class="relative">

    {{-- Tombol Bell --}}
    <button @click="toggle()"
            class="relative p-2 text-gray-600 hover:text-indigo-600 hover:bg-gray-100 rounded-full focus:outline-none transition-colors"
            title="Notifikasi">
        {{-- Icon Lonceng --}}
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>

        {{-- Badge Counter (muncul kalau ada notif belum dibaca) --}}
        <span x-show="unreadCount > 0"
              x-cloak
              x-text="unreadCount > 99 ? '99+' : unreadCount"
              class="absolute top-0 right-0 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[10px] font-bold leading-none text-white bg-red-500 rounded-full ring-2 ring-white">
        </span>

        {{-- Animasi ping kalau ada notif baru --}}
        <span x-show="unreadCount > 0"
              x-cloak
              class="absolute top-0 right-0 inline-flex h-[18px] w-[18px] rounded-full bg-red-400 opacity-75 animate-ping">
        </span>
    </button>

    {{-- Dropdown Notifikasi --}}
    <div x-show="open"
         x-cloak
         @click.outside="open = false"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50 overflow-hidden">

        {{-- Header Dropdown --}}
        <div class="flex items-center justify-between px-4 py-3 border-b bg-gray-50">
            <div class="flex items-center gap-2">
                <span class="font-semibold text-gray-800">Notifikasi</span>
                <span x-show="unreadCount > 0"
                      x-text="'(' + unreadCount + ' baru)'"
                      class="text-xs text-red-600 font-medium"></span>
            </div>
            <button @click="markAllRead()"
                    x-show="unreadCount > 0"
                    class="text-xs text-indigo-600 hover:text-indigo-800 hover:underline font-medium">
                Tandai semua dibaca
            </button>
        </div>

        {{-- List Notifikasi --}}
        <div class="max-h-96 overflow-y-auto divide-y divide-gray-100">

            <template x-for="n in notifications" :key="n.id">
                <a :href="n.url || '#'"
                   @click.prevent="openNotif(n)"
                   class="block px-4 py-3 hover:bg-gray-50 cursor-pointer transition-colors"
                   :class="!n.read ? 'bg-blue-50/50' : ''">
                    <div class="flex items-start gap-3">
                        {{-- Icon berdasarkan tipe --}}
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

                        {{-- Isi Notif --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-sm text-gray-800 truncate" x-text="n.title"></span>
                                <span x-show="!n.read" class="flex-shrink-0 w-2 h-2 bg-red-500 rounded-full"></span>
                            </div>
                            <p class="text-xs text-gray-600 mt-0.5 line-clamp-2" x-text="n.message"></p>
                            <p class="text-[11px] text-gray-400 mt-1" x-text="n.created_at"></p>
                        </div>
                    </div>
                </a>
            </template>

            {{-- Empty state --}}
            <div x-show="notifications.length === 0" class="px-4 py-12 text-center">
                <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <p class="text-sm text-gray-400 mt-2">Belum ada notifikasi</p>
            </div>
        </div>

        {{-- Footer --}}
        <div class="px-4 py-2 border-t bg-gray-50 text-center">
            <span class="text-xs text-gray-500">Notifikasi otomatis muncul realtime</span>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- Alpine Component — Logika Bell                                    --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<script>
    function notifBell() {
        return {
            open: false,
            notifications: [],
            unreadCount: 0,

            init() {
                // Fetch notif pertama kali
                this.fetchNotifs();

                // ⚡ Realtime — listen notif baru dari server
                if (window.Echo) {
                    const userId = {{ auth()->id() }};
                    window.Echo.private(`user.${userId}`)
                        .listen('.notification.sent', (e) => {
                            console.log('🔔 Notif baru masuk:', e);

                            // Tambah ke list paling atas
                            this.notifications.unshift({
                                id: e.id,
                                type: e.type,
                                title: e.title,
                                message: e.message,
                                url: e.url,
                                read: false,
                                created_at: e.created_at,
                            });

                            this.unreadCount++;

                            // Toast SweetAlert
                            if (window.Swal) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: e.type === 'booking' ? 'info' : 'success',
                                    title: e.title,
                                    text: e.message,
                                    showConfirmButton: false,
                                    timer: 5000,
                                    timerProgressBar: true,
                                });
                            }
                        });
                }
            },

            toggle() {
                this.open = !this.open;
                if (this.open) this.fetchNotifs();
            },

            async fetchNotifs() {
                try {
                    const res = await fetch('{{ route('notifications.index') }}', {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    const data = await res.json();
                    this.notifications = data.notifications;
                    this.unreadCount = data.unread_count;
                } catch (e) {
                    console.error('❌ Gagal fetch notif:', e);
                }
            },

            async openNotif(n) {
                if (!n.read) {
                    await fetch(`/notifications/${n.id}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                    n.read = true;
                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                }
                if (n.url) window.location.href = n.url;
            },

            async markAllRead() {
                await fetch('{{ route('notifications.readAll') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                this.notifications.forEach(n => n.read = true);
                this.unreadCount = 0;
            },
        };
    }
</script>