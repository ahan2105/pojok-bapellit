{{-- resources/views/components/chatbot.blade.php --}}
{{-- Chatbot AI — WhatsApp Style + Menu Cepat + Fullscreen + Resize --}}

@once
<style>
    /* ═══════════════════════════════════════════════════════════ */
    /* BUBBLE PESAN                                                  */
    /* ═══════════════════════════════════════════════════════════ */
    .chatbot-bg {
        background-color: #ECE5DD;
        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'><path fill='%23d9cdbf' fill-opacity='0.35' d='M30 5l2 3-2 3-2-3zM10 20l2 3-2 3-2-3zM50 20l2 3-2 3-2-3zM30 40l2 3-2 3-2-3zM15 50l2 3-2 3-2-3zM45 50l2 3-2 3-2-3z'/></svg>");
    }
    .chatbot-row-bot  { display: flex; justify-content: flex-start; }
    .chatbot-row-user { display: flex; justify-content: flex-end; }

    .chatbot-bubble-bot,
    .chatbot-bubble-user {
        position: relative;
        max-width: 85%;
        border-radius: 0.5rem;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        color: #1f2937;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
    }
    .chatbot-bubble-bot {
        border-top-left-radius: 0;
        background: #ffffff;
    }
    .chatbot-bubble-bot::before {
        content: '';
        position: absolute;
        left: -8px;
        top: 0;
        border-top: 10px solid transparent;
        border-right: 10px solid #ffffff;
    }
    .chatbot-bubble-user {
        border-top-right-radius: 0;
        background: #DCF8C6;
    }
    .chatbot-bubble-user::before {
        content: '';
        position: absolute;
        right: -8px;
        top: 0;
        border-top: 10px solid transparent;
        border-left: 10px solid #DCF8C6;
    }

    .chatbot-text {
        white-space: pre-wrap;
        word-break: break-word;
        padding-right: 3rem;
    }
    .chatbot-meta {
        margin-top: 0.125rem;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.25rem;
        font-size: 10px;
        color: #6b7280;
    }
    .chatbot-check {
        width: 14px;
        height: 13px;
        color: #0ea5e9;
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* MENU CEPAT                                                    */
    /* ═══════════════════════════════════════════════════════════ */
    .chatbot-quick-wrapper {
        max-width: 100% !important;
        padding: 0 !important;
        background: #ffffff;
    }
    .chatbot-quick-label {
        padding: 0.625rem 0.75rem 0.25rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: #6b7280;
    }
    .chatbot-quick-menu {
        display: flex;
        flex-wrap: wrap;
        gap: 0.375rem;
        padding: 0.5rem 0.75rem 0.75rem;
    }
    .chatbot-quick-btn {
        font-size: 0.75rem;
        padding: 0.375rem 0.625rem;
        border-radius: 9999px;
        background: #ffffff;
        color: #075E54;
        border: 1px solid #075E54;
        cursor: pointer;
        transition: all 0.15s;
        font-weight: 500;
        line-height: 1.2;
    }
    .chatbot-quick-btn:hover { background: #075E54; color: #ffffff; }
    .chatbot-quick-btn:disabled { opacity: 0.4; cursor: not-allowed; }

    /* ═══════════════════════════════════════════════════════════ */
    /* TOMBOL HEADER & RESIZE HANDLE                                 */
    /* ═══════════════════════════════════════════════════════════ */
    .chatbot-header-btn {
        display: flex;
        height: 28px;
        width: 28px;
        align-items: center;
        justify-content: center;
        border-radius: 9999px;
        color: rgba(255, 255, 255, 0.85);
        transition: all 0.15s;
        cursor: pointer;
        background: transparent;
        border: none;
        padding: 0;
    }
    .chatbot-header-btn:hover { background: rgba(255, 255, 255, 0.15); color: #ffffff; }
    .chatbot-header-btn:active { transform: scale(0.9); }
    .chatbot-header-btn svg { width: 16px; height: 16px; }

    #chatbot-resize-handle {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 20px;
        height: 20px;
        cursor: nwse-resize;
        z-index: 10;
        color: rgba(7, 94, 84, 0.3);
        transition: color 0.15s;
    }
    #chatbot-resize-handle:hover { color: rgba(7, 94, 84, 0.8); }
    #chatbot-resize-handle::before {
        content: '';
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 10px;
        height: 10px;
        border-right: 2px solid currentColor;
        border-bottom: 2px solid currentColor;
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* TOMBOL FLOATING — z-index TINGGI biar gak ketimpa            */
    /* ═══════════════════════════════════════════════════════════ */
    #chatbot-toggle {
        position: fixed !important;
        bottom: 1.5rem !important;
        right: 1.5rem !important;
        z-index: 2147483647 !important; /* nilai maksimal int32 */
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* BUBBLE CONTAINER — state default                              */
    /* ═══════════════════════════════════════════════════════════ */
    #chatbot-bubble {
        position: fixed;
        bottom: 6.5rem;
        right: 1.5rem;
        z-index: 2147483646;
        width: 24rem;
        max-width: calc(100vw - 2rem);
    }
    #chatbot-bubble.chatbot-hidden { display: none !important; }

    #chatbot-container {
        display: flex;
        flex-direction: column;
        height: 560px;
        max-height: calc(100vh - 8rem);
        overflow: hidden;
        border-radius: 1rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        background: #ffffff;
    }

    /* State fullscreen */
    #chatbot-bubble.chatbot-fullscreen {
        top: 1rem !important;
        left: 1rem !important;
        right: 1rem !important;
        bottom: 1rem !important;
        width: auto !important;
        max-width: none !important;
        height: auto !important;
    }
    #chatbot-bubble.chatbot-fullscreen #chatbot-container {
        height: 100% !important;
        max-height: none !important;
    }

    /* ═══════════════════════════════════════════════════════════ */
    /* RESPONSIF HP                                                  */
    /* ═══════════════════════════════════════════════════════════ */
    @media (max-width: 640px) {
        #chatbot-bubble {
            left: 0 !important;
            right: 0 !important;
            top: 0 !important;
            bottom: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            height: 100vh !important;
            height: 100dvh !important;
            z-index: 2147483646 !important;
        }
        #chatbot-container {
            height: 100% !important;
            max-height: none !important;
            border-radius: 0 !important;
        }
        #chatbot-drag-handle { cursor: default !important; }
        #chatbot-resize-handle { display: none !important; }
        .chatbot-quick-btn { font-size: 0.7rem; padding: 0.3rem 0.55rem; }
        .chatbot-bubble-bot, .chatbot-bubble-user { max-width: 92%; font-size: 0.8125rem; }

        /* Tombol tetap di kanan bawah, z-index tinggi */
        #chatbot-toggle {
            bottom: 1.25rem !important;
            right: 1.25rem !important;
            z-index: 2147483647 !important;
        }
    }
</style>
@endonce

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- TOMBOL FLOATING                                              --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<button id="chatbot-toggle"
        type="button"
        title="Tanya Asisten AI"
        class="flex h-14 w-14 items-center justify-center rounded-full text-white shadow-xl transition-transform duration-200 hover:scale-110 active:scale-95"
        style="background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);">
    <svg class="h-7 w-7" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
    </svg>
</button>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- BUBBLE CHATBOT                                               --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div id="chatbot-bubble" class="chatbot-hidden">
    <div id="chatbot-container">

        {{-- Header --}}
        <div id="chatbot-drag-handle"
             class="relative flex cursor-move select-none items-center gap-3 px-4 py-3 pr-24"
             style="background: #075E54;">
            <div class="relative flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white/20">
                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <span class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full bg-green-400 ring-2"
                      style="--tw-ring-color: #075E54;"></span>
            </div>
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-semibold text-white">Admin</p>
                <p class="truncate text-[11px] text-white/70">online</p>
            </div>

            <div class="absolute right-2 top-2 flex items-center gap-0.5">
                <button type="button"
                        id="chatbot-fullscreen-btn"
                        onclick="toggleChatbotFullscreen()"
                        title="Perbesar"
                        aria-label="Perbesar"
                        class="chatbot-header-btn">
                    <svg id="chatbot-icon-expand" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                    </svg>
                    <svg id="chatbot-icon-minimize" class="hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 9V5m0 4H5m4 0l-5-5m11 5V5m0 4h4m-4 0l5-5M9 15v4m0-4H5m4 0l-5 5m11-5v4m0-4h4m-4 0l5 5"/>
                    </svg>
                </button>

                <button type="button"
                        onclick="closeChatbot()"
                        title="Tutup chat"
                        aria-label="Tutup chat"
                        class="chatbot-header-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Messages --}}
        <div id="chatbot-messages"
             class="chatbot-bg flex-1 space-y-2 overflow-y-auto p-3"></div>

        {{-- Input --}}
        <form id="chatbot-form"
              class="flex items-end gap-2 px-2 py-2"
              style="background: #f0f0f0;">
            <div class="flex flex-1 items-end rounded-3xl bg-white px-3 py-1.5 shadow-sm">
                <textarea id="chatbot-input"
                          rows="1"
                          placeholder="Ketik pesan atau pilih menu..."
                          class="flex-1 resize-none bg-transparent py-1.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none"
                          maxlength="1000"></textarea>
            </div>
            <button type="submit" id="chatbot-send"
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full text-white shadow-md transition-transform hover:scale-105 active:scale-95 disabled:opacity-50"
                    style="background: #25D366;">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                </svg>
            </button>
        </form>

        <div id="chatbot-resize-handle" title="Tarik untuk ubah ukuran"></div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- SCRIPT CHATBOT                                               --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@once
<script>
(function () {
    'use strict';
    if (window.__CHATBOT_INIT__) return;
    window.__CHATBOT_INIT__ = true;

    document.addEventListener('DOMContentLoaded', initChatbot);
    if (document.readyState !== 'loading') initChatbot();

    function initChatbot() {
        if (window.__CHATBOT_BOUND__) return;
        window.__CHATBOT_BOUND__ = true;

        const toggle       = document.getElementById('chatbot-toggle');
        const bubble       = document.getElementById('chatbot-bubble');
        const container    = document.getElementById('chatbot-container');
        const handle       = document.getElementById('chatbot-drag-handle');
        const resizeHandle = document.getElementById('chatbot-resize-handle');
        const form         = document.getElementById('chatbot-form');
        const input        = document.getElementById('chatbot-input');
        const sendBtn      = document.getElementById('chatbot-send');
        const messages     = document.getElementById('chatbot-messages');
        const fsBtn        = document.getElementById('chatbot-fullscreen-btn');
        const iconExpand   = document.getElementById('chatbot-icon-expand');
        const iconMinimize = document.getElementById('chatbot-icon-minimize');

        if (!toggle || !bubble) return;

        const chatUrl     = '{{ route('chatbot.chat') }}';
        const menusUrl    = '{{ route('chatbot.menus') }}';
        const templateUrl = '{{ route('chatbot.template') }}';
        const csrfToken   = document.querySelector('meta[name="csrf-token"]')?.content;

        const MIN_W = 320;
        const MIN_H = 400;
        const MAX_W = () => Math.min(900, window.innerWidth - 32);
        const MAX_H = () => window.innerHeight - 32;

        let menusLoaded  = false;
        let isFullscreen = false;
        const isMobile   = () => window.matchMedia('(max-width: 640px)').matches;

        function nowTime() {
            const d = new Date();
            return String(d.getHours()).padStart(2, '0') + ':' + String(d.getMinutes()).padStart(2, '0');
        }

        // Hapus inline style — biar CSS class yang atur
        function clearInlineStyles() {
            bubble.removeAttribute('style');
            container.removeAttribute('style');
        }

        // ═══════════════════════════════════════════════════════
        // BUKA / TUTUP / FULLSCREEN
        // ═══════════════════════════════════════════════════════
        window.openChatbot = function () {
            bubble.classList.remove('chatbot-hidden');
            toggle.style.display = 'none';
            setTimeout(() => input && input.focus(), 150);
            if (!menusLoaded) {
                loadMenus();
                menusLoaded = true;
            }
            if (isMobile()) document.body.style.overflow = 'hidden';
        };

        window.closeChatbot = function () {
            bubble.classList.add('chatbot-hidden');
            bubble.classList.remove('chatbot-fullscreen');
            clearInlineStyles();
            toggle.style.display = 'flex';
            isFullscreen = false;
            iconExpand.classList.remove('hidden');
            iconMinimize.classList.add('hidden');
            fsBtn.title = 'Perbesar';
            document.body.style.overflow = '';
        };

        window.toggleChatbotFullscreen = function () {
            isFullscreen = !isFullscreen;

            bubble.classList.toggle('chatbot-fullscreen', isFullscreen);
            iconExpand.classList.toggle('hidden', isFullscreen);
            iconMinimize.classList.toggle('hidden', !isFullscreen);
            fsBtn.title = isFullscreen ? 'Kecilkan' : 'Perbesar';

            // Hapus inline style biar CSS class yang atur
            clearInlineStyles();

            setTimeout(() => { messages.scrollTop = messages.scrollHeight; }, 100);
        };

        toggle.addEventListener('click', window.openChatbot);

        // ═══════════════════════════════════════════════════════
        // DRAG
        // ═══════════════════════════════════════════════════════
        let isDragging = false;
        let dragStartX = 0, dragStartY = 0, dragStartLeft = 0, dragStartTop = 0;

        handle.addEventListener('mousedown', startDrag);
        handle.addEventListener('touchstart', startDrag, { passive: true });

        function startDrag(e) {
            if (isMobile() || isFullscreen) return;
            if (e.target.closest('button')) return;

            isDragging = true;
            const rect = bubble.getBoundingClientRect();
            // Set inline style biar bisa drag
            Object.assign(bubble.style, {
                position: 'fixed',
                top: rect.top + 'px',
                left: rect.left + 'px',
                bottom: 'auto',
                right: 'auto',
                zIndex: '2147483646',
                width: rect.width + 'px',
                maxWidth: 'none',
            });

            const p = e.touches ? e.touches[0] : e;
            dragStartX = p.clientX;
            dragStartY = p.clientY;
            dragStartLeft = rect.left;
            dragStartTop = rect.top;

            document.addEventListener('mousemove', onDrag);
            document.addEventListener('mouseup', stopDrag);
            document.addEventListener('touchmove', onDrag, { passive: false });
            document.addEventListener('touchend', stopDrag);
            document.body.style.userSelect = 'none';
        }

        function onDrag(e) {
            if (!isDragging) return;
            e.preventDefault();
            const p = e.touches ? e.touches[0] : e;
            const newLeft = Math.max(8, Math.min(dragStartLeft + p.clientX - dragStartX, window.innerWidth - bubble.offsetWidth - 8));
            const newTop  = Math.max(8, Math.min(dragStartTop + p.clientY - dragStartY, window.innerHeight - bubble.offsetHeight - 8));
            bubble.style.left = newLeft + 'px';
            bubble.style.top = newTop + 'px';
        }

        function stopDrag() {
            isDragging = false;
            document.removeEventListener('mousemove', onDrag);
            document.removeEventListener('mouseup', stopDrag);
            document.removeEventListener('touchmove', onDrag);
            document.removeEventListener('touchend', stopDrag);
            document.body.style.userSelect = '';
        }

        // ═══════════════════════════════════════════════════════
        // RESIZE
        // ═══════════════════════════════════════════════════════
        let isResizing = false;
        let resizeStartX = 0, resizeStartY = 0, resizeStartW = 0, resizeStartH = 0;

        resizeHandle.addEventListener('mousedown', startResize);
        resizeHandle.addEventListener('touchstart', startResize, { passive: true });

        function startResize(e) {
            if (isMobile() || isFullscreen) return;
            e.preventDefault();
            e.stopPropagation();

            isResizing = true;
            const rect = bubble.getBoundingClientRect();

            Object.assign(bubble.style, {
                position: 'fixed',
                top: rect.top + 'px',
                left: rect.left + 'px',
                bottom: 'auto',
                right: 'auto',
                zIndex: '2147483646',
                width: rect.width + 'px',
                maxWidth: 'none',
            });
            container.style.height = rect.height + 'px';

            const p = e.touches ? e.touches[0] : e;
            resizeStartX = p.clientX;
            resizeStartY = p.clientY;
            resizeStartW = rect.width;
            resizeStartH = rect.height;

            document.addEventListener('mousemove', onResize);
            document.addEventListener('mouseup', stopResize);
            document.addEventListener('touchmove', onResize, { passive: false });
            document.addEventListener('touchend', stopResize);
            document.body.style.userSelect = 'none';
        }

        function onResize(e) {
            if (!isResizing) return;
            e.preventDefault();
            const p = e.touches ? e.touches[0] : e;
            const newW = Math.max(MIN_W, Math.min(resizeStartW + p.clientX - resizeStartX, MAX_W()));
            const newH = Math.max(MIN_H, Math.min(resizeStartH + p.clientY - resizeStartY, MAX_H()));
            bubble.style.width = newW + 'px';
            container.style.height = newH + 'px';
        }

        function stopResize() {
            isResizing = false;
            document.removeEventListener('mousemove', onResize);
            document.removeEventListener('mouseup', stopResize);
            document.removeEventListener('touchmove', onResize);
            document.removeEventListener('touchend', stopResize);
            document.body.style.userSelect = '';
        }

        // ═══════════════════════════════════════════════════════
        // INPUT
        // ═══════════════════════════════════════════════════════
        input.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 100) + 'px';
        });

        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                form.requestSubmit();
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !bubble.classList.contains('chatbot-hidden')) {
                if (isFullscreen) {
                    window.toggleChatbotFullscreen();
                } else {
                    window.closeChatbot();
                }
            }
        });

        // ═══════════════════════════════════════════════════════
        // RENDER BUBBLE PESAN
        // ═══════════════════════════════════════════════════════
        function addMessage(text, isUser) {
            const row = document.createElement('div');
            row.className = isUser ? 'chatbot-row-user' : 'chatbot-row-bot';

            const b = document.createElement('div');
            b.className = isUser ? 'chatbot-bubble-user' : 'chatbot-bubble-bot';

            const textEl = document.createElement('div');
            textEl.className = 'chatbot-text';
            textEl.textContent = text;
            b.appendChild(textEl);

            const meta = document.createElement('div');
            meta.className = 'chatbot-meta';
            meta.innerHTML = `<span>${nowTime()}</span>`;
            if (isUser) {
                meta.innerHTML += `<svg class="chatbot-check" viewBox="0 0 16 15" fill="currentColor"><path d="M15.01 3.316l-.478-.372a.365.365 0 00-.51.063L8.666 9.879a.32.32 0 01-.484.033l-.358-.325a.319.319 0 00-.484.032l-.378.483a.418.418 0 00.036.541l1.32 1.266c.143.14.361.125.484-.033l6.272-8.048a.366.366 0 00-.063-.51zm-4.1 0l-.478-.372a.365.365 0 00-.51.063L4.566 9.879a.32.32 0 01-.484.033L1.891 7.769a.366.366 0 00-.515.006l-.423.433a.364.364 0 00.006.514l3.258 3.185c.143.14.361.125.484-.033l6.272-8.048a.365.365 0 00-.063-.51z"/></svg>`;
            }
            b.appendChild(meta);

            row.appendChild(b);
            messages.appendChild(row);
            messages.scrollTop = messages.scrollHeight;
        }

        function addTyping() {
            const row = document.createElement('div');
            row.className = 'chatbot-row-bot';
            row.id = 'chatbot-typing';

            const b = document.createElement('div');
            b.className = 'chatbot-bubble-bot';
            b.innerHTML = `
                <div class="flex items-center gap-1 py-1">
                    <span class="h-2 w-2 animate-bounce rounded-full bg-gray-400" style="animation-delay:0ms"></span>
                    <span class="h-2 w-2 animate-bounce rounded-full bg-gray-400" style="animation-delay:150ms"></span>
                    <span class="h-2 w-2 animate-bounce rounded-full bg-gray-400" style="animation-delay:300ms"></span>
                </div>`;
            row.appendChild(b);
            messages.appendChild(row);
            messages.scrollTop = messages.scrollHeight;
        }

        function removeTyping() {
            document.getElementById('chatbot-typing')?.remove();
        }

        // ═══════════════════════════════════════════════════════
        // MENU CEPAT
        // ═══════════════════════════════════════════════════════
        async function loadMenus() {
            try {
                const res = await fetch(menusUrl, {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin',
                });
                if (!res.ok) return;
                const data = await res.json();
                if (data.menus && data.menus.length) renderQuickMenu(data.menus);
            } catch (err) {
                console.error('[Chatbot] Gagal load menu:', err);
            }
        }

        function renderQuickMenu(menus) {
            if (document.getElementById('chatbot-quick-menu')) return;

            const row = document.createElement('div');
            row.className = 'chatbot-row-bot';
            row.id = 'chatbot-quick-menu';

            const wrapper = document.createElement('div');
            wrapper.className = 'chatbot-bubble-bot chatbot-quick-wrapper';

            const label = document.createElement('div');
            label.className = 'chatbot-quick-label';
            label.textContent = '📌 Pilih topik cepat:';
            wrapper.appendChild(label);

            const menuBox = document.createElement('div');
            menuBox.className = 'chatbot-quick-menu';

            menus.forEach(m => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'chatbot-quick-btn';
                btn.textContent = m.label;
                btn.dataset.id = m.id;
                btn.addEventListener('click', () => handleQuickMenu(m.id, btn));
                menuBox.appendChild(btn);
            });

            wrapper.appendChild(menuBox);
            row.appendChild(wrapper);
            messages.appendChild(row);
            messages.scrollTop = messages.scrollHeight;
        }

        function showMenusAgain(delay = 300) {
            setTimeout(() => {
                if (!document.getElementById('chatbot-quick-menu')) {
                    loadMenus();
                }
            }, delay);
        }

        async function handleQuickMenu(id, btnEl) {
            document.querySelectorAll('.chatbot-quick-btn').forEach(b => b.disabled = true);
            addMessage(btnEl.textContent.trim(), true);

            document.getElementById('chatbot-quick-menu')?.remove();
            addTyping();

            try {
                const res = await fetch(templateUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ id }),
                });
                const data = await res.json();
                removeTyping();
                addMessage(res.ok ? (data.reply || 'Maaf, saya tidak mengerti.') : ('⚠️ ' + (data.error || 'Gagal memuat jawaban')), false);
            } catch (err) {
                removeTyping();
                addMessage('⚠️ Koneksi gagal. Coba lagi.', false);
                console.error('[Chatbot]', err);
            } finally {
                showMenusAgain(300);
            }
        }

        // ═══════════════════════════════════════════════════════
        // KIRIM PESAN MANUAL
        // ═══════════════════════════════════════════════════════
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const message = input.value.trim();
            if (!message) return;

            document.getElementById('chatbot-quick-menu')?.remove();

            addMessage(message, true);
            input.value = '';
            input.style.height = 'auto';
            sendBtn.disabled = true;
            addTyping();

            try {
                const res = await fetch(chatUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ message }),
                });
                const data = await res.json();
                removeTyping();
                addMessage(res.ok ? (data.reply || 'Maaf, saya tidak mengerti.') : ('⚠️ ' + (data.error || 'Gagal menghubungi AI')), false);
            } catch (err) {
                removeTyping();
                addMessage('⚠️ Koneksi gagal. Coba lagi.', false);
                console.error('[Chatbot]', err);
            } finally {
                sendBtn.disabled = false;
                input.focus();
                showMenusAgain(400);
            }
        });
    }
})();
</script>
@endonce