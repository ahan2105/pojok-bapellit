import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '');
    const tunnelHost = env.VITE_TUNNEL_HOST || '';
    const isTunnelMode = tunnelHost !== '';

    const baseConfig = {
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
        ],
    };

    if (isTunnelMode) {
        baseConfig.server = {
            host: '0.0.0.0',
            hmr: {
                host: tunnelHost,
                protocol: 'wss',
                port: 443,
            },
            cors: true,
        };
        console.log(`[Vite] Mode: TUNNEL (${tunnelHost})`);
    } else {
        console.log('[Vite] Mode: LOCAL');
    }

    return baseConfig;
});