<div class="rounded-xl border border-gray-200 bg-white shadow-sm">
    <div class="border-b border-gray-100 px-5 py-4 sm:px-6">
        <h2 class="text-lg font-semibold text-gray-900">Suara Notifikasi</h2>
        <p class="mt-0.5 text-sm text-gray-500">Atur suara yang diputar saat ada notifikasi masuk.</p>
    </div>

    <form action="{{ route('admin.settings.general.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6 p-5 sm:p-6">
        @csrf
        @method('PUT')

        <div class="rounded-xl border border-gray-200 bg-gray-50 p-5">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-indigo-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-gray-900">
                        Status saat ini:
                        <span class="ml-1 inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium 
                              {{ $soundType === 'custom' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }}">
                            {{ $soundType === 'custom' ? 'File custom' : 'Default sistem' }}
                        </span>
                    </p>
                    <p class="mt-1 break-all font-mono text-xs text-gray-500">{{ $soundFile }}</p>
                </div>
            </div>

            <div class="mt-5 border-t border-gray-200 pt-5">
                <label for="notification_sound" class="mb-2 block text-sm font-medium text-gray-700">Ganti suara notifikasi</label>
                <input type="file" id="notification_sound" name="notification_sound" accept=".mp3,.wav"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-full file:border file:border-indigo-200 file:bg-white file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-600 hover:file:bg-indigo-50">

                <p class="mt-4 text-xs text-gray-400">
                    Format .mp3 atau .wav, maksimal 2 MB. Kosongkan jika tidak ingin mengubah.
                    @if($soundType === 'custom')
                        <br><span class="text-indigo-600 font-medium">(Sedang menggunakan file custom)</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="rounded-lg bg-gray-900 px-8 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-black">
                Simpan Pengaturan Suara
            </button>
        </div>
    </form>

    {{-- Form reset — TERPISAH, di luar form utama --}}
    @if($soundType === 'custom')
        <form action="{{ route('admin.settings.sound.reset') }}" method="POST"
              onsubmit="return confirm('Kembalikan suara ke default? File custom akan dihapus permanen.')"
              class="px-5 pb-5 sm:px-6 sm:pb-6">
            @csrf
            <div class="flex justify-end">
                <button type="submit"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Kembalikan ke Default
                </button>
            </div>
        </form>
    @endif
</div>