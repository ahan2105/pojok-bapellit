// public/sw.js

self.addEventListener('push', function (event) {
    let data = {};

    // Coba parse JSON dulu
    if (event.data) {
        const text = event.data.text();
        try {
            data = JSON.parse(text);
        } catch (e) {
            // Kalau bukan JSON (misal dari DevTools test), pakai sebagai body
            data = {
                title: 'Notifikasi Baru',
                message: text,
            };
        }
    }

    const title = data.title || 'Notifikasi Baru';
    const options = {
        body: data.message || 'Anda punya notifikasi baru',
        icon: '/favicon.ico',
        badge: '/favicon.ico',
        vibrate: [200, 100, 200],
        data: {
            url: data.url || '/',
        },
        requireInteraction: false,
        tag: 'notif-' + Date.now(),   // biar notif gak ketumpuk
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    // Ambil URL dari data notif
    let urlToOpen = event.notification.data.url || '/';

    // Pastiin URL absolute
    if (!urlToOpen.startsWith('http')) {
        urlToOpen = self.location.origin + urlToOpen;
    }

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
            // Coba cari tab yang udah kebuka dengan URL sama
            for (const client of clientList) {
                if (client.url === urlToOpen && 'focus' in client) {
                    return client.focus();
                }
            }

            // Kalau gak ada, cari tab apa aja yang kebuka, fokusin
            for (const client of clientList) {
                if ('focus' in client) {
                    client.focus();
                    if ('navigate' in client) {
                        return client.navigate(urlToOpen);
                    }
                    return;
                }
            }

            // Kalau gak ada tab, buka window baru
            if (clients.openWindow) {
                return clients.openWindow(urlToOpen);
            }
        }).catch((err) => {
            console.error('Notification click error:', err);
        })
    );
});