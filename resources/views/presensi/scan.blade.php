@php
    $userAuth = Auth::user();
    $isAdmin = $userAuth && ($userAuth->role === 'admin' || $userAuth->is_admin === true);
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('title', 'Scan Absensi')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">

    <!-- Tombol Kembali -->
    <div class="mb-6">
        <a href="{{ route('presensi.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8 text-center">

        <h1 class="text-2xl font-bold text-gray-900 mb-2">Scan Absensi</h1>
        <p class="text-gray-500 mb-6">Arahkan kamera ke QR Code yang ditampilkan admin</p>

        <!-- Scanner Area -->
        <div id="scanner-container" class="mx-auto mb-6 rounded-2xl overflow-hidden border-4 border-indigo-200 bg-black" style="max-width: 500px;"></div>

        <!-- Status Info -->
        <div id="scan-status" class="p-4 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-600">
            📷 Menunggu kamera aktif...
        </div>

        <!-- Tombol Manual -->
        <div class="mt-6 pt-6 border-t border-gray-100">
            <p class="text-sm text-gray-500 mb-3">Atau masukkan token manual:</p>
            <div class="flex items-center gap-2 max-w-md mx-auto">
                <input type="text" id="manual-token" 
                       placeholder="Paste token QR di sini..."
                       class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                <button onclick="submitManual()" 
                        class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700">
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
        html5QrCode = new Html5Qrcode("scanner-container");

        html5QrCode.start(
            { facingMode: "environment" },   // pakai kamera belakang
            {
                fps: 10,
                qrbox: { width: 250, height: 250 },
            },
            onScanSuccess,
            () => {} // silent error (banyak error saat scan berjalan)
        ).then(() => {
            updateStatus('📷 Kamera aktif. Arahkan ke QR Code.', 'info');
        }).catch((err) => {
            updateStatus('❌ Kamera tidak bisa diakses. Pakai input manual di bawah.', 'error');
            console.error(err);
        });
    }

    // ===== Callback saat QR terdeteksi =====
    function onScanSuccess(decodedText) {
        if (isProcessing) return;
        isProcessing = true;

        updateStatus('⏳ Memproses absensi...', 'info');

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
                updateStatus('✅ ' + data.message, 'success');
                stopScanner();
                setTimeout(() => {
                    window.location.href = '{{ route("presensi.index") }}';
                }, 2000);
            } else {
                updateStatus('❌ ' + data.message, 'error');
                setTimeout(() => { isProcessing = false; }, 2000);
            }
        })
        .catch(err => {
            updateStatus('❌ Error jaringan. Coba lagi.', 'error');
            isProcessing = false;
        });
    }

    // ===== Update Status =====
    function updateStatus(message, type = 'info') {
        const el = document.getElementById('scan-status');
        el.textContent = message;
        el.className = 'p-4 rounded-lg text-sm border ';
        if (type === 'success') el.className += 'bg-green-50 border-green-200 text-green-700 font-semibold';
        else if (type === 'error') el.className += 'bg-red-50 border-red-200 text-red-700';
        else el.className += 'bg-gray-50 border-gray-200 text-gray-600';
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