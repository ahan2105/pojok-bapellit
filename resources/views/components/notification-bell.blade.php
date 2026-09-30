
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
</script>