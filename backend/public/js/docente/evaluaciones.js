
/* Evaluación real: 9 criterios según pauta de Urgencias del liceo. La nota se calcula en servidor. */
document.addEventListener('DOMContentLoaded', async () => {
    'use strict';

    window.DocenteUI?.initSidebar();

    const $ = id => document.getElementById(id);

    const CRITERIA = [
        ['Admisión', 'Datos del paciente, previsión, contacto y medicamentos habituales.'],
        ['Triage', 'Motivo, nivel ESI y escala de dolor EVA.'],
        ['Evaluación ABCDE', 'Evaluación primaria completa (A, B, C, D y E).'],
        ['Anamnesis y examen físico', 'Redacción de la enfermedad actual y examen físico correspondiente.'],
        ['Procedimientos TENS', 'Procedimiento, hora y responsable.'],
        ['Administración de medicamentos', 'Fármaco, dosis, vía, hora y reacciones observadas.'],
        ['Revisión de exámenes', 'Documentos/imágenes adjuntos y fecha y hora.'],
        ['Acciones de enfermería', 'Seguimiento cronológico del paciente.'],
        ['Destino y cierre', 'Destino, justificación y finalización dentro del tiempo.']
    ];

    const THIRD_CRITERIA = [
        ['Registro y antecedentes', 'Motivo de consulta y antecedentes del paciente.'],
        ['Signos vitales y antropometría', 'PA, FC, FR, temperatura, saturación, peso, talla e IMC.'],
        ['Interpretación de signos', 'Pertinencia de la interpretación clínica.'],
        ['Examen físico general', 'Calidad de la valoración general y segmentaria.'],
        ['Prestaciones y acciones TENS', 'Pertinencia de intervenciones y acciones.'],
        ['Educación al paciente', 'Calidad de la educación y sus indicaciones.'],
        ['Confirmación de educación', 'Casilla de confirmación de educación.'],
        ['Citaciones y controles', 'Fecha, tipo de control, periodicidad, profesional e indicaciones.'],
        ['Cierre y cumplimiento', 'Cuatro secciones guardadas y entrega dentro del plazo.']
    ];

    const THIRD_EVIDENCE = [
        ['antecedentes-3ro'],
        ['signos-vitales-3ro'],
        ['signos-vitales-3ro'],
        ['examen-fisico-3ro'],
        ['examen-fisico-3ro'],
        ['citaciones-3ro'],
        ['citaciones-3ro'],
        ['citaciones-3ro'],
        ['antecedentes-3ro', 'signos-vitales-3ro', 'examen-fisico-3ro', 'citaciones-3ro']
    ];

    const evidenceSections = [
        ['admision'],
        ['triage'],
        ['abcde'],
        ['anamnesis', 'examen-fisico'],
        ['procedimientos'],
        ['medicamentos_4to'],
        ['examenes'],
        ['acciones-enfermeria'],
        ['destino']
    ];

    let records = [];
    let active = null;
    let answers = [];
    let notes = [];
    let modal = null;

    // Registros legibles y seguros: ningún dato del estudiante se ejecuta como HTML.
    const fieldName = key => {
        const names = {
            pas: 'Presión sistólica',
            pad: 'Presión diastólica',
            fc: 'Frecuencia cardíaca',
            fr: 'Frecuencia respiratoria',
            spo2: 'Saturación de oxígeno (SpO₂)',
            temperatura: 'Temperatura',
            imc: 'IMC',
            eva: 'Escala EVA',
            motivo_consulta: 'Motivo de consulta',
            interpretacion: 'Interpretación',
            interpretacion_clinica: 'Interpretación clínica',
            fecha_hora: 'Fecha y hora',
            tipo_control: 'Tipo de control',
            educacion_confirmada: 'Educación confirmada',
            profesional_asignado: 'Profesional asignado',
            tiene_patologias: 'Patologías crónicas',
            tiene_alergias: 'Alergias',
            tiene_medicamentos: 'Medicamentos habituales',
            patologias: 'Patologías',
            medicamentos: 'Medicamentos',
            alergias: 'Alergias',
            torax_abdomen: 'Tórax y abdomen',
            cabeza_cuello: 'Cabeza y cuello',
            extremidades_piel: 'Extremidades y piel',
            estado_conciencia: 'Estado de conciencia',
            estado_general: 'Estado general',
            hidratacion: 'Hidratación',
            nutricion: 'Nutrición',
            marcha: 'Marcha',
            facie: 'Facie',
            eva_habilitada: 'Registro EVA activo',
            observaciones: 'Observaciones',
            fecha_citacion: 'Fecha de citación',
            motivo: 'Motivo',
            datos: 'Datos del formulario'
        };

        return names[key] || String(key).replace(/[_-]+/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
    };

    const formatValue = value => {
        if (value === null || value === undefined || value === '') return 'Sin registrar';
        if (typeof value === 'boolean') return value ? 'Sí' : 'No';
        if (typeof value === 'string' && /^data:[^;]+;base64,/.test(value)) return 'Archivo adjunto guardado';
        if (typeof value === 'string' && value.length > 3000) return value.slice(0, 3000) + '…';
        return String(value);
    };

    const make = (tag, cls = '', content) => {
        const el = document.createElement(tag);
        if (cls) el.className = cls;
        if (content !== undefined) el.textContent = String(content);
        return el;
    };

    function readableRecord(raw) {
        return window.FichaRecordView.render(raw);
    }

    const SECTION_LABELS = {
        'antecedentes-3ro': 'Antecedentes del paciente',
        'signos-vitales-3ro': 'Signos vitales',
        'examen-fisico-3ro': 'Examen físico',
        'citaciones-3ro': 'Citaciones y controles',
        admision: 'Admisión',
        triage: 'Triage',
        abcde: 'Evaluación ABCDE',
        anamnesis: 'Anamnesis',
        'examen-fisico': 'Examen físico',
        procedimientos: 'Procedimientos',
        medicamentos_4to: 'Medicamentos',
        examenes: 'Exámenes',
        'acciones-enfermeria': 'Acciones de enfermería',
        destino: 'Destino y cierre'
    };

    function renderEvidence(sections, keys) {
        const details = make('div', 'eval-evidence');
        details.hidden = true;

        keys.forEach(key => {
            const group = make('section', 'eval-evidence-section');
            group.append(make('h4', '', SECTION_LABELS[key] || fieldName(key)));
            group.append(readableRecord(sections?.[key]));
            details.append(group);
        });

        return details;
    }

    const textCell = (tr, text) => {
        const td = document.createElement('td');
        td.textContent = String(text ?? '—');
        tr.append(td);
        return td;
    };

    const eligible = f => ['Entregada', 'Tiempo agotado'].includes(f.estado);
    const status = f => f.evaluacion?.estado || 'pendiente';

    function score() {
        const yes = answers.filter(a => a === 'si').length;
        const pending = answers.filter(a => !['si', 'no'].includes(a)).length;

        // Mismo cálculo que app/Support/UrgencyRubric.php.
        const p = Math.round((20 + 80 * yes / 9) * 100) / 100;
        const grade = p < 60
            ? 2 + ((p - 20) / 40) * 2
            : 4 + ((p - 60) / 40) * 3;

        return {
            yes,
            pending,
            percentage: p,
            grade: Math.min(7, grade)
        };
    }

    const gradeText = value => Number(value).toFixed(1).replace('.', ',');

    function summary() {
        const result = score();

        // Las evaluaciones finalizadas antes del cambio conservan la escala almacenada.
        const stored = active?.evaluacion?.estado === 'finalizada'
            ? active.evaluacion
            : null;

        const percentage = stored && stored.porcentaje != null
            ? Number(stored.porcentaje)
            : result.percentage;

        const grade = stored && stored.nota != null
            ? Number(stored.nota)
            : result.grade;

        $('evalCompleted').textContent = `${result.yes} / 9`;
        $('evalPercent').textContent = `${percentage.toFixed(1).replace('.', ',')}%${result.pending ? ' *' : ''}`;
        $('evalGrade').textContent = `${gradeText(grade)}${result.pending ? ' *' : ''}`;

        $('evalResult').textContent = result.pending
            ? 'Pendiente de revisión'
            : percentage >= 60
                ? 'Aprobado'
                : 'Reprobado';

        $('evalResult').className = result.pending
            ? 'eval-summary-pending'
            : percentage >= 60
                ? 'eval-summary-approved'
                : 'eval-summary-failed';

        $('evalProgressBar').style.width = percentage + '%';

        $('evalAnswered').textContent = result.pending
            ? `${result.pending} criterio(s) pendientes. Nota provisional (*).`
            : 'Todos los criterios revisados. La escala se aplica en servidor al guardar.';
    }

    function updateCourses() {
        const selected = $('evalCourse').value;

        const options = [...new Set(records.filter(r =>
            (! $('evalLevel').value || r.nivel === $('evalLevel').value) &&
            (($('evalJornada').value === 'vespertino') === /^V(?:3ro|4to)$/i.test(r.curso || ''))
        ).map(r => r.curso).filter(Boolean))].sort();

        $('evalCourse').replaceChildren(new Option('Todos los cursos', ''));

        options.forEach(v => $('evalCourse').add(new Option(v, v)));

        if (options.includes(selected)) {
            $('evalCourse').value = selected;
        }
    }

    function renderList() {
        const query = $('evalSearch').value.trim().toLocaleLowerCase('es');
        const course = $('evalCourse').value;
        const level = $('evalLevel').value;
        const service = $('evalService').value;
        const s = $('evalStatus').value;

        const visible = records.filter(f =>
            (($('evalJornada').value === 'vespertino') === /^V(?:3ro|4to)$/i.test(f.curso || '')) &&
            (!query || `${f.estudiante} ${f.codigo} ${f.caso}`.toLocaleLowerCase('es').includes(query)) &&
            (!course || f.curso === course) &&
            (!level || f.nivel === level) &&
            (!service || f.servicio === service) &&
            (!s || status(f) === s)
        );

        const porJornada = records.filter(f =>
            (($('evalJornada').value === 'vespertino') === /^V(?:3ro|4to)$/i.test(f.curso || ''))
        );

        $('statTotal').textContent = porJornada.length;
        $('statPending').textContent = porJornada.filter(f => status(f) === 'pendiente').length;
        $('statDraft').textContent = porJornada.filter(f => status(f) === 'borrador').length;
        $('statFinished').textContent = porJornada.filter(f => status(f) === 'finalizada').length;

        const tbody = $('evalRows');
        tbody.replaceChildren();

        visible.forEach(f => {
            const tr = document.createElement('tr');

            [f.estudiante, f.curso, `${f.codigo} · ${f.caso}`, f.servicio === 'cesfam' ? 'CESFAM' : 'Urgencias']
                .forEach(v => textCell(tr, v));

            const stateCell = textCell(tr, '');
            stateCell.append(make(
                'span',
                'eval-list-status eval-status-' + status(f),
                status(f) === 'finalizada'
                    ? 'Finalizada'
                    : status(f) === 'borrador'
                        ? 'Borrador'
                        : 'Pendiente'
            ));

            const resultCell = textCell(tr, '');
            const finished = status(f) === 'finalizada';
            const passed = Number(f.evaluacion?.porcentaje) >= 60;

            const resultText = finished
                ? `${f.evaluacion.porcentaje == null ? 'Sin porcentaje' : f.evaluacion.porcentaje + '%'} · ${f.evaluacion.nota == null ? 'Sin nota' : gradeText(f.evaluacion.nota)}`
                : 'Pendiente';

            resultCell.append(make(
                'span',
                'eval-list-result ' + (finished
                    ? (passed ? 'eval-list-approved' : 'eval-list-failed')
                    : 'eval-list-pending'),
                resultText
            ));

            const action = textCell(tr, '');
            action.textContent = '';

            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'btn btn-secondary btn-small';
            button.textContent = status(f) === 'finalizada' ? 'Ver evaluación' : 'Evaluar ficha';
            button.disabled = !eligible(f);
            button.title = button.disabled ? 'La ficha todavía no está cerrada.' : '';
            button.addEventListener('click', () => open(f.id));

            action.append(button);

            if (finished) {
                const pdf = make('a', 'btn btn-secondary btn-small eval-pdf-link', 'Descargar PDF');
                pdf.href = `/evaluaciones/${encodeURIComponent(f.id)}/pdf`;
                pdf.setAttribute('download', '');
                action.append(pdf);
            }

            tbody.append(tr);
        });

        if (!visible.length) {
            const tr = document.createElement('tr');
            const td = textCell(tr, 'No hay fichas que coincidan con los filtros.');
            td.colSpan = 7;
            tbody.append(tr);
        }

        $('evalCount').textContent = `${visible.length} de ${records.length} fichas visibles`;
    }

    function open(id) {
        active = records.find(r => String(r.id) === String(id));

        if (!active || !eligible(active)) return;

        const evaluation = active.evaluacion || {};

        $('evalEditor').hidden = true;
        $('evalThirdEditor').hidden = true;

        if (
            active.nivel === '3.º medio' &&
            !Array.isArray(evaluation.criterios) &&
            evaluation.estado === 'finalizada'
        ) {
            openThird(active);
            return;
        }

        const third = active.nivel === '3.º medio';

        const criteriaList =
            Array.isArray(evaluation.rubrica_criterios) && evaluation.rubrica_criterios.length === 9
                ? evaluation.rubrica_criterios
                : (Array.isArray(active.pauta) && active.pauta.length === 9
                    ? active.pauta
                    : (third ? THIRD_CRITERIA : CRITERIA));

        const sectionsList = third ? THIRD_EVIDENCE : evidenceSections;
        const suggested = active.evaluacionSugerida || {};

        const suggestedAnswers = Array.isArray(suggested.criterios)
            ? suggested.criterios
            : Array(9).fill('pendiente');

        answers = Array.isArray(evaluation.criterios)
            ? [...evaluation.criterios]
            : [...suggestedAnswers];

        notes = Array.isArray(evaluation.notas)
            ? [...evaluation.notas]
            : Array(9).fill('');

        const locked = evaluation.estado === 'finalizada';

        $('evalPdf').hidden = !locked;
        $('evalPdf').href = locked ? `/evaluaciones/${encodeURIComponent(active.id)}/pdf` : '#';
        $('evalThirdPdf').hidden = true;

        $('evalHeading').textContent = third
            ? 'Pauta de cotejo · Atención primaria (CESFAM)'
            : 'Pauta de cotejo · Servicio de Urgencias';

        $('evalContext').textContent = `${active.estudiante} · ${active.curso} · ${active.codigo} · ${active.caso}`;

        const tbody = $('evalCriteria');
        tbody.replaceChildren();

        criteriaList.forEach(([heading, description], i) => {
            const row = document.createElement('tr');

            textCell(row, i + 1);

            const title = textCell(row, '');
            title.textContent = '';

            const strong = document.createElement('strong');
            strong.textContent = heading;

            const desc = document.createElement('p');
            desc.textContent = description;
            desc.className = 'eval-criterion-description';

            title.append(strong, desc);

            if (suggested.origen?.[i] === 'automatica') {
                const meta = document.createElement('p');
                meta.className = 'eval-criterion-description';
                meta.textContent = 'Comprobación automática de datos: ' + (suggested.motivos?.[i] || 'Presencia del registro');
                title.append(meta);
            } else {
                const meta = document.createElement('p');
                meta.className = 'eval-criterion-description';
                meta.textContent = 'Revisión clínica requerida por el docente.';
                title.append(meta);
            }

            const result = textCell(row, '');
            result.textContent = '';

            const badge = document.createElement('span');
            badge.className = 'eval-result';
            result.append(badge);

            const controls = textCell(row, '');
            controls.textContent = '';

            const view = document.createElement('button');
            view.type = 'button';
            view.className = 'btn btn-secondary btn-small';
            view.textContent = 'Ver registro del estudiante';

            // La evidencia se despliega en su propia fila, ocupando las cuatro columnas.
            // Así no queda comprimida en el espacio destinado a botones y comentarios.
            const details = renderEvidence(active.secciones, sectionsList[i]);
            const detailId = `evalRecord-${active.id}-${i}`;
            details.id = detailId;

            const evidenceRow = document.createElement('tr');
            evidenceRow.className = 'eval-evidence-row';
            evidenceRow.hidden = true;

            const evidenceCell = document.createElement('td');
            evidenceCell.colSpan = 4;
            evidenceCell.append(details);
            evidenceRow.append(evidenceCell);

            view.setAttribute('aria-controls', detailId);
            view.setAttribute('aria-expanded', 'false');

            view.addEventListener('click', () => {
                const opening = evidenceRow.hidden;

                evidenceRow.hidden = !opening;
                details.hidden = !opening;

                view.setAttribute('aria-expanded', String(opening));
                view.textContent = opening
                    ? 'Ocultar registro del estudiante'
                    : 'Ver registro del estudiante';
            });

            const select = document.createElement('select');
            select.className = 'eval-decision';
            select.disabled = locked;
            select.setAttribute('aria-label', `Resultado de ${heading}`);

            [['pendiente', 'Pendiente'], ['si', 'Sí cumple'], ['no', 'No cumple']]
                .forEach(([v, label]) => select.add(new Option(label, v)));

            select.value = answers[i] || 'pendiente';

            const note = document.createElement('textarea');
            note.className = 'eval-note';
            note.rows = 2;
            note.placeholder = 'Observación docente (opcional)';
            note.value = notes[i] || '';
            note.disabled = locked;

            const update = () => {
                answers[i] = select.value;
                badge.textContent = answers[i] === 'si'
                    ? 'Cumple'
                    : answers[i] === 'no'
                        ? 'No cumple'
                        : 'Pendiente';

                badge.className = 'eval-result eval-' + answers[i];
                summary();
            };

            select.addEventListener('change', update);
            note.addEventListener('input', () => notes[i] = note.value);

            controls.append(view, select, note);
            tbody.append(row, evidenceRow);

            update();
        });

        $('evalFeedback').value = evaluation.retroalimentacion || '';
        $('evalFeedback').disabled = locked;

        $('evalSave').hidden = locked;
        $('evalFinish').hidden = locked;

        $('evalMessage').textContent = locked
            ? 'Evaluación finalizada. Solo lectura.'
            : 'Se completaron sugerencias automáticas para los campos verificables. Revisa los criterios clínicos pendientes y confirma la pauta.';

        $('evalEditor').hidden = false;
        summary();
        $('evalEditor').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function openThird(f) {
        const e = f.evaluacion || {};
        const locked = e.estado === 'finalizada';

        $('evalThirdPdf').hidden = !locked;
        $('evalThirdPdf').href = locked ? `/evaluaciones/${encodeURIComponent(f.id)}/pdf` : '#';
        $('evalPdf').hidden = true;

        $('evalThirdContext').textContent = `${f.estudiante} · ${f.curso} · ${f.codigo} · ${f.caso}`;

        $('evalThirdGrade').value = e.nota ?? '';
        $('evalThirdPercent').value = e.porcentaje ?? '';
        $('evalThirdFeedback').value = e.retroalimentacion ?? '';

        for (const id of ['evalThirdGrade', 'evalThirdPercent', 'evalThirdFeedback']) {
            $(id).disabled = locked;
        }

        $('evalThirdSave').hidden = locked;
        $('evalThirdFinish').hidden = locked;

        $('evalThirdMessage').textContent = locked
            ? 'Evaluación finalizada. Solo lectura.'
            : 'La evaluación CESFAM es manual. Puedes guardar un borrador.';

        $('evalThirdEditor').hidden = false;
        $('evalThirdEditor').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    async function saveThird(finalizar) {
        if (!active || active.nivel !== '3.º medio' || active.evaluacion?.estado === 'finalizada') return;

        const feedback = $('evalThirdFeedback').value.trim();

        if (!feedback) {
            $('evalThirdMessage').textContent = 'Debes escribir una retroalimentación docente.';
            return;
        }

        const grade = $('evalThirdGrade').value;
        const percent = $('evalThirdPercent').value;

        if (grade !== '' && (Number(grade) < 2 || Number(grade) > 7)) {
            $('evalThirdMessage').textContent = 'La nota debe estar entre 2,0 y 7,0.';
            return;
        }

        if (percent !== '' && (Number(percent) < 20 || Number(percent) > 100)) {
            $('evalThirdMessage').textContent = 'El porcentaje debe estar entre 20 y 100.';
            return;
        }

        for (const id of ['evalThirdSave', 'evalThirdFinish']) {
            $(id).disabled = true;
        }

        try {
            const response = await window.DocenteAPI.send(
                `/docente/fichas/${encodeURIComponent(active.id)}/evaluacion`,
                'POST',
                {
                    retroalimentacion: feedback,
                    nota: grade === '' ? null : Number(grade),
                    porcentaje: percent === '' ? null : Number(percent),
                    finalizar
                }
            );

            active.evaluacion = response.data;
            renderList();
            openThird(active);

            $('evalThirdMessage').textContent = response.message || 'Evaluación guardada.';
        } catch (error) {
            $('evalThirdMessage').textContent = error.message;
        } finally {
            for (const id of ['evalThirdSave', 'evalThirdFinish']) {
                $(id).disabled = false;
            }
        }
    }

    async function save(finalizar) {
        if (!active || active.evaluacion?.estado === 'finalizada') return;

        if (finalizar && score().pending) {
            $('evalMessage').textContent = 'Debes revisar los nueve criterios antes de finalizar.';
            return;
        }

        const buttons = [$('evalSave'), $('evalFinish')];
        buttons.forEach(b => b.disabled = true);

        try {
            const response = await window.DocenteAPI.send(
                `/docente/fichas/${encodeURIComponent(active.id)}/evaluacion`,
                'POST',
                {
                    criterios: answers,
                    notas: notes,
                    retroalimentacion: $('evalFeedback').value,
                    finalizar
                }
            );

            active.evaluacion = response.data;
            renderList();

            if (finalizar) {
                open(active.id);
            } else {
                $('evalMessage').textContent = 'Borrador de evaluación guardado correctamente.';
            }
        } catch (error) {
            $('evalMessage').textContent = error.message;
        } finally {
            buttons.forEach(b => b.disabled = false);
        }
    }

    ['evalSearch', 'evalCourse', 'evalService', 'evalStatus'].forEach(id => {
        $(id).addEventListener(id === 'evalSearch' ? 'input' : 'change', renderList);
    });

    $('evalLevel').addEventListener('change', () => {
        updateCourses();
        renderList();
    });

    $('evalJornada').addEventListener('change', () => {
        updateCourses();
        renderList();
    });

    $('evalClear').addEventListener('click', () => {
        ['evalSearch', 'evalLevel', 'evalCourse', 'evalService', 'evalStatus']
            .forEach(id => $(id).value = '');

        updateCourses();
        renderList();
    });

    $('evalBack').addEventListener('click', () => {
        $('evalEditor').hidden = true;
        active = null;
    });

    $('evalThirdBack').addEventListener('click', () => {
        $('evalThirdEditor').hidden = true;
        active = null;
    });

    $('evalThirdSave').addEventListener('click', () => saveThird(false));
    $('evalThirdFinish').addEventListener('click', () => saveThird(true));
    $('evalSave').addEventListener('click', () => save(false));

    $('evalFinish').addEventListener('click', () => {
        if (score().pending) {
            $('evalMessage').textContent = 'Hay criterios pendientes.';
            return;
        }

        modal?.open();
    });

    modal = window.DocenteUI?.initConfirmModal({
        modalId: 'evalConfirmModal',
        cancelId: 'evalCancelConfirm',
        confirmId: 'evalAcceptConfirm',
        onConfirm: () => save(true)
    });

    try {
        const response = await window.DocenteAPI.get('/docente/fichas/datos');
        records = response.data || [];

        if (new URLSearchParams(location.search).get('nivel') === '3') {
            $('evalLevel').value = '3.º medio';
        }

        updateCourses();
        renderList();

        const id = new URLSearchParams(location.search).get('ficha');

        if (id) {
            open(id);
        }
    } catch (error) {
        $('evalCount').textContent = 'Error al cargar evaluaciones: ' + error.message;
    }
});
