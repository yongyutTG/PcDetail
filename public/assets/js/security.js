/* Escape at the HTML rendering boundary; keep API/form values unmodified. */
function escapeHtml(value) {
    return String(value == null ? '' : value).replace(/[&<>"']/g, function (character) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[character];
    });
}
function escapeRecord(record) {
    const safe = {};
    Object.keys(record).forEach(function (key) {
        safe[key] = typeof record[key] === 'string' ? escapeHtml(record[key]) : record[key];
    });
    return safe;
}
function pcFetch(url, options = {}) {
    const target = new URL(url, window.location.href);
    const headers = new Headers(options.headers || {});
    if (target.origin === window.location.origin &&
        !['GET', 'HEAD', 'OPTIONS'].includes((options.method || 'GET').toUpperCase())) {
        headers.set('X-CSRF-TOKEN', window.CSRF_TOKEN);
    }
    return fetch(url, { ...options, headers });
}
