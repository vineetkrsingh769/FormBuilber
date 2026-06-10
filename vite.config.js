import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

const LARAVEL_URL = 'http://127.0.0.1:8000';

function laravelProxy() {
    return {
        name: 'laravel-proxy',
        configureServer(server) {
            return () => {
                server.middlewares.use(async (req, res, next) => {
                    const url = req.url ?? '/';
                    const [pathname, query = ''] = url.split('?');

                    const isViteAsset =
                        pathname.startsWith('/@') ||
                        pathname.startsWith('/resources/') ||
                        pathname.startsWith('/node_modules/') ||
                        pathname.startsWith('/fonts/') ||
                        /\.(js|mjs|ts|jsx|tsx|css|json|map|woff2?|ttf|svg|png|jpg|jpeg|gif|ico)$/.test(pathname);

                    if (isViteAsset) {
                        return next();
                    }

                    const laravelPath = (pathname === '/index.html' || pathname === '/') ? '/' : pathname;
                    const target = `${LARAVEL_URL}${laravelPath}${query ? `?${query}` : ''}`;

                    try {
                        const response = await fetch(target, {
                            method: req.method ?? 'GET',
                            headers: {
                                Accept: req.headers.accept ?? '*/*',
                                'X-Forwarded-Host': req.headers.host ?? 'localhost:5173',
                                'X-Forwarded-Proto': 'http',
                            },
                        });

                        res.statusCode = response.status;
                        response.headers.forEach((value, key) => {
                            if (!['content-encoding', 'transfer-encoding', 'connection'].includes(key.toLowerCase())) {
                                res.setHeader(key, value);
                            }
                        });

                        const body = Buffer.from(await response.arrayBuffer());
                        res.end(body);
                    } catch {
                        res.statusCode = 502;
                        res.setHeader('Content-Type', 'text/html');
                        res.end(`<h1>Laravel server not running</h1><p>Run: <code>php artisan serve</code></p><p>Or use: <code>npm run dev</code> to start both servers.</p>`);
                    }
                });
            };
        },
    };
}

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
        laravelProxy(),
    ],
    server: {
        host: '127.0.0.1',
        port: 5173,
        strictPort: true,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
