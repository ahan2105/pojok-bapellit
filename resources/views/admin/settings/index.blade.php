@php
    /** @var \App\Models\User|null $user */
    $user = Auth::user();
    $isAdmin = $user?->isAdmin() ?? false;
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';

    // Deteksi halaman aktif
    $isSoundPage = request()->routeIs('admin.settings.sound');
    $isSessionsPage = request()->routeIs('admin.settings.sessions');
@endphp

@extends($layout)

@section('title', $isSessionsPage ? 'Sesi & Perangkat' : ($isSoundPage ? 'Suara Notifikasi' : 'Template Chatbot'))

@section('content')
@php
    // Normalisasi keywords (dipakai partial chatbot)
    $keywordsOf = function ($t) {
        $raw = $t->keywords;

        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            if (is_string($decoded)) {
                $decoded = json_decode($decoded, true);
            }
            $raw = is_array($decoded) ? $decoded : preg_split('/\s*,\s*/', $raw);
        }

        return array_values(array_filter(
            array_map(fn ($k) => trim((string) $k), (array) $raw),
            fn ($k) => $k !== ''
        ));
    };

    $templateData = isset($templates)
        ? $templates->mapWithKeys(fn ($t) => [
            $t->id => [
                'label'    => (string) $t->label,
                'keywords' => $keywordsOf($t),
                'reply'    => (string) $t->reply,
            ],
        ])
        : collect();
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8">

    {{-- Header --}}
    <div class="mb-6 sm:mb-8">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Pengaturan Sistem</h1>
        <p class="mt-1 text-sm text-gray-500">Kelola template chatbot dan preferensi notifikasi.</p>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="mb-6 flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <p class="font-medium">Data belum bisa disimpan:</p>
            <ul class="mt-1 list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex flex-col gap-6 lg:flex-row lg:gap-8">

        {{-- Sidebar internal --}}
        @include('admin.settings.nav')

        {{-- Konten dinamis --}}
        <div class="min-w-0 flex-1">
            @if($isSessionsPage)
                @include('admin.settings.sessions', [
                    'histories' => $histories,
                    'activeSessions' => $activeSessions,
                    'stats' => $stats,
                ])
            @elseif($isSoundPage)
                @include('admin.settings.sound', [
                    'soundType' => $soundType,
                    'soundFile' => $soundFile,
                ])
            @else
                @include('admin.settings.chatbot', ['templates' => $templates, 'keywordsOf' => $keywordsOf])
            @endif
        </div>
    </div>
</div>

@if(!$isSoundPage && !$isSessionsPage)
    @include('admin.settings.scripts', ['templateData' => $templateData])
@endif
@endsection