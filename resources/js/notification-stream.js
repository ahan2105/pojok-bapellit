class NotificationStream {
    constructor(options = {}) {
        this.streamUrl           = options.streamUrl || '/notifications/stream';
        this.onNotification      = options.onNotification || (() => {});
        this.onUnreadCountChange = options.onUnreadCountChange || (() => {});
        this.initialUnreadCount  = options.initialUnreadCount || 0;

        this.eventSource         = null;
        this.unreadCount         = this.initialUnreadCount;
        this.connected           = false;
        this.reconnectDelay      = 3000;
        this.maxReconnectDelay   = 30000;
        this.currentReconnectDelay = this.reconnectDelay;
    }

    connect() {
        if (this.eventSource) {
            this.eventSource.close();
        }

        // ⭐ Skip ngrok warning page (khusus ngrok free)
       const url = this._buildUrl(this.streamUrl);

        this.eventSource = new EventSource(url, {
            withCredentials: true,
        });

        this.eventSource.addEventListener('notification', (e) => {
            try {
                const notif = JSON.parse(e.data);

                this.unreadCount++;
                this.onUnreadCountChange(this.unreadCount);
                this.onNotification(notif);

                // ⭐ Dispatch global event — biar halaman lain bisa listen
                window.dispatchEvent(new CustomEvent('realtime:notification', {
                    detail: notif,
                }));

                this.currentReconnectDelay = this.reconnectDelay;
            } catch (err) {
                console.error('[SSE] Gagal parse notif:', err);
            }
        });

        // ⭐ Event untuk data-changed (kalau nanti dipakai)
        this.eventSource.addEventListener('data-changed', (e) => {
            try {
                const payload = JSON.parse(e.data);

                window.dispatchEvent(new CustomEvent('realtime:data-changed', {
                    detail: payload,
                }));

                if (typeof this.onDataChanged === 'function') {
                    this.onDataChanged(payload);
                }
            } catch (err) {
                console.error('[SSE] Gagal parse data-changed:', err);
            }
        });

        this.eventSource.addEventListener('reconnect', () => {
            console.log('[SSE] Server minta reconnect');
            this.eventSource.close();
            this._scheduleReconnect();
        });

        this.eventSource.onopen = () => {
            console.log('[SSE] Connected');
            this.connected = true;
            this.currentReconnectDelay = this.reconnectDelay;
        };

        this.eventSource.onerror = () => {
            this.connected = false;
            this.eventSource.close();
            this._scheduleReconnect();
        };
    }

    /**
     * Build URL + skip ngrok warning
     */
    _buildUrl(baseUrl) {
        const param = 'ngrok-skip-browser-warning=true';

        if (baseUrl.includes(param)) {
            return baseUrl;
        }

        return baseUrl.includes('?')
            ? baseUrl + '&' + param
            : baseUrl + '?' + param;
    }

    _scheduleReconnect() {
        console.log(`[SSE] Reconnect dalam ${this.currentReconnectDelay}ms`);
        setTimeout(() => this.connect(), this.currentReconnectDelay);
        this.currentReconnectDelay = Math.min(
            this.currentReconnectDelay * 2,
            this.maxReconnectDelay
        );
    }

    disconnect() {
        if (this.eventSource) {
            this.eventSource.close();
            this.eventSource = null;
        }
    }

    setUnreadCount(count) {
        this.unreadCount = count;
        this.onUnreadCountChange(count);
    }
}

export default NotificationStream;