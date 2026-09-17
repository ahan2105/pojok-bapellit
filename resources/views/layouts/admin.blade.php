<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - Pojok Bapelit')</title>
    
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
    {{-- Realtime Notification Listener (Reverb + Echo) --}}
    {{-- ───────────────────────────────────────────── --}}
    @auth
    <script type="module">
        document.addEventListener('DOMContentLoaded', () => {
            if (!window.Echo) {
                console.error('❌ Echo belum ke-load. Cek: npm run dev + php artisan reverb:start');
                return;
            }

            console.log('🎧 Admin listening ke channel: admin.notifications');

            window.Echo.private('admin.notifications')

                .listen('.booking.created', (e) => {
                    console.log('🔔 Booking baru masuk:', e);

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'info',
                        title: 'Booking Baru',
                        html: `<b>${e.nama}</b><br><small>${e.keperluan} — ${e.aula ?? '-'}</small><br><small>${e.tanggal} (${e.sesi})</small>`,
                        showConfirmButton: false,
                        timer: 6000,
                        timerProgressBar: true,
                    });
                })

                .listen('.surat.submitted', (e) => {
                    console.log('📩 Surat baru masuk:', e);

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Pengajuan Surat Baru',
                        html: `<b>${e.no_surat ?? '-'}</b><br><small>${e.jenis_surat ?? '-'} — ${e.pengaju ?? '-'}</small>`,
                        showConfirmButton: false,
                        timer: 6000,
                        timerProgressBar: true,
                    });
                });

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