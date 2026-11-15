/* Revisión docente unificada de fichas reales de 3.º y 4.º medio. */
document.addEventListener('DOMContentLoaded', async () => {
    'use strict';
    window.DocenteUI?.initSidebar();
    const $ = id => document.getElementById(id);
    const labelsThird = {'antecedentes-3ro':'Antecedentes del paciente', 'signos-vitales-3ro':'Signos vitales', 'examen-fisico-3ro':'Examen físico', 'citaciones-3ro':'Citaciones / controles'};
    const labels = {
        admision: 'Admisión', triage: 'Triage', 'signos-vitales': 'Signos vitales', abcde: 'Evaluación ABCDE',
        anamnesis: 'Anamnesis', 'examen-fisico': 'Examen físico', procedimientos: 'Procedimientos',
        medicamentos_4to: 'Medicamentos', examenes: 'Exámenes', 'acciones-enfermeria': 'Acciones de enfermería', destino: 'Destino del paciente'
    };
    let fichas = [];
    let active = null;
    const dateText = value => value ? new Date(value).toLocaleString('es-CL') : '—';
    // Renderizador legible de registros reales. Los datos se agregan como texto seguro,
    // nunca se interpreta el JSON del estudiante como HTML.
    const fieldName = key => {
        const names = {pas:'Presión sistólica',pad:'Presión diastólica',fc:'Frecuencia cardíaca',fr:'Frecuencia respiratoria',
            spo2:'Saturación de oxígeno (SpO₂)',temperatura:'Temperatura',imc:'IMC',eva:'Escala EVA',
            motivo_consulta:'Motivo de consulta',interpretacion_clinica:'Interpretación clínica',
            fecha_hora:'Fecha y hora',tipo_control:'Tipo de control',educacion_confirmada:'Educación confirmada',
            profesional_asignado:'Profesional asignado',tiene_patologias:'Patologías crónicas',
            tiene_alergias:'Alergias',tiene_medicamentos:'Medicamentos habituales',
            patologias:'Patologías',medicamentos:'Medicamentos',alergias:'Alergias',
            torax_abdomen:'Tórax y abdomen',cabeza_cuello:'Cabeza y cuello',extremidades_piel:'Extremidades y piel',
            eva_habilitada:'Registro EVA activo',datos:'Datos del formulario'};
        return names[key] || key.replace(/[_-]+/g,' ').replace(/\b\w/g,x=>x.toUpperCase());
    };
    const valueText = v => {
        if (v === null || v === undefined || v === '') return 'Sin registrar';
        if (typeof v === 'boolean') return v ? 'Sí' : 'No';
        if (typeof v === 'number') return String(v);
        if (typeof v === 'string') {
            if (/^data:[^;]+;base64,/.test(v)) return 'Archivo adjunto guardado';
            return v.length > 3000 ? v.slice(0,3000)+'…' : v;
        }
        return String(v);
    };
    const make = (tag,cls,txt) => {const el=document.createElement(tag);if(cls)el.className=cls;if(txt!==undefined)el.textContent=txt;return el;};
    function renderStructured(raw) { return window.FichaRecordView.render(raw); }
    function cell(tr, value) { const td = document.createElement('td'); td.textContent = String(value ?? '—'); tr.append(td); return td; }
    function updateCourses() {
        const selected = $('reviewCourse').value;
        const level = $('reviewLevel').value;
        const known = level === '3.º medio' ? ['3.º Medio A','3.º Medio B','3.º Medio C']
            : level === '4.º medio' ? ['4.º Medio A','4.º Medio B','4.º Medio C','4.º Medio D']
            : ['3.º Medio A','3.º Medio B','3.º Medio C','4.º Medio A','4.º Medio B','4.º Medio C','4.º Medio D'];
        const courses = [...new Set([...known, ...fichas.filter(f => !level || f.nivel === level).map(f => f.curso).filter(Boolean)])].sort();
        $('reviewCourse').replaceChildren(new Option('Todos los cursos', ''));
        courses.forEach(c => $('reviewCourse').add(new Option(c,c)));
        if (courses.includes(selected)) $('reviewCourse').value = selected;
    }
    function render() {
        const query = $('reviewSearch').value.toLocaleLowerCase('es').trim();
        const level = $('reviewLevel').value, course = $('reviewCourse').value, status = $('reviewStatus').value;
        const list = fichas.filter(f =>
            (!query || `${f.estudiante} ${f.codigo} ${f.caso}`.toLocaleLowerCase('es').includes(query)) &&
            (!level || f.nivel === level) && (!course || f.curso === course) && (!status || f.estado === status));
        $('metricTotal').textContent = fichas.length;
        $('metricProgress').textContent = fichas.filter(f => f.estado === 'En desarrollo').length;
        $('metricDelivered').textContent = fichas.filter(f => f.estado === 'Entregada').length;
        $('metricExpired').textContent = fichas.filter(f => f.estado === 'Tiempo agotado').length;
        const tbody = $('reviewRows'); tbody.replaceChildren(); $('reviewEmpty').hidden = !!list.length;
        list.forEach(f => {
            const row = document.createElement('tr');
            cell(row,f.estudiante).append(document.createElement('br'), document.createTextNode('#' + f.id));
            cell(row, f.codigo).append(document.createElement('br'), document.createTextNode(f.caso));
            cell(row, f.curso).append(document.createElement('br'), document.createTextNode(f.nivel));
            cell(row,f.estado); cell(row,dateText(f.ultimoGuardado));
            const count = Object.keys(f.secciones || {}).length;
            const total = Object.keys(f.nivel === '3.º medio' ? labelsThird : labels).length;
            cell(row, `${Math.min(100, Math.round(100 * count / total))}%`);
            const actions = cell(row,''); actions.textContent = '';
            const button = document.createElement('button'); button.type = 'button'; button.className = 'btn btn-secondary btn-small'; button.textContent = 'Ver ficha';
            button.addEventListener('click', () => open(f.id)); actions.append(button); tbody.append(row);
        });
    }
    function open(id) {
        const f = fichas.find(r => String(r.id) === String(id)); if (!f) return;
        active = f;
        $('reviewDetailTitle').textContent = `Ficha de ${f.estudiante}`;
        $('reviewDetailSubtitle').textContent = `${f.codigo} · ${f.caso} · ${f.curso}`;
        $('detailStatus').textContent = f.estado;
        $('detailSaved').textContent = dateText(f.ultimoGuardado);
        const sectionLabels = f.nivel === '3.º medio' ? labelsThird : labels;
        $('detailSections').textContent = `${Object.keys(f.secciones || {}).length} de ${Object.keys(sectionLabels).length}`;
        $('detailDuration').textContent = `${f.duracion} minutos`;
        const box = $('reviewSections'); box.replaceChildren();
        Object.entries(sectionLabels).forEach(([key, title], index) => {
            const saved = f.secciones?.[key];
            const wrap = document.createElement('div'); wrap.className = 'review-section';
            const button = document.createElement('button'); button.type = 'button'; button.className = 'review-section-trigger';
            const text = document.createElement('span'); text.textContent = title;
            const badge = document.createElement('span'); badge.className = `pill ${saved ? 'is-delivered' : ''}`; badge.textContent = saved ? 'Con registro' : 'Sin registro';
            button.append(text, badge); button.setAttribute('aria-expanded', String(index === 0));
            const content = document.createElement('div'); content.className = 'review-section-content review-content-formatted'; content.hidden = index !== 0;
            if (saved) content.append(renderStructured(saved));
            else content.append(make('p','review-muted','No se ha guardado esta sección.'));
            button.addEventListener('click', () => { content.hidden = !content.hidden; button.setAttribute('aria-expanded', String(!content.hidden)); });
            wrap.append(button,content); box.append(wrap);
        });
        const eligible = f.estado === 'Entregada' || f.estado === 'Tiempo agotado';
        $('reviewNotice').textContent = eligible ? f.nivel === '3.º medio' ? 'Esta ficha está cerrada y puede evaluarse manualmente con la pauta CESFAM.' : 'Esta ficha está cerrada y puede evaluarse con la pauta de Urgencias.' : 'Esta ficha continúa abierta. Puedes revisarla, pero no calificarla definitivamente.';
        const downloadable=f.evaluacion?.estado === 'finalizada';
        $('reviewDownloadPDF').hidden=!downloadable;
        $('reviewDownloadPDF').href=downloadable?`/evaluaciones/${encodeURIComponent(f.id)}/pdf`:'#';
        $('reviewEvaluate').disabled = !eligible;
        $('reviewEvaluate').onclick = eligible ? () => window.location.assign('/docente/evaluaciones?ficha=' + encodeURIComponent(f.id)) : null;
        $('reviewDetails').hidden = false; $('reviewDetails').scrollIntoView({behavior:'smooth',block:'start'});
    }
    ['reviewSearch','reviewStatus','reviewCourse'].forEach(id => $(id).addEventListener(id === 'reviewSearch' ? 'input' : 'change', render));
    $('reviewLevel').addEventListener('change', () => { updateCourses(); render(); });
    $('reviewClear')?.addEventListener('click', () => { ['reviewSearch','reviewLevel','reviewCourse','reviewStatus'].forEach(id => $(id).value = ''); updateCourses(); render(); });
    $('closeReview').addEventListener('click', () => { $('reviewDetails').hidden = true; active = null; });
    try {
        const response = await window.DocenteAPI.get('/docente/fichas/datos');
        fichas = response.data || []; updateCourses(); render();
        const requested = new URLSearchParams(location.search).get('ficha');
        if (requested && fichas.some(f=>String(f.id)===requested)) open(requested);
    } catch (error) { $('reviewEmpty').hidden = false; $('reviewEmpty').textContent = 'No se pudieron cargar las fichas: ' + error.message; }
});
