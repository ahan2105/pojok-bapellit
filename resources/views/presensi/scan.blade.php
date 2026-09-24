@php
    $userAuth = Auth::user();
    $isAdmin = $userAuth && ($userAuth->role === 'admin' || $userAuth->is_admin === true);
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('title', 'Scan Absensi')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-6 sm:py-8">

    <!-- Tombol Kembali -->
    <div class="mb-5 sm:mb-6">
        <a href="{{ route('presensi.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 hover:border-indigo-300 hover:text-indigo-600 transition-all group shadow-sm">
            <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-600 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 sm:p-6 md:p-8">

        {{-- Header --}}
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-50 mb-3">
                <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Scan Absensi</h1>
            <p class="text-sm sm:text-base text-gray-500">Arahkan kamera ke QR Code yang ditampilkan admin</p>
        </div>

        <!-- Scanner Area -->
        <div class="relative mx-auto mb-6 rounded-2xl overflow-hidden bg-black" 
             style="max-width: 500px;">
            <div id="scanner-container" class="w-full"></div>

            {{-- Overlay Loading --}}
            <div id="scanner-loading" 
                 class="absolute inset-0 flex flex-col items-center justify-center bg-gray-900 text-white z-10">
                <svg class="w-10 h-10 animate-spin text-indigo-400 mb-3" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-sm text-gray-300">Mengaktifkan kamera...</p>
            </div>
        </div>

        {{-- Kamera Error / Retry --}}
        <div id="camera-error" class="hidden mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-red-700 mb-1">Kamera tidak bisa diakses</p>
                    <p class="text-xs text-red-600 mb-3">Pastikan Anda sudah memberi izin kamera di browser. Atau gunakan input token manual di bawah.</p>
                    <button onclick="retryScanner()" 
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-600 text-white text-xs font-semibold rounded-lg hover:bg-red-700 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Coba Lagi
                    </button>
                </div>
            </div>
        </div>

        <!-- Status Info -->
        <div id="scan-status" class="p-4 rounded-xl bg-gray-50 border border-gray-200 text-sm text-gray-600 flex items-center gap-3 transition-colors">
            <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span id="scan-status-text">Menunggu kamera aktif...</span>
        </div>

        <!-- Tombol Manual -->
        <div class="mt-6 pt-6 border-t border-gray-100">
            <div class="flex items-center justify-center gap-2 mb-3">
                <div class="h-px bg-gray-200 flex-1"></div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Atau</span>
                <div class="h-px bg-gray-200 flex-1"></div>
            </div>

            <p class="text-sm text-gray-500 mb-3 text-center">Masukkan token manual:</p>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 max-w-md mx-auto">
                <input type="text" id="manual-token" 
                       placeholder="Paste token QR di sini..."
                       class="flex-1 px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none transition">
                <button onclick="submitManual()" 
                        class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Absen
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Library html5-qrcode (via CDN) -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
    let html5QrCode = null;
    let isProcessing = false;

    // ===== Inisialisasi Kamera =====
    function initScanner() {
        // Sembunyikan error kalau ada
        document.getElementById('camera-error').classList.add('hidden');
        document.getElementById('scanner-loading').classList.remove('hidden');

        if (html5QrCode) {
            // Kalau instance lama ada, coba stop dulu
            html5QrCode.stop().catch(() => {}).finally(() => {
                html5QrCode = null;
                startScanner();
            });
        } else {
            startScanner();
        }
    }

    function startScanner() {
        html5QrCode = new Html5Qrcode("scanner-container");

        html5QrCode.start(
            { facingMode: "environment" },   // kamera belakang
            {
                fps: 10,
                qrbox: { width: 250, height: 250 },
            },
            onScanSuccess,
            () => {} // silent error
        ).then(() => {
            document.getElementById('scanner-loading').classList.add('hidden');
            updateStatus('Kamera aktif. Arahkan ke QR Code.', 'info');
        }).catch((err) => {
            document.getElementById('scanner-loading').classList.add('hidden');
            document.getElementById('camera-error').classList.remove('hidden');
            updateStatus('Kamera tidak bisa diakses. Pakai input manual di bawah.', 'error');
            console.error(err);
        });
    }

    // ===== Retry =====
    function retryScanner() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                html5QrCode.clear();
                html5QrCode = null;
                initScanner();
            }).catch(() => {
                html5QrCode = null;
                initScanner();
            });
        } else {
            initScanner();
        }
    }

    // ===== Callback saat QR terdeteksi =====
    function onScanSuccess(decodedText) {
        if (isProcessing) return;
        isProcessing = true;

        updateStatus('Memproses absensi...', 'info');

        // Extract token dari URL (QR isinya URL full, ambil segmen terakhir)
        let token = decodedText;
        try {
            const url = new URL(decodedText);
            const pathSegments = url.pathname.split('/');
            token = pathSegments[pathSegments.length - 1];
        } catch (e) {
            // Kalau bukan URL, pakai langsung
        }

        // Kirim ke backend
        fetch('{{ route("absensi.scan-process") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ token: token })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                updateStatus(data.message, 'success');
                stopScanner();
                setTimeout(() => {
                    window.location.href = '{{ route("presensi.index") }}';
                }, 2500);
            } else {
                updateStatus(data.message, 'error');
                setTimeout(() => { isProcessing = false; }, 2500);
            }
        })
        .catch(err => {
            updateStatus('Error jaringan. Coba lagi.', 'error');
            setTimeout(() => { isProcessing = false; }, 2000);
        });
    }

    // ===== Update Status =====
    function updateStatus(message, type = 'info') {
        const el = document.getElementById('scan-status');
        const textEl = document.getElementById('scan-status-text');

        textEl.textContent = message;

        // Reset class
        el.className = 'p-4 rounded-xl border text-sm flex items-center gap-3 transition-colors';

        // Icon
        let icon = '';
        if (type === 'success') {
            el.className += ' bg-green-50 border-green-200 text-green-700 font-semibold';
            icon = `<svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>`;
        } else if (type === 'error') {
            el.className += ' bg-red-50 border-red-200 text-red-700';
            icon = `<svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>`;
        } else {
            el.className += ' bg-gray-50 border-gray-200 text-gray-600';
            icon = `<svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>`;
        }

        el.innerHTML = icon + `<span id="scan-status-text">${message}</span>`;
    }

    // ===== Stop Kamera =====
    function stopScanner() {
        if (html5QrCode) html5QrCode.stop().catch(() => {});
    }

    // ===== Input Manual =====
    function submitManual() {
        const token = document.getElementById('manual-token').value.trim();
        if (!token) {
            updateStatus('Masukkan token dulu.', 'error');
            return;
        }
        onScanSuccess(token);
    }

    // Auto start scanner saat load
    document.addEventListener('DOMContentLoaded', initScanner);

    // Stop kamera saat pindah halaman
    window.addEventListener('beforeunload', stopScanner);
</script>
@endsection