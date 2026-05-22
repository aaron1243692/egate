<script>
(function () {
    'use strict';

    const TOKEN_URL = @json(route('csrf.token'));

    function readMetaToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    }

    function writeMetaToken(token) {
        const meta = document.querySelector('meta[name="csrf-token"]');

        if (meta && token) {
            meta.content = token;
        }
    }

    function applyResponseToken(response) {
        const token = response.headers.get('X-CSRF-TOKEN');

        if (token) {
            writeMetaToken(token);
        }
    }

    async function refreshFromServer(fresh = false) {
        const url = new URL(TOKEN_URL, window.location.origin);

        if (fresh) {
            url.searchParams.set('fresh', '1');
        }

        const response = await fetch(url.toString(), {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        if (!response.ok) {
            throw new Error('Unable to refresh CSRF token.');
        }

        applyResponseToken(response);

        const payload = await response.json();
        writeMetaToken(payload.token || '');

        return readMetaToken();
    }

    function headers(extra = {}) {
        const token = readMetaToken();

        return {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            ...(token ? { 'X-CSRF-TOKEN': token } : {}),
            ...extra,
        };
    }

    async function request(url, options = {}, allowRetry = true) {
        const init = {
            credentials: 'same-origin',
            ...options,
            headers: {
                ...headers(),
                ...(options.headers || {}),
            },
        };

        let response = await fetch(url, init);
        applyResponseToken(response);

        if (response.status === 419 && allowRetry) {
            await refreshFromServer(true);

            init.headers = {
                ...headers(),
                ...(options.headers || {}),
            };

            response = await fetch(url, init);
            applyResponseToken(response);
        }

        return response;
    }

    window.EgateCsrf = {
        getToken: readMetaToken,
        setToken: writeMetaToken,
        refreshFromServer,
        headers,
        request,
    };
})();
</script>
