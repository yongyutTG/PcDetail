const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const token = seconds => 'header.' + Buffer.from(JSON.stringify({exp: Date.now()/1000 + seconds})).toString('base64url') + '.signature';
function setup(access, handler) {
    const storage = new Map([['access_token', access], ['refresh_token', 'test-refresh']]);
    const context = vm.createContext({
        Headers, Date, atob, fetch: handler,
        window: { BASE_URL: '/app/', CSRF_TOKEN: 'test-csrf', location: { href: '' } },
        localStorage: { getItem: k => storage.get(k) || null, setItem: (k,v) => storage.set(k,v), removeItem: k => storage.delete(k) }
    });
    vm.runInContext(fs.readFileSync('public/assets/js/api.js','utf8'), context);
    return {context, storage};
}
const reply = (status, data = {}) => ({ status, ok: status >= 200 && status < 300, json: async () => data });
(async () => {
    let refreshes = 0, requests = 0;
    const fresh = token(180);
    let state = setup(token(-1), async (url, options) => {
        if (url.endsWith('jwt/refresh')) { refreshes++; return reply(200, {access_token: fresh}); }
        requests++;
        assert.equal(options.headers.get('Authorization'), 'Bearer ' + fresh);
        return reply(200);
    });
    await Promise.all([1,2,3].map(() => state.context.apiFetch('/history')));
    assert.equal(refreshes, 1); assert.equal(requests, 3);

    refreshes = 0;
    const old = token(90);
    state = setup(old, async (url, options) => {
        if (url.endsWith('jwt/refresh')) { refreshes++; return reply(200, {access_token: fresh}); }
        return reply(options.headers.get('Authorization') === 'Bearer ' + old ? 401 : 200);
    });
    const results = await Promise.all([1,2,3].map(() => state.context.apiFetch('/ping')));
    assert.equal(refreshes, 1); results.forEach(r => assert.equal(r.status, 200));

    state = setup(token(-1), async () => reply(401));
    await assert.rejects(state.context.apiFetch('/history'), /Authentication expired/);
    assert.equal(state.storage.has('access_token'), false);
    assert.equal(state.context.window.location.href, '/app/login');

    state = setup(token(-1), async () => reply(503));
    await assert.rejects(state.context.apiFetch('/history'), /503/);
    assert.equal(state.storage.get('refresh_token'), 'test-refresh');
    assert.equal(state.context.window.location.href, '');
    console.log('PASS: proactive refresh, concurrent 401 retry, expired login, transient server failure');
})().catch(e => { console.error(e); process.exitCode = 1; });
