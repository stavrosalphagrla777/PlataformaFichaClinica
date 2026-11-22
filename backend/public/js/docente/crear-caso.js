
/* Vista docente: creación de casos clínicos. JavaScript independiente. */

document.addEventListener('DOMContentLoaded', () => {
    const app = document.getElementById('app');
    const toggle = document.getElementById('menuToggle');
    const form = document.getElementById('caseForm');

    if (!form) return;

    // Menú lateral compartido.
    window.DocenteUI?.initSidebar();

    // =====================================================
    // EDITORES DE TEXTO ENRIQUECIDO (QUILL)
    // =====================================================

    const editors = {};

    ['descripcion', 'antecedentes'].forEach(id => {
        const element = document.getElementById(`editor-${id}`);
        const value = document.getElementById(id);

        if (!element || !value) return;

        if (typeof Quill === 'undefined') {
            element.hidden = true;
            value.classList.add('editor-fallback');
            value.placeholder = element.dataset.placeholder || '';
            return;
        }

        const quill = new Quill(element, {
            theme: 'snow',
            placeholder: element.dataset.placeholder || '',
            modules: {
                toolbar: [
                    [{ header: [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    [{ align: [] }],
                    ['blockquote', 'link'],
                    ['clean']
                ]
            }
        });

        editors[id] = quill;

        quill.on('text-change', () => {
            value.value = quill.getText().trim()
                ? quill.getSemanticHTML()
                : '';

            value.dispatchEvent(
                new Event('input', { bubbles: true })
            );
        });
    });

    // =====================================================
    // RESUMEN DINÁMICO DEL CASO
    // =====================================================

    function summary() {
        [
            ['titulo', 'sumTitulo'],
            ['nivel', 'sumNivel'],
            ['tipo', 'sumTipo'],
            ['estado', 'sumEstado'],
            ['servicio', 'sumServicio']
        ].forEach(([a, b]) => {
            const source = document.getElementById(a);
            const target = document.getElementById(b);

            if (source && target) {
                target.textContent = source.value || '—';
            }
        });

        // Duración máxima del caso
        const duracion = document.getElementById('duracion');
        const sumDuracion = document.getElementById('sumDuracion');

        if (duracion && sumDuracion) {
            const minutos = Number(duracion.value);

            sumDuracion.textContent =
                duracion.value.trim() !== '' &&
                    Number.isInteger(minutos) &&
                    minutos >= 1 &&
                    minutos <= 10080
                    ? `${minutos} minutos`
                    : 'Sin definir';
        }

        // Mostrar prioridad ESI solo para urgencias
        const esi = document.getElementById('esiField');
        const servicio = document.getElementById('servicio');

        if (esi && servicio) {
            esi.hidden =
                servicio.value !== 'Servicio de urgencias';
        }
    }

    // Actualizar resumen al modificar campos
    form.addEventListener('input', summary);
    form.addEventListener('change', summary);

    // =====================================================
    // MODAL DE CONFIRMACIÓN - RESTABLECER
    // =====================================================

    const resetDialog = window.DocenteUI?.initConfirmModal({
        modalId: 'resetModal', cancelId: 'cancelReset', confirmId: 'confirmReset',
        onConfirm: () => form.reset()
    });
    document.getElementById('resetButton')?.addEventListener('click', () => resetDialog?.open());

    // =====================================================
    // RESTABLECER FORMULARIO
    // =====================================================

    form.addEventListener('reset', () => {
        const message = document.getElementById('formMessage');

        if (message) {
            message.textContent = '';
        }

        requestAnimationFrame(() => {
            // Limpiar ambos editores Quill
            Object.values(editors).forEach(editor => {
                editor.setText('');
            });

            // Actualizar campos ocultos de Quill
            ['descripcion', 'antecedentes'].forEach(id => {
                const value = document.getElementById(id);

                if (value) {
                    value.value = '';
                }
            });

            // Actualizar resumen con valores iniciales
            summary();
        });
    });

    // =====================================================
    // VALIDACIÓN DEL FORMULARIO
    // =====================================================

    form.addEventListener('submit', async event => {
        event.preventDefault();

        const message = document.getElementById('formMessage');
        const description = document.getElementById('descripcion');
        const duracion = document.getElementById('duracion');

        // Validar descripción
        if (!description.value.trim()) {
            message.textContent =
                'Debes completar la descripción e instrucciones del caso.';

            if (editors.descripcion) {
                editors.descripcion.focus();
            } else {
                description.focus();
            }

            return;
        }

        // Validar duración máxima
        if (duracion) {
            const minutos = Number(duracion.value);

            if (
                duracion.value.trim() === '' ||
                !Number.isInteger(minutos) ||
                minutos < 1 ||
                minutos > 10080
            ) {
                message.textContent =
                    'Debes ingresar una duración válida entre 1 y 10080 minutos.';

                duracion.focus();
                return;
            }
        }

        if (!form.reportValidity()) return;
        const keys = ['titulo', 'descripcion', 'antecedentes', 'nivel', 'tipo', 'estado', 'servicio', 'duracion', 'paciente', 'edad', 'sexo', 'prevision', 'esi'];
        const payload = {};
        keys.forEach(key => { payload[key] = document.getElementById(key)?.value?.trim() || null; });
        payload.segmentario = {
            cabeza_cuello: document.getElementById('seg_cabeza_cuello').value.trim(),
            torax_abdomen: document.getElementById('seg_torax_abdomen').value.trim(),
            extremidades_piel: document.getElementById('seg_extremidades_piel').value.trim(),
            neurologico: document.getElementById('seg_neurologico').value.trim()
        };
        payload.duracion = Number(payload.duracion);
        payload.edad = payload.edad ? Number(payload.edad) : null;
        if (!payload.titulo || !payload.descripcion) {
            message.textContent = 'Completa el título y la descripción del caso.';
            return;
        }
        const saveButton = form.querySelector('[type="submit"]');
        saveButton.disabled = true;
        message.textContent = 'Guardando caso...';
        try {
            const response = await window.DocenteAPI.send('/docente/casos', 'POST', payload);
            message.textContent = `Caso ${response.data?.codigo || ''} creado correctamente.`;
            window.location.assign('/docente/casos');
        } catch (error) {
            message.textContent = error.message;
        } finally { saveButton.disabled = false; }

    });

    // =====================================================
    // INICIALIZACIÓN
    // =====================================================

    summary();
});
