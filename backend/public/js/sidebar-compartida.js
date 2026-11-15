/* Una sola acción de menú para todas las páginas, con estado ARIA sincronizado. */
(() => {
    const init = () => {
        const app = document.getElementById('app');
        const button = document.getElementById('menuToggle');
        if (!app || !button || button.dataset.sidebarReady) return;
        button.dataset.sidebarReady = 'true';
        const sync = () => {
            const visible = !app.classList.contains('sidebar-hidden');
            button.setAttribute('aria-expanded', String(visible));
            button.setAttribute('aria-label', visible ? 'Ocultar menú lateral' : 'Mostrar menú lateral');
        };
        button.addEventListener('click', () => { app.classList.toggle('sidebar-hidden'); sync(); });
        sync();
    };
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
    else init();
})();
