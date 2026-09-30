<aside class="lg:w-60 lg:shrink-0">
    <nav class="flex flex-col gap-2 lg:sticky lg:top-8 lg:h-[calc(100vh-4rem)] lg:justify-center">
        <a href="{{ route('admin.settings.index') }}"
           class="flex w-full items-center justify-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors
                  {{ request()->routeIs('admin.settings.index') ? 'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
            Template Chatbot
        </a>
        <a href="{{ route('admin.settings.sound') }}"
           class="flex w-full items-center justify-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors
                  {{ request()->routeIs('admin.settings.sound') ? 'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
            Suara Notifikasi
        </a>
    </nav>
</aside>