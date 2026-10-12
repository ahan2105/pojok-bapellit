{{-- ============================================================ --}}
{{-- MODAL QR CODE --}}
{{-- ============================================================ --}}

<div id="qrModal" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-5 overflow-y-auto" style="background-color: rgba(15,23,42,0.62);">
    <div id="qr-print-card" class="bg-white rounded-2xl shadow-2xl relative my-4 sm:my-8" style="width: 420px; max-width: 100%; padding: 20px 22px; animation: modalIn 0.2s ease-out;">

        <!-- Tombol Close (X) -->
        <button type="button" 
                onclick="closeQrModal()"
                class="no-print absolute top-3 right-3 p-2 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-full transition z-10"
                aria-label="Tutup QR Code">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- Header -->
        <div class="text-center mb-3 pr-8">
            <p class="print-kicker text-[10px] font-bold uppercase tracking-[0.2em] text-indigo-600 mb-1">QR ABSENSI</p>
            <h2 class="text-lg font-bold text-gray-900 leading-tight break-words">{{ $sesi->nama_sesi }}</h2>
            <p class="text-xs text-gray-500 mt-1">
                {{ \Carbon\Carbon::parse($sesi->tanggal)->translatedFormat('d F Y') }}
            </p>
        </div>

        {{-- Info peserta --}}
        <div class="no-print mb-3 p-2.5 bg-amber-50 border border-amber-200 rounded-lg flex items-start gap-2">
            <svg class="w-3.5 h-3.5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <p class="text-[11px] text-amber-700 leading-relaxed">
                <strong>Hanya peserta yang terdaftar</strong> di sesi ini yang bisa absen dengan QR. User lain akan ditolak.
            </p>
        </div>

        <!-- QR Code -->
        <div id="qr-wrapper" class="flex justify-center mb-3">
            <div class="qr-box p-2.5 bg-white border-2 border-dashed border-purple-200 rounded-xl inline-flex items-center justify-center">
                <div class="qr-code">{!! $qrCode !!}</div>
            </div>
        </div>

        <!-- URL Scan -->
        <div class="no-print bg-gray-50 rounded-lg p-2.5 mb-3">
            <p class="text-[10px] text-gray-500 mb-0.5">URL Scan:</p>
            <p class="text-[11px] font-mono text-gray-700 break-all">{{ $scanUrl }}</p>
        </div>

        {{-- TOKEN MANUAL --}}
        <div class="no-print bg-gradient-to-br from-purple-50 to-indigo-50 border border-purple-200 rounded-xl p-3 mb-3 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-purple-600 rounded-md flex items-center justify-center flex-shrink-0">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </div>
                    <p class="text-[10px] font-bold text-purple-800 uppercase tracking-widest">
                        Token Manual
                    </p>
                </div>
                <button type="button" 
                        onclick="copyToken()"
                        id="copy-token-btn"
                        class="inline-flex items-center gap-1 px-2 py-1 text-[11px] font-semibold text-purple-700 bg-white border border-purple-300 rounded-md hover:bg-purple-50 hover:border-purple-400 transition shadow-sm">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    Copy
                </button>
            </div>

            <div class="bg-white rounded-md border-2 border-dashed border-purple-300 p-2.5">
                <p id="token-text" 
                   class="text-center text-xs font-mono font-bold text-purple-900 tracking-wide break-all select-all leading-snug">
                    {{ $sesi->token_qr }}
                </p>
            </div>

            <div class="flex items-start gap-1.5 mt-2">
                <svg class="w-3.5 h-3.5 text-purple-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-[11px] text-purple-700 leading-relaxed">
                    Untuk user yang HP-nya <strong>tanpa kamera</strong> atau kamera bermasalah. Masukkan token ini di halaman <strong>Scan Absensi → Token Manual</strong>.
                </p>
            </div>
        </div>

        {{-- KHUSUS PRINT --}}
        <div class="print-token-box">
            <p class="print-token-label">Token Manual</p>
            <p class="print-token-value">{{ $sesi->token_qr }}</p>
            <p class="print-token-hint">Masukkan token ini di halaman Scan Absensi bila kamera tidak dapat digunakan.</p>
        </div>

        <div class="print-footer-info">
            <p class="print-footer-line">Arahkan kamera ke QR ini di menu Scan Absensi.</p>
            <p class="print-footer-line print-footer-line--muted">QR tidak berlaku setelah absensi dikunci admin.</p>
        </div>

        <!-- Tombol Aksi -->
        <div class="no-print flex items-center gap-2 mb-2.5">
            <form action="{{ route('admin.absensi.qr-regenerate', $sesi->id) }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" 
                        onclick="return confirm('Generate QR baru? QR lama tidak berlaku lagi.')"
                        class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-amber-500 text-white text-xs font-semibold rounded-lg hover:bg-amber-600 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh QR
                </button>
            </form>

            <button type="button" 
                    onclick="printQr()"
                    class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700 transition flex-shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print
            </button>
        </div>

        <button type="button" 
                onclick="closeQrModal()"
                class="no-print w-full inline-flex items-center justify-center gap-2 px-3 py-2 bg-gray-100 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-200 transition">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            Tutup
        </button>

        <div class="no-print mt-2.5 pt-2.5 border-t border-gray-100 text-center space-y-0.5">
            <p class="text-[11px] text-gray-500">
                📱 Arahkan kamera HP user ke QR di atas untuk absen
            </p>
            <p class="text-[10px] text-gray-400">
                🔒 QR tidak berlaku setelah absensi dikunci
            </p>
        </div>

    </div>
</div>

<style>
    .qr-code svg,
    .qr-code canvas,
    .qr-code img {
        display: block;
        width: 220px !important;
        height: 220px !important;
        max-width: 100%;
    }

    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.95) translateY(6px); }
        to   { opacity: 1; transform: scale(1)    translateY(0); }
    }

    .print-token-box { display: none; }
    .print-footer-info { display: none; }

    /* ========== PRINT ========== */
    @media print {
        @page {
            size: A4 portrait;
            margin: 8mm;
        }

        html, body {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            height: auto !important;
            min-height: 0 !important;
            overflow: visible !important;
            background: #ffffff !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body > * {
            display: none !important;
        }

        body > *:has(#qrModal) {
            display: block !important;
            position: static !important;
            width: 100% !important;
            height: auto !important;
            min-height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow: visible !important;
            background: #ffffff !important;
            box-shadow: none !important;
            border: 0 !important;
        }

        body > *:has(#qrModal) *:not(#qrModal):not(#qrModal *):not(:has(#qrModal)) {
            display: none !important;
        }

        #qrModal {
            display: block !important;
            position: static !important;
            width: 100% !important;
            height: auto !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #ffffff !important;
            overflow: visible !important;
        }

        #qrModal * {
            visibility: visible !important;
        }

        #qrModal,
        #qr-print-card {
            box-shadow: none !important;
            animation: none !important;
        }

        #qr-print-card {
            display: block !important;
            box-sizing: border-box !important;
            width: 100% !important;
            max-width: 180mm !important;
            height: auto !important;
            max-height: none !important;
            margin: 0 auto !important;
            padding: 5mm 6mm !important;
            overflow: visible !important;
            border: 0.4mm solid #cbd5e1 !important;
            border-radius: 4mm !important;
            background: #ffffff !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
            page-break-after: avoid !important;
            break-after: avoid !important;
        }

        #qrModal .no-print {
            display: none !important;
            visibility: hidden !important;
        }

        #qrModal .print-kicker {
            display: block !important;
            margin: 0 0 1mm !important;
            font-size: 9pt !important;
            font-weight: 700 !important;
            letter-spacing: 0.2em !important;
            color: #4338ca !important;
            text-align: center !important;
        }

        #qrModal h2 {
            display: block !important;
            max-width: 165mm !important;
            margin: 0 auto !important;
            font-size: 20pt !important;
            line-height: 1.15 !important;
            text-align: center !important;
            overflow-wrap: anywhere !important;
            color: #111827 !important;
        }

        #qrModal .text-center > p:not(.print-kicker) {
            margin-top: 1mm !important;
            font-size: 10pt !important;
            text-align: center !important;
            color: #6b7280 !important;
        }

        #qr-wrapper {
            display: flex !important;
            justify-content: center !important;
            margin: 4mm 0 0 !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        #qrModal .qr-box {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 3mm !important;
            border: 0.4mm solid #cbd5e1 !important;
            border-radius: 3mm !important;
            background: #ffffff !important;
        }

        #qrModal .qr-code,
        #qrModal .qr-code svg,
        #qrModal .qr-code canvas,
        #qrModal .qr-code img {
            display: block !important;
            width: 108mm !important;
            height: 108mm !important;
            max-width: none !important;
            max-height: none !important;
        }

        #qrModal .print-token-box {
            display: block !important;
            visibility: visible !important;
            box-sizing: border-box !important;
            width: 130mm !important;
            max-width: 100% !important;
            margin: 4mm auto 0 !important;
            padding: 3mm 4mm !important;
            border: 0.4mm dashed #a78bfa !important;
            border-radius: 3mm !important;
            background: #f5f3ff !important;
            text-align: center !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        #qrModal .print-token-label {
            display: block !important;
            font-size: 8pt !important;
            font-weight: 700 !important;
            letter-spacing: 0.18em !important;
            text-transform: uppercase !important;
            color: #6d28d9 !important;
            margin: 0 0 1.5mm !important;
            text-align: center !important;
        }

        #qrModal .print-token-value {
            display: block !important;
            font-family: 'Courier New', Consolas, monospace !important;
            font-size: 11pt !important;
            font-weight: 700 !important;
            letter-spacing: 0 !important;
            line-height: 1.3 !important;
            color: #4c1d95 !important;
            margin: 0 !important;
            white-space: normal !important;
            word-break: break-word !important;
            overflow-wrap: anywhere !important;
            text-align: center !important;
        }

        #qrModal .print-token-hint {
            display: block !important;
            margin: 1.5mm 0 0 !important;
            font-size: 8pt !important;
            color: #6d28d9 !important;
            line-height: 1.3 !important;
            text-align: center !important;
        }

        #qrModal .print-footer-info {
            display: block !important;
            visibility: visible !important;
            width: 130mm !important;
            max-width: 100% !important;
            margin: 4mm auto 0 !important;
            padding: 3mm 4mm 0 !important;
            border-top: 0.3mm solid #e5e7eb !important;
            text-align: center !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        #qrModal .print-footer-line {
            display: block !important;
            margin: 0 !important;
            font-size: 9pt !important;
            font-weight: 600 !important;
            color: #374151 !important;
            line-height: 1.4 !important;
            text-align: center !important;
        }

        #qrModal .print-footer-line--muted {
            margin-top: 1.5mm !important;
            font-size: 8pt !important;
            font-weight: 500 !important;
            color: #6b7280 !important;
            font-style: italic !important;
        }
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
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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