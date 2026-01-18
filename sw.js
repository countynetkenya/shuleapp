const CACHE_NAME = 'shuleapp-v1';
const ASSETS_CACHE = 'shuleapp-assets-v1';

const STATIC_ASSETS = [
    '/assets/shuleapp/jquery.min.js',
    '/assets/vendor/bootstrap/css/bootstrap.min.css',
    '/assets/vendor/bootstrap/js/bootstrap.min.js',
    '/assets/shuleapp/inilabs.css',
    '/assets/shuleapp/inilabs.js',
    '/assets/shuleapp/style.js',
    '/assets/shuleapp/combined.css',
    '/assets/pace/pace.css',
    '/assets/toastr/toastr.min.js',
    '/assets/toastr/toastr.min.css',
    '/assets/fonts/font-awesome.css',
    '/assets/fonts/icomoon.css',
    '/assets/fonts/ini-icon.css',
    '/assets/vendor/highcharts/highcharts.js',
    '/assets/vendor/highcharts/modules/exporting.js',
    '/assets/vendor/google-fonts/fonts.css'
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(ASSETS_CACHE)
            .then(cache => {
                console.log('Opened cache');
                return cache.addAll(STATIC_ASSETS);
            })
    );
});

self.addEventListener('activate', event => {
    const cacheWhitelist = [CACHE_NAME, ASSETS_CACHE];
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheWhitelist.indexOf(cacheName) === -1) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});

self.addEventListener('fetch', event => {
    const url = new URL(event.request.url);

    // Cache First for Assets
    if (url.pathname.startsWith('/assets/')) {
        event.respondWith(
            caches.match(event.request)
                .then(response => {
                    if (response) {
                        return response;
                    }
                    return fetch(event.request).then(
                        response => {
                            if(!response || response.status !== 200 || response.type !== 'basic') {
                                return response;
                            }
                            const responseToCache = response.clone();
                            caches.open(ASSETS_CACHE)
                                .then(cache => {
                                    cache.put(event.request, responseToCache);
                                });
                            return response;
                        }
                    );
                })
        );
    } else {
        // Network First for everything else
        event.respondWith(
            fetch(event.request)
                .then(response => {
                     // Check if we received a valid response
                    if(!response || response.status !== 200 || response.type !== 'basic') {
                        return response;
                    }

                    // Clone the response
                    const responseToCache = response.clone();

                    caches.open(CACHE_NAME)
                        .then(cache => {
                            cache.put(event.request, responseToCache);
                        });

                    return response;
                })
                .catch(() => {
                    return caches.match(event.request);
                })
        );
    }
});
