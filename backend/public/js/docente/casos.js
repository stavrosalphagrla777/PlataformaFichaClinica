/* Vista docente: listado y consulta de casos clínicos. */
document.addEventListener('DOMContentLoaded', () => {
    'use strict';
    window.DocenteUI?.initSidebar();
    const $ = id => document.getElementById(id);
    let rows = [...document.querySelectorAll('#caseRows tr[data-search]')];

    function filterCases() {
        const query = $('buscar').value.trim().toLocaleLowerCase('es');
        const level = $('nivel').value;
        const type = $('tipo').value;
        const status = $('estado').value;
        const mine = $('propios').value;
        let visible = 0;
        rows.forEach(row => {
            const match = row.dataset.search.includes(query)
                && (!level || row.dataset.nivel === level)
                && (!type || row.dataset.tipo === type)
                && (!status || row.dataset.estado === status)
                && (!mine || row.dataset.propio === mine);
            row.hidden = !match;
            if (match) visible++;
        });
        $('resultCount').textContent = rows.length ? `${visible} de ${rows.length} casos visibles` : '';
    }

    ['buscar', 'nivel', 'tipo', 'estado', 'propios'].forEach(id => {
        $(id).addEventListener(id === 'buscar' ? 'input' : 'change', filterCases);
    });
    $('limpiar').addEventListener('click', () => {
        $('buscar').value = '';
        ['nivel', 'tipo', 'estado', 'propios'].forEach(id => { $(id).value = ''; });
        filterCases();
    });

    // Reconstruir únicamente etiquetas de formato seguras provenientes del editor Quill.
    // No se agrega HTML crudo al DOM; se ignoran scripts, imágenes y atributos peligrosos.
    function richContent(html) {
        const container = document.createElement('div');
        container.className = 'case-rich-text';
        if (!html) { container.textContent = '—'; return container; }
        const parsed = new DOMParser().parseFromString(String(html), 'text/html');
        const allowed = new Set(['P','BR','H1','H2','H3','STRONG','B','EM','I','U','S','UL','OL','LI','BLOCKQUOTE','A']);
        const copy = (node, parent) => {
            if (node.nodeType === Node.TEXT_NODE) { parent.append(document.createTextNode(node.nodeValue)); return; }
            if (node.nodeType !== Node.ELEMENT_NODE || ['SCRIPT','STYLE','IFRAME','OBJECT','SVG','MATH','IMG','FORM'].includes(node.tagName)) return;
            const tag = allowed.has(node.tagName) ? node.tagName.toLowerCase() : 'span';
            const element = document.createElement(tag);
            if (tag === 'a') {
                try {
                    const target = new URL(node.getAttribute('href'), window.location.origin);
                    if (!['https:', 'http:'].includes(target.protocol)) return;
                    element.href = target.href; element.target = '_blank'; element.rel = 'noopener noreferrer';
                } catch { return; }
            }
            for (const child of node.childNodes) copy(child, element);
            parent.append(element);
        };
        for (const child of parsed.body.childNodes) copy(child, container);
        return container;
    }

    // Modal de consulta: reutiliza los estilos generales; no modifica registros.
    const modal = $('detailModal');
    const closeButton = $('closeModal');
    const deleteButton = $('deleteCase');
    const deleteError = $('caseDeleteError');
    const confirmModal = $('deleteConfirmModal');
    const confirmText = $('deleteConfirmText');
    const cancelDelete = $('cancelDeleteCase');
    const confirmDelete = $('confirmDeleteCase');
    let deleting = false;
    let currentCase = null;
    let currentRow = null;
    let previousFocus = null;
    let previousBodyOverflow = null;
    let previousDocumentOverflow = null;
    function closeModal() {
        if (deleting) return;
        confirmModal.hidden = true;
        modal.hidden = true;
        currentCase = null;
        currentRow = null;
        deleteButton.hidden = true;
        deleteError.hidden = true;
        deleteError.textContent = '';
        if (previousBodyOverflow !== null) {
            document.body.style.overflow = previousBodyOverflow;
            document.documentElement.style.overflow = previousDocumentOverflow;
            previousBodyOverflow = null;
            previousDocumentOverflow = null;
        }
        if (previousFocus?.isConnected) previousFocus.focus();
    }
    document.querySelectorAll('[data-view]').forEach(button => {
        button.addEventListener('click', () => {
            let data;
            try { data = JSON.parse(button.dataset.view); }
            catch { return; }
            currentCase = data;
            currentRow = button.closest('tr');
            deleteButton.hidden = !data.puedeEliminar;
            deleteError.hidden = true;
            deleteError.textContent = '';
            const fields = [
                ['Código', data.codigo], ['Título', data.titulo], ['Creado por', data.creadoPor],
                ['Descripción', data.descripcion], ['Antecedentes', data.antecedentes], ['Nivel', data.nivel],
                ['Tipo', data.tipo], ['Estado', data.estado],
                ['Duración máxima', data.duracion != null && data.duracion !== '' ? `${data.duracion} minutos` : '—'],
                ['Prioridad ESI', data.esi]
            ];
            const details = $('detailContent');
            details.replaceChildren();
            fields.forEach(([label, value]) => {
                const term = document.createElement('dt');
                const description = document.createElement('dd');
                term.textContent = label;
                if (label === 'Descripción' || label === 'Antecedentes') description.append(richContent(value));
                else description.textContent = value ?? '—';
                details.append(term, description);
            });
            previousFocus = document.activeElement;
            previousBodyOverflow = document.body.style.overflow;
            previousDocumentOverflow = document.documentElement.style.overflow;
            document.body.style.overflow = 'hidden';
            document.documentElement.style.overflow = 'hidden';
            modal.hidden = false;
            const scrollableDetails = modal.querySelector('.modal-body');
            if (scrollableDetails) scrollableDetails.scrollTop = 0;
            closeButton.focus();
        });
    });
    // Confirmación visual consistente con el resto de la plataforma.
    // El backend vuelve a verificar autoría, rol y existencia de fichas iniciadas.
    function cancelDeleteModal() {
        if (deleting) return;
        confirmModal.hidden = true;
        modal.hidden = false;
        deleteButton.focus();
    }
    deleteButton.addEventListener('click', () => {
        if (!currentCase?.puedeEliminar || !Number.isInteger(Number(currentCase.id)) || deleting) return;
        confirmText.textContent = `¿Eliminar el caso ${currentCase.codigo || ''} · ${currentCase.titulo || ''}?`;
        deleteError.hidden = true;
        deleteError.textContent = '';
        modal.hidden = true;
        confirmModal.hidden = false;
        cancelDelete.focus();
    });
    cancelDelete.addEventListener('click', cancelDeleteModal);
    confirmModal.addEventListener('click', event => {
        if (event.target === confirmModal) cancelDeleteModal();
    });
    confirmDelete.addEventListener('click', async () => {
        if (deleting || !currentCase?.puedeEliminar || !Number.isInteger(Number(currentCase.id))) return;
        deleting = true;
        confirmDelete.disabled = true;
        cancelDelete.disabled = true;
        confirmDelete.textContent = 'Eliminando…';
        const codigo = currentCase.codigo || 'este caso';
        try {
            const response = await window.DocenteAPI.remove(`/docente/casos/${encodeURIComponent(currentCase.id)}`);
            const deletedRow = currentRow;
            deleting = false;
            closeModal();
            deletedRow?.remove();
            rows = rows.filter(row => row !== deletedRow);
            if (rows.length === 0) {
                const empty = document.createElement('tr');
                const cell = document.createElement('td');
                cell.colSpan = 8;
                cell.className = 'empty';
                cell.textContent = 'No hay casos clínicos registrados.';
                empty.append(cell);
                $('caseRows').append(empty);
            }
            $('caseActionMessage').textContent = response.message || `Caso ${codigo} eliminado.`;
            filterCases();
        } catch (error) {
            confirmModal.hidden = true;
            modal.hidden = false;
            deleteError.textContent = error.message || 'No fue posible eliminar el caso.';
            deleteError.hidden = false;
            deleteButton.focus();
        } finally {
            deleting = false;
            confirmDelete.disabled = false;
            cancelDelete.disabled = false;
            confirmDelete.textContent = 'Sí, eliminar caso';
        }
    });
    closeButton.addEventListener('click', closeModal);
    modal.addEventListener('click', event => {
        if (event.target === modal) closeModal();
    });
    document.addEventListener('keydown', event => {
        const activeModal = !confirmModal.hidden ? confirmModal : (!modal.hidden ? modal : null);
        if (!activeModal) return;
        if (event.key === 'Escape') {
            event.preventDefault();
            if (!confirmModal.hidden) cancelDeleteModal();
            else closeModal();
            return;
        }
        if (event.key === 'Tab') {
            const focusable = [...activeModal.querySelectorAll('button:not([disabled]), a[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled])')].filter(el => !el.hidden);
            if (!focusable.length) { event.preventDefault(); return; }
            const first = focusable[0], last = focusable[focusable.length - 1];
            if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
            else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
        }
    });
    filterCases();
});
