document.addEventListener('DOMContentLoaded', () => {
    'use strict';
    const bell = document.getElementById('fichaBell');
    const toggle = document.getElementById('fichaBellToggle');
    const menu = document.getElementById('fichaBellMenu');
    if (!bell || !toggle || !menu) return;
    const header = document.querySelector('.main header.topbar, header.topbar, .main .topbar');
    if (header) {
        const userInfo = header.querySelector('.user-info, .user-details');
        if (userInfo) header.insertBefore(bell, userInfo);
        else header.appendChild(bell);
    } else {
        bell.classList.add('ficha-bell-floating');
        bell.style.cssText = 'position:fixed;top:12px;right:10px;color:white;background:#0c4b77;border-radius:40px;';
    }
    const close = () => { menu.hidden=true;toggle.setAttribute('aria-expanded','false'); };
    toggle.addEventListener('click', () => {
        const open=menu.hidden;
        menu.hidden=!open;
        toggle.setAttribute('aria-expanded',String(open));
    });
    document.addEventListener('click', event => { if (!bell.contains(event.target)) close(); });
    document.addEventListener('keydown', event => { if (event.key==='Escape' && !menu.hidden) { close();toggle.focus(); } });
});
