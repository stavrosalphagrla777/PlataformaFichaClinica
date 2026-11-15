(() => {
    'use strict';
    const $ = (id) => document.getElementById(id);
    const form = $('assignmentForm');
    if (!form) return;

    const caseSelect = $('caso');
    const courseSelect = $('curso');
    const levelSelect = $('nivel');
    const state = () => form.querySelector('input[name="estado"]:checked')?.value || 'Activa';
    let assignments = [];

    window.DocenteUI?.initSidebar();

    function syncCourses(preferred = '') {
        const level = levelSelect.value;
        const options = level === '3.º medio' ? ['Todos los cursos', '3.º Medio A', '3.º Medio B', '3.º Medio C'] : level === '4.º medio' ? ['Todos los cursos', '4.º Medio A', '4.º Medio B', '4.º Medio C', '4.º Medio D'] : [];
        courseSelect.replaceChildren(new Option(options.length ? 'Seleccionar curso' : 'Selecciona primero el nivel', ''));
        options.forEach((course) => courseSelect.add(new Option(course, course)));
        if (options.includes(preferred)) courseSelect.value = preferred;
    }
    function syncCaseLevel() {
        const option = caseSelect.selectedOptions[0];
        const level = option?.dataset.nivel || '';
        [...levelSelect.options].forEach((item) => { item.disabled = !!level && level !== 'Ambos niveles' && item.value !== '' && item.value !== level; });
        if (!caseSelect.value) { levelSelect.value = ''; syncCourses(); }
        if (level && level !== 'Ambos niveles' && levelSelect.value !== level) {
            levelSelect.value = level;
            syncCourses();
        }
        syncDuration();
        $('caseHint').textContent = level && level !== 'Ambos niveles' ? `Caso disponible para ${level}.` : 'Selecciona el nivel y el curso correspondiente.';
    }
    function syncDuration() {
        const minutes = Number(caseSelect.selectedOptions[0]?.dataset.duracion);
        $('duracion').value = caseSelect.value && Number.isInteger(minutes) && minutes >= 1 && minutes <= 10080 ? String(minutes) : '';
    }
    function getDuration() {
        const minutes = Number($('duracion').value);
        return $('duracion').value.trim() !== '' && Number.isInteger(minutes) && minutes >= 1 && minutes <= 10080 ? minutes : null;
    }
    const dateText = (value) => value ? new Date(value).toLocaleString('es-CL', { dateStyle: 'short', timeStyle: 'short' }) : '—';
    function updateSummary() {
        $('sumCaso').textContent = caseSelect.selectedOptions[0]?.textContent.trim() || '—';
        if (!caseSelect.value) $('sumCaso').textContent = '—';
        $('sumNivel').textContent = levelSelect.value || '—';
        $('sumCurso').textContent = courseSelect.value || '—';
        $('sumPeriodo').textContent = $('inicio').value && $('termino').value ? `${dateText($('inicio').value)} — ${dateText($('termino').value)}` : '—';
        if ($('sumDuracion')) $('sumDuracion').textContent = $('duracion').value ? `${$('duracion').value} minutos` : '—';
        $('sumEstado').textContent = state();
    }
    function safeText(value) { return document.createTextNode(String(value ?? '')); }
    function el(tag, cls, text) { const node = document.createElement(tag); if (cls) node.className = cls; if (text !== undefined) node.append(safeText(text)); return node; }
    function render() {
        const query = $('searchAssignments').value.trim().toLocaleLowerCase('es');
        const status = $('filterStatus').value;
        const filtered = assignments.filter(a => (!status || a.estado === status) && `${a.codigo} ${a.casoTexto} ${a.curso}`.toLocaleLowerCase('es').includes(query));
        const body = $('assignmentRows'); body.replaceChildren();
        if (!filtered.length) {
            const tr = el('tr'), td = el('td', 'assignment-empty', assignments.length ? 'No hay resultados para los filtros seleccionados.' : 'No hay asignaciones registradas.');
            td.colSpan = 6; tr.append(td); body.append(tr);
        }
        filtered.forEach(a => {
            const tr = el('tr');
            const c = el('td'); c.append(el('strong', '', a.codigo), el('span', 'assignment-case-subtitle', a.casoTexto.split(' · ').slice(1).join(' · ') || a.casoTexto));
            const course = el('td', '', a.curso);
            const period = el('td', 'assignment-date', dateText(a.inicio)); period.append(el('small', '', `Hasta ${dateText(a.termino)}`));
            const durationCell = el('td', '', a.duracion ? `${a.duracion} min` : 'Sin límite definido');
            const statusCell = el('td'); statusCell.append(el('span', 'assignment-status-badge ' + (a.estado === 'Activa' ? 'is-active' : a.estado === 'Programada' ? 'is-planned' : 'is-inactive'), a.estado));
            const actions = el('td'), wrap = el('div', 'assignment-actions');
            const edit = el('button', 'btn btn-secondary', 'Editar'); edit.type = 'button'; edit.addEventListener('click', () => editAssignment(a.id));
            const remove = el('button', 'btn btn-secondary', 'Eliminar'); remove.type = 'button'; remove.addEventListener('click', () => removeAssignment(a.id));
            wrap.append(edit, remove); actions.append(wrap); tr.append(c, course, period, durationCell, statusCell, actions); body.append(tr);
        });
        $('assignmentCount').textContent = `${filtered.length} asignación(es) mostrada(s)`;
    }
    function resetForm() {
        form.reset(); $('editingId').value = ''; $('formTitle').textContent = 'Nueva asignación'; $('saveAssignment').textContent = 'Guardar asignación';
        $('assignmentMessage').textContent = ''; syncCourses(); syncCaseLevel(); updateSummary();
    }
    function editAssignment(id) {
        const a = assignments.find(item => String(item.id) === String(id)); if (!a) return;
        if (![...caseSelect.options].some(o => o.value === a.codigo)) {
            const option = new Option(a.casoTexto, a.codigo); option.dataset.nivel = a.nivel; option.dataset.duracion = a.duracion || ''; caseSelect.add(option);
        }
        caseSelect.value = a.codigo; syncCaseLevel(); levelSelect.value = a.nivel; syncCourses(a.curso);
        $('inicio').value = a.inicio; $('termino').value = a.termino; syncDuration(); $('duracion').value = a.duracion; $('observaciones').value = a.observaciones || '';
        form.querySelector(`input[name="estado"][value="${a.estado}"]`).checked = true;
        $('editingId').value = id; $('formTitle').textContent = 'Editar asignación'; $('saveAssignment').textContent = 'Guardar cambios';
        $('assignmentMessage').textContent = ''; updateSummary(); form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    // El servidor valida que una asignación con fichas iniciadas no se elimine.
    let pendingDeleteId = null;
    const deleteDialog = window.DocenteUI?.initConfirmModal({
        modalId: 'deleteAssignmentModal',
        cancelId: 'cancelDeleteAssignment',
        confirmId: 'confirmDeleteAssignment',
        onConfirm: async () => {
            if (pendingDeleteId === null) return;
            const id = pendingDeleteId;
            pendingDeleteId = null;
            try {
                await window.DocenteAPI.remove('/docente/asignaciones/' + encodeURIComponent(id));
                assignments = assignments.filter(a => String(a.id) !== String(id));
                if ($('editingId').value === String(id)) resetForm();
                $('assignmentMessage').textContent = 'Asignación eliminada.';
                render();
            } catch (error) { $('assignmentMessage').textContent = error.message; }
        }
    });
    function removeAssignment(id) { pendingDeleteId = id; deleteDialog?.open(); }
    async function load() {
        try {
            const response = await window.DocenteAPI.get('/docente/asignaciones/datos');
            assignments = response.data || [];
            render();
        } catch (error) { $('assignmentMessage').textContent = error.message; }
    }
    caseSelect.addEventListener('change', () => { syncCaseLevel(); updateSummary(); });
    levelSelect.addEventListener('change', () => { syncCourses(); updateSummary(); });
    form.addEventListener('input', updateSummary); form.addEventListener('change', updateSummary);
    // El usuario confirma antes de perder cambios del formulario.
    const resetDialog = window.DocenteUI?.initConfirmModal({
        modalId: 'clearAssignmentModal', cancelId: 'cancelClearAssignment',
        confirmId: 'confirmClearAssignment', onConfirm: resetForm
    });
    $('cancelEdit').addEventListener('click', () => resetDialog ? resetDialog.open() : resetForm());
    $('searchAssignments').addEventListener('input', render);
    $('filterStatus').addEventListener('change', render);
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        if (!form.reportValidity()) return;
        const start = $('inicio').value, end = $('termino').value;
        const duracion = getDuration();
        if (duracion === null) { $('assignmentMessage').textContent = 'Ingresa una duración válida entre 1 y 10080 minutos.'; caseSelect.focus(); return; }
        // Comparar fecha Y hora; se permite el mismo día si la hora de término es posterior.
        const startTime = new Date(start).getTime();
        const endTime = new Date(end).getTime();
        if (!Number.isFinite(startTime) || !Number.isFinite(endTime) || endTime <= startTime) {
            $('assignmentMessage').textContent = 'El fin de disponibilidad debe ser posterior al inicio (pueden ser el mismo día, con distinta hora).';
            $('termino').focus();
            return;
        }
        const level = caseSelect.selectedOptions[0]?.dataset.nivel;
        if (level && level !== 'Ambos niveles' && level !== levelSelect.value) { $('assignmentMessage').textContent = 'El nivel seleccionado no corresponde al caso clínico.'; return; }
        const editingId = $('editingId').value;
        const duplicate = assignments.some(a => String(a.id) !== String(editingId) && a.codigo === caseSelect.value && (a.curso === courseSelect.value || a.curso === 'Todos los cursos' || courseSelect.value === 'Todos los cursos') && a.nivel === levelSelect.value && new Date(start) < new Date(a.termino) && new Date(end) > new Date(a.inicio));
        if (duplicate) { $('assignmentMessage').textContent = 'Ya existe una asignación de este caso para el curso durante ese periodo.'; return; }
        const payload = {
            codigo: caseSelect.value, nivel: levelSelect.value, curso: courseSelect.value,
            inicio: start, termino: end, duracion, estado: state(), observaciones: $('observaciones').value.trim()
        };
        const button = $('saveAssignment'); button.disabled = true;
        try {
            const url = editingId ? '/docente/asignaciones/' + encodeURIComponent(editingId) : '/docente/asignaciones';
            await window.DocenteAPI.send(url, editingId ? 'PUT' : 'POST', payload);
            await load(); resetForm();
            $('assignmentMessage').textContent = editingId ? 'Asignación actualizada.' : 'Asignación guardada en la base de datos.';
        } catch (error) { $('assignmentMessage').textContent = error.message; }
        finally { button.disabled = false; }

    });
    syncCourses(); syncCaseLevel(); updateSummary(); load();
})();
