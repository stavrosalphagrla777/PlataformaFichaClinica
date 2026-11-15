/*
 * Sincroniza la navegación horizontal de las etapas con la navegación lateral.
 * El sidebar ya construye los enlaces reales en Laravel con el código del caso
 * y aplica los permisos; no reconstruimos rutas ni creamos enlaces '#'.
 * Se ejecuta tras los scripts originales de cada formulario.
 */
(() => {
    'use strict';
    const pairs = {
        tabResumen: 'sidebarFicha',
        tabAdmision: 'sidebarAdmision',
        tabTriage: 'sidebarTriage',
        tabSignos: 'sidebarSignos',
        tabABCDE: 'sidebarABCDE',
        tabAnamnesis: 'sidebarAnamnesis',
        tabExaFisico: 'sidebarExaFisico',
        tabProcedimientos: 'sidebarProcedimientos',
        tabMedicamentos: 'sidebarMedicamentos',
        tabExamenes: 'sidebarExamenes',
        tabAccEnfermeria: 'sidebarAccEnfermeria',
        tabDestino: 'sidebarDestino'
    };
    function synchronizeLinks() {
        for (const [tabId, sidebarId] of Object.entries(pairs)) {
            const tab = document.getElementById(tabId);
            const sidebar = document.getElementById(sidebarId);
            if (!tab || !sidebar || !sidebar.href) continue;
            const url = new URL(sidebar.href, window.location.href);
            if (url.origin !== window.location.origin || url.hash) continue;
            tab.href = url.href;
        }
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', synchronizeLinks);
    } else {
        synchronizeLinks();
    }
})();
