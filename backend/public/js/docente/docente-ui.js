/* Componentes compartidos del módulo docente. */
window.DocenteUI = (() => {
    'use strict';
    // El menú es responsabilidad de sidebar-compartida.js para todos los roles.
    function initSidebar() { /* compatibilidad con módulos existentes */ }
    /* Modal reutilizable: solo cambia contenido y callback de confirmación. */
    function initConfirmModal({ modalId, cancelId, confirmId, onConfirm }) {
        const modal = document.getElementById(modalId);
        const cancel = document.getElementById(cancelId);
        const confirm = document.getElementById(confirmId);
        if (!modal || !cancel || !confirm) return null;
        let previousFocus = null;
        function close() { modal.hidden = true; if (previousFocus?.isConnected) previousFocus.focus(); }
        function open() { previousFocus = document.activeElement; modal.hidden = false; cancel.focus(); }
        cancel.addEventListener('click', close);
        confirm.addEventListener('click', () => { onConfirm(); close(); });
        modal.addEventListener('click', event => { if (event.target === modal) close(); });
        document.addEventListener('keydown', event => {
            if (modal.hidden) return;
            if (event.key === 'Escape') { event.preventDefault(); close(); }
            if (event.key === 'Tab') {
                if (event.shiftKey && document.activeElement === cancel) { event.preventDefault(); confirm.focus(); }
                else if (!event.shiftKey && document.activeElement === confirm) { event.preventDefault(); cancel.focus(); }
            }
        });
        return { open, close };
    }
    return { initSidebar, initConfirmModal };
})();
