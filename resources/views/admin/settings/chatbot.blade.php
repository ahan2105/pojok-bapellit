<div class="rounded-xl border border-gray-200 bg-white shadow-sm">
    <div class="flex flex-wrap items-start justify-between gap-3 border-b border-gray-100 px-5 py-4 sm:px-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Template Chatbot</h2>
            <p class="mt-0.5 text-sm text-gray-500">Jawaban otomatis yang diprioritaskan sebelum AI.</p>
        </div>
        <span class="rounded-md bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
            {{ count($templates) }} template aktif
        </span>
    </div>

    <div class="space-y-6 p-5 sm:p-6">

        <div class="rounded-lg border border-indigo-100 bg-indigo-50/60 px-4 py-3 text-sm text-indigo-900">
            <p class="font-medium">Cara kerja jawaban chatbot</p>
            <ol class="mt-1.5 list-decimal space-y-0.5 pl-5 text-indigo-800/90">
                <li>Jika pesan pengguna mengandung salah satu kata kunci, chatbot memakai jawaban template (tanpa token AI).</li>
                <li>Jika tidak ada yang cocok, pertanyaan diteruskan ke AI dengan template sebagai bahan rujukan.</li>
            </ol>
        </div>

        {{-- Form tambah/edit --}}
        <form id="form-template"
              action="{{ route('admin.settings.chatbot.store') }}"
              method="POST"
              class="rounded-xl border border-dashed border-gray-300 bg-gray-50/60 p-5">
            @csrf
            <input type="hidden" name="_method" id="method-field" value="PUT" disabled>

            <div class="mb-5 flex items-center justify-between gap-3">
                <h3 id="form-title" class="text-base font-semibold text-gray-900">Tambah Template Baru</h3>
                <span id="form-badge" class="hidden rounded-md bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-800">Mode edit</span>
            </div>

            <div class="space-y-5">
                <div>
                    <label for="input-label" class="mb-1.5 block text-sm font-medium text-gray-700">Label menu dan tombol cepat</label>
                    <input type="text" id="input-label" name="label" value="{{ old('label') }}" required
                           placeholder="Contoh: Cara Booking Aula"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="input-keywords" class="mb-1.5 block text-sm font-medium text-gray-700">Kata kunci pemicu</label>
                    <input type="text" id="input-keywords" name="keywords" value="{{ old('keywords') }}"
                           placeholder="booking, sewa, pesan aula, cara booking"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="mt-1.5 text-xs text-gray-500">Pisahkan dengan koma. Chatbot membalas otomatis jika pesan pengguna mengandung salah satu kata kunci.</p>
                    <div id="keywords-preview" class="mt-2 flex flex-wrap gap-1.5"></div>
                </div>

                <div>
                    <label for="input-reply" class="mb-1.5 block text-sm font-medium text-gray-700">Jawaban standar</label>
                    <textarea id="input-reply" name="reply" rows="6" required
                              placeholder="Tulis jawaban lengkap di sini..."
                              class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('reply') }}</textarea>
                    <p class="mt-1.5 text-xs text-gray-500">Ditampilkan apa adanya di chat. Baris baru dipertahankan, format markdown tidak dirender.</p>
                </div>
            </div>

            <div class="mt-6 flex flex-col sm:flex-row sm:items-center gap-3">
                <button type="submit" id="btn-submit"
                        class="w-full sm:w-auto rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                    Simpan Template
                </button>
                <button type="button" id="btn-cancel-edit"
                        class="hidden w-full sm:w-auto rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                    Batal Edit
                </button>
            </div>
        </form>

        {{-- Daftar template --}}
        <div>
            <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
                <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500">Daftar Template Aktif</h3>
                @if(count($templates) > 0)
                    <input type="search" id="template-filter" placeholder="Cari label atau kata kunci..."
                           class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:w-64">
                @endif
            </div>

            <div class="space-y-3" id="template-list">
                @forelse($templates as $t)
                    @php $kws = $keywordsOf($t); @endphp
                    <article data-template-id="{{ $t->id }}"
                             data-search="{{ mb_strtolower($t->label . ' ' . implode(' ', $kws)) }}"
                             class="template-item rounded-xl border border-gray-200 bg-white p-4 transition hover:border-indigo-200 sm:p-5">
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h4 class="font-semibold text-gray-900 line-clamp-2 sm:truncate">{{ $t->label }}</h4>
                                <div class="mt-2 flex flex-wrap gap-1.5">
                                    @forelse($kws as $kw)
                                        <span class="inline-flex items-center rounded-full border border-gray-200 bg-gray-100 px-2.5 py-1 text-[11px] font-medium text-gray-600">{{ $kw }}</span>
                                    @empty
                                        <span class="text-xs text-gray-400">Tanpa kata kunci, hanya muncul lewat tombol menu.</span>
                                    @endforelse
                                </div>
                            </div>

                            <div class="flex items-center gap-1 shrink-0 -ml-1 sm:ml-0">
                                <button type="button" data-edit="{{ $t->id }}"
                                        class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-blue-600 transition hover:bg-blue-50">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </button>
                                <form action="{{ route('admin.settings.chatbot.destroy', $t) }}" method="POST"
                                      onsubmit="return confirm('Hapus template ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-red-600 transition hover:bg-red-50">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>

                        <p class="mt-3 whitespace-pre-line break-words rounded-lg bg-gray-50 px-3 py-2.5 text-sm leading-relaxed text-gray-600">{{ $t->reply }}</p>
                    </article>
                @empty
                    <div class="rounded-xl border border-dashed border-gray-200 bg-gray-50 py-10 text-center text-sm text-gray-400">
                        Belum ada template custom. Tambahkan lewat form di atas.
                    </div>
                @endforelse

                <div id="filter-empty" class="hidden rounded-xl border border-dashed border-gray-200 bg-gray-50 py-8 text-center text-sm text-gray-400">
                    Tidak ada template yang cocok dengan pencarian.
                </div>
            </div>
        </div>
    </div>
</div>