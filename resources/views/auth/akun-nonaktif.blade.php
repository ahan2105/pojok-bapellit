<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Dinonaktifkan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Figtree', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    
    {{-- Background dekoratif (subtle) --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-red-100 rounded-full blur-3xl opacity-60"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-indigo-100 rounded-full blur-3xl opacity-60"></div>
    </div>

    {{-- Card Utama --}}
    <div class="relative max-w-md w-full">
        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-8 sm:p-10 text-center">
            
            {{-- Icon Merah --}}
            <div class="mx-auto w-20 h-20 rounded-full bg-red-50 flex items-center justify-center mb-6 ring-4 ring-red-100">
                <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                </svg>
            </div>

            {{-- Judul --}}
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3">
                Akun Dinonaktifkan
            </h1>

            {{-- Pesan --}}
            <p class="text-gray-600 text-sm sm:text-base leading-relaxed mb-2">
                Akun Anda telah <span class="text-red-600 font-semibold">dinonaktifkan</span> oleh admin.
            </p>
            <p class="text-gray-500 text-sm mb-8">
                Silakan hubungi admin untuk informasi lebih lanjut atau untuk mengaktifkan kembali akun Anda.
            </p>

            {{-- Divider --}}
            <div class="border-t border-gray-100 my-6"></div>

            {{-- Info Box --}}
            <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 text-left mb-6">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-indigo-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm">
                        <p class="font-semibold text-gray-800 mb-1">Kenapa ini terjadi?</p>
                        <p class="text-gray-600 leading-relaxed">
                            Status akun Anda diubah menjadi <strong class="text-red-600">nonaktif</strong>. Anda tidak bisa login sampai admin mengaktifkan kembali.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Tombol Kembali ke Login --}}
            <a href="{{ route('login') }}" 
               class="inline-flex items-center justify-center gap-2 w-full px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white text-base font-semibold rounded-xl transition shadow-lg shadow-indigo-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Halaman Login
            </a>
        </div>

        {{-- Footer --}}
        <p class="text-center text-xs text-gray-400 mt-6">
            © {{ date('Y') }} Bappelitbangda
        </p>
    </div>

</body>
</html>