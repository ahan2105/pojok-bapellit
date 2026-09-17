<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'User - Pojok Bapelit')</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-100">
    @include('navbar.index')
    
    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>
    
    <!-- SweetAlert Notifikasi -->
    @include('components.sweetalert')
    
    {{-- ───────────────────────────────────────────── --}}
    {{-- Realtime Notification Listener (User) --}}
    {{-- ───────────────────────────────────────────── --}}
    @auth
    <script type="module">
        document.addEventListener('DOMContentLoaded', () => {
            if (!window.Echo) {
                console.error('❌ Echo belum ke-load. Cek: npm run dev');
                return;
            }

            const userId = {{ auth()->id() }};
            console.log(`🎧 User ${userId} listening ke channel: user.${userId}`);

            // Dengarkan channel privat user
            window.Echo.private(`user.${userId}`)

                // ── Booking user di-approve ──
                .listen('.booking.approved', (e) => {
                    console.log('✅ Booking disetujui:', e);

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Booking Disetujui!',
                        html: `<b>${e.aula ?? '-'}</b><br><small>${e.tanggal} (${e.sesi})</small>`,
                        showConfirmButton: false,
                        timer: 6000,
                        timerProgressBar: true,
                    });
                })

                // ── Booking user di-reject ──
                .listen('.booking.rejected', (e) => {
                    console.log('❌ Booking ditolak:', e);

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Booking Ditolak',
                        html: `<b>${e.aula ?? '-'}</b><br><small>${e.alasan ?? 'Hubungi admin untuk info lebih lanjut.'}</small>`,
                        showConfirmButton: false,
                        timer: 6000,
                        timerProgressBar: true,
                    });
                })

                // ── Surat user selesai / di-approve ──
                .listen('.surat.approved', (e) => {
                    console.log('📩 Surat disetujui:', e);

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Surat Disetujui',
                        html: `<b>${e.no_surat ?? '-'}</b><br><small>${e.jenis_surat ?? '-'}</small>`,
                        showConfirmButton: false,
                        timer: 6000,
                        timerProgressBar: true,
                    });
                })

                // ── Surat user di-reject ──
                .listen('.surat.rejected', (e) => {
                    console.log('❌ Surat ditolak:', e);

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Surat Ditolak',
                        html: `<b>${e.no_surat ?? '-'}</b><br><small>${e.alasan ?? 'Hubungi admin untuk info lebih lanjut.'}</small>`,
                        showConfirmButton: false,
                        timer: 6000,
                        timerProgressBar: true,
                    });
                });

            // ── Debug koneksi ──
            window.Echo.connector.pusher.connection.bind('connected', () => {
                console.log('✅ Echo terhubung ke Reverb');
            });
            window.Echo.connector.pusher.connection.bind('disconnected', () => {
                console.warn('⚠️ Echo terputus dari Reverb');
            });
        });
    </script>
    @endauth

    @stack('scripts')
</body>
</html>