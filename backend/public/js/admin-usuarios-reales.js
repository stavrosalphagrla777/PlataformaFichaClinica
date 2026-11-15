/* Gestión real y masiva de cuentas: envío POST con CSRF, nunca cambios simulados. */
document.addEventListener('DOMContentLoaded', () => {
    'use strict';
    const $ = id => document.getElementById(id);
    const checks = [...document.querySelectorAll('.user-check')];
    const all = $('selectAll'), modal = $('statusModal');
    let currentAction = null, previousFocus = null;
    function refresh() {
        const n = checks.filter(c => c.checked).length;
        $('selectionCount').textContent = `${n} usuario${n === 1 ? '' : 's'} seleccionado${n === 1 ? '' : 's'}`;
        ['bulkActivate','bulkDeactivate','clearSelection'].forEach(id => $(id).disabled = !n);
        all.checked = checks.length > 0 && checks.every(c => c.checked);
        all.indeterminate = checks.some(c => c.checked) && !all.checked;
    }
    checks.forEach(c => c.addEventListener('change', refresh));
    all.addEventListener('change', () => { checks.forEach(c => c.checked = all.checked); refresh(); });
    $('clearSelection').addEventListener('click', () => { checks.forEach(c => c.checked = false); refresh(); });
    function open(title, description, action) {
        currentAction = action; previousFocus = document.activeElement;
        $('modalTitle').textContent = title;
        $('modalDescription').textContent = description;
        modal.hidden = false; $('cancelStatus').focus();
    }
    function close() { modal.hidden = true; currentAction = null; if (previousFocus?.isConnected) previousFocus.focus(); }
    $('bulkActivate').addEventListener('click', () => open('Activar usuarios', 'Se activarán las cuentas seleccionadas. ¿Confirmas los cambios?', () => {
        $('bulkEstado').value = '1'; $('bulkUsersForm').requestSubmit();
    }));
    $('bulkDeactivate').addEventListener('click', () => open('Desactivar usuarios', 'Se desactivarán las cuentas seleccionadas y sus tokens de acceso. No es posible desactivar tu propia cuenta ni dejar la plataforma sin administradores activos.', () => {
        $('bulkEstado').value = '0'; $('bulkUsersForm').requestSubmit();
    }));
    document.querySelectorAll('[data-individual-form]').forEach(button => button.addEventListener('click', () => {
        const verb = button.dataset.nextStatus;
        open(`${verb === 'activar' ? 'Reactivar' : 'Desactivar'} usuario`, `¿Quieres ${verb} a ${button.dataset.targetName}?`, () => {
            const form = $(button.dataset.individualForm); if (form) form.requestSubmit();
        });
    }));
    $('confirmStatus').addEventListener('click', () => { const cb = currentAction; close(); cb?.(); });
    $('cancelStatus').addEventListener('click', close);
    modal.addEventListener('click', e => { if(e.target === modal) close(); });
    document.addEventListener('keydown', e => {
        if (modal.hidden) return;
        if (e.key === 'Escape') { e.preventDefault(); close(); }
        if (e.key === 'Tab') {
            const first = $('cancelStatus'), last = $('confirmStatus');
            if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
            else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
        }
    });
    refresh();
});
