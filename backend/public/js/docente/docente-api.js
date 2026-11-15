/* Cliente de sesión Laravel con CSRF. Sin datos ficticios ni tokens persistentes. */
window.DocenteAPI = (() => {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    async function request(url, options = {}) {
        const headers = {Accept: 'application/json', 'X-CSRF-TOKEN': csrf, ...(options.headers || {})};
        if (options.body && !(options.body instanceof FormData)) headers['Content-Type'] = 'application/json';
        const response = await fetch(url, {credentials: 'same-origin', ...options, headers});
        let result;
        try { result = await response.json(); } catch { result = {}; }
        if (!response.ok) {
            const errors = result.errors ? Object.values(result.errors).flat().join(' ') : '';
            throw new Error(errors || result.message || `Error HTTP ${response.status}`);
        }
        return result;
    }
    return {
        get: url => request(url),
        send: (url, method, data) => request(url, {method, body: data instanceof FormData ? data : JSON.stringify(data)}),
        remove: url => request(url, {method: 'DELETE'})
    };
})();
