{{-- ============================================================ --}}
{{-- MODAL QR CODE --}}
{{-- ============================================================ --}}

<div id="qrModal" class="fixed inset-0 z-50 hidden items-start justify-center p-4 overflow-y-auto" style="background-color: rgba(0,0,0,0.5);">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 md:p-8 relative my-8" style="animation: modalIn 0.2s ease-out;">
        
        <!-- Tombol Close (X) -->
        <button type="button" 
                onclick="closeQrModal()"
                class="absolute top-4 right-4 p-2 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-full transition z-10">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- Header -->
        <div class="text-center mb-6 pr-8">
            <h2 class="text-xl font-bold text-gray-900">{{ $sesi->nama_sesi }}</h2>
            <p class="text-sm text-gray-500 mt-1">
                {{ \Carbon\Carbon::parse($sesi->tanggal)->translatedFormat('d F Y') }}
            </p>
        </div>

        {{-- ⭐ INFO: QR cuma bisa dipakai peserta sesi ini --}}
        <div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-lg flex items-start gap-2">
            <svg class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <p class="text-xs text-amber-700 leading-relaxed">
                <strong>Hanya peserta yang terdaftar</strong> di sesi ini yang bisa absen dengan QR. User lain akan ditolak.
            </p>
        </div>

        <!-- QR Code -->
        <div id="qr-wrapper" class="flex justify-center mb-4">
            <div class="p-4 bg-white border-2 border-dashed border-purple-200 rounded-xl inline-block">
                {!! $qrCode !!}
            </div>
        </div>

        <!-- URL Scan -->
        <div class="bg-gray-50 rounded-lg p-3 mb-3">
            <p class="text-xs text-gray-500 mb-1">URL Scan:</p>
            <p class="text-xs font-mono text-gray-700 break-all">{{ $scanUrl }}</p>
        </div>

        {{-- TOKEN MANUAL — untuk user tanpa kamera --}}
        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-4">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-purple-700 uppercase tracking-wide">
                    🔑 Token Manual
                </p>
                <button type="button" 
                        onclick="copyToken()"
                        id="copy-token-btn"
                        class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium text-purple-700 bg-white border border-purple-300 rounded-md hover:bg-purple-100 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    Copy
                </button>
            </div>
            <div class="bg-white rounded-md p-3 border border-purple-100">
                <p id="token-text" class="text-sm font-mono text-purple-900 break-all select-all">
                    {{ $sesi->token_qr }}
                </p>
            </div>
            <p class="text-xs text-purple-600 mt-2 leading-relaxed">
                💡 Untuk user yang HP-nya tanpa kamera atau kamera gangguan.
                Bisa <strong>masukkan token ini</strong> di halaman Scan Absensi → kolom "Token Manual".
            </p>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex items-center gap-2 mb-4">
            <form action="{{ route('admin.absensi.qr-regenerate', $sesi->id) }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" 
                        onclick="return confirm('Generate QR baru? QR lama tidak berlaku lagi.')"
                        class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-amber-500 text-white text-sm font-semibold rounded-lg hover:bg-amber-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh QR
                </button>
            </form>

            <button type="button" 
                    onclick="printQr()"
                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print
            </button>
        </div>

        {{-- ⭐ TOMBOL TUTUP --}}
        <button type="button" 
                onclick="closeQrModal()"
                class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            Tutup
        </button>

        <!-- Info -->
        <div class="mt-4 pt-4 border-t border-gray-100 text-center">
            <p class="text-xs text-gray-500">
                📱 Arahkan kamera HP user ke QR di atas untuk absen
            </p>
            <p class="text-xs text-gray-400 mt-1">
                🔒 QR tidak berlaku setelah absensi dikunci
            </p>
        </div>

    </div>
</div>

<style>
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.95); }
        to   { opacity: 1; transform: scale(1); }
    }
    @media print {
        body * { visibility: hidden; }
        #qrModal, #qrModal * { visibility: visible; }
        #qrModal { position: absolute; left: 0; top: 0; background: white !important; padding: 0 !important; }
        #qrModal > div { box-shadow: none !important; margin: 0 !important; max-width: 100% !important; }
        #qrModal button, #qrModal form { display: none !important; }
    }
</style>

<script>
    function openQrModal() {
        const modal = document.getElementById('qrModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeQrModal() {
        const modal = document.getElementById('qrModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    document.getElementById('qrModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeQrModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeQrModal();
    });

    function printQr() {
        window.print();
    }

    function copyToken() {
        const tokenText = document.getElementById('token-text');
        const btn = document.getElementById('copy-token-btn');
        
        if (!tokenText) return;

        const token = tokenText.textContent.trim();

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(token).then(() => {
                showCopyFeedback(btn);
            }).catch(() => {
                fallbackCopy(token, btn);
            });
        } else {
            fallbackCopy(token, btn);
        }
    }

    function fallbackCopy(text, btn) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.select();
        try {
            document.execCommand('copy');
            showCopyFeedback(btn);
        } catch (err) {
            alert('Gagal copy. Silakan copy manual: ' + text);
        }
        document.body.removeChild(textarea);
    }

    function showCopyFeedback(btn) {
        const originalHTML = btn.innerHTML;
        btn.innerHTML = `
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Tersalin!
        `;
        btn.classList.add('bg-green-100', 'border-green-300', 'text-green-700');
        btn.classList.remove('bg-white', 'border-purple-300', 'text-purple-700');

        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.classList.remove('bg-green-100', 'border-green-300', 'text-green-700');
            btn.classList.add('bg-white', 'border-purple-300', 'text-purple-700');
        }, 2000);
    }
</script>