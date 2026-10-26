let refreshPromise = null;
let loginRedirectStarted = false;

function requireLogin() {
    localStorage.removeItem('access_token');
    localStorage.removeItem('refresh_token');
    if (!loginRedirectStarted) {
        loginRedirectStarted = true;
        window.location.href = window.BASE_URL + 'login';
    }
    throw new Error('Authentication expired. Please login again.');
}

// Only a scheduling hint: the server still verifies signatures and account status.
function tokenNeedsRefresh(token) {
    if (!token) return true;
    try {
        const part = token.split('.')[1].replace(/-/g, '+').replace(/_/g, '/');
        const payload = JSON.parse(atob(part.padEnd(Math.ceil(part.length / 4) * 4, '=')));
        return typeof payload.exp !== 'number' || payload.exp <= Date.now() / 1000 + 15;
    } catch (_) {
        return true;
    }
}

function refreshAccessToken() {
    if (refreshPromise) return refreshPromise;
    refreshPromise = (async () => {
        const refreshToken = localStorage.getItem('refresh_token');
        if (!refreshToken) return requireLogin();
        const response = await fetch(window.BASE_URL + 'jwt/refresh', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.CSRF_TOKEN
            },
            body: JSON.stringify({ refresh_token: refreshToken })
        });
        if (response.status === 401) return requireLogin();
        if (!response.ok) throw new Error('Token refresh failed: HTTP ' + response.status);
        const data = await response.json();
        if (typeof data.access_token !== 'string' || !data.access_token) {
            throw new Error('Invalid token refresh response');
        }
        localStorage.setItem('access_token', data.access_token);
        return data.access_token;
    })().finally(() => { refreshPromise = null; });
    return refreshPromise;
}

async function apiFetch(url, options = {}) {
    let token = localStorage.getItem('access_token');
    if (tokenNeedsRefresh(token)) token = await refreshAccessToken();
    const send = accessToken => {
        const headers = new Headers(options.headers || {});
        headers.set('Accept', 'application/json');
        headers.set('X-CSRF-TOKEN', window.CSRF_TOKEN);
        headers.set('Authorization', 'Bearer ' + accessToken);
        return fetch(url, { ...options, headers });
    };
    let response = await send(token);
    if (response.status !== 401) return response;

    // Another request may already have refreshed while this response was in flight.
    const latest = localStorage.getItem('access_token');
    token = latest && latest !== token && !tokenNeedsRefresh(latest)
        ? latest : await refreshAccessToken();
    response = await send(token);
    if (response.status === 401) return requireLogin();
    return response;
}
