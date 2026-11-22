/*
 * Adaptador temporal entre los formularios existentes de 4.º medio (que escriben
 * borradores en Storage) y la persistencia autorizada de Laravel. No reemplaza
 * la validación del servidor; solo sincroniza los JSON que las vistas ya generan.
 */
(() => {
    'use strict';
    const cfg = window.__FICHA_4TO;
    if (!cfg || !cfg.codigo) return;
    const slugs = {
        admision: ['ficha-tp-admision-demo-', 'local'],
        triage: ['ficha-tp-triage-demo-', 'local'],
        'signos-vitales': ['fctp_signos_vitales_v2_', 'session'],
        abcde: ['fctp_abcde_v1_', 'session'],
        anamnesis: ['fctp_anamnesis_v1_', 'session'],
        'examen-fisico': ['fctp_examen_fisico_v1_', 'session'],
        procedimientos: ['fctp_procedimientos_v1_', 'session'],
        medicamentos_4to: ['fctp_medicamentos_v1_', 'session'],
        examenes: ['fctp_solicitudes_examenes_v2_', 'session'],
        'acciones-enfermeria': ['fctp_acciones_enfermeria_v1_', 'session'],
        destino: ['fctp_destino_v1_', 'session']
    };
    const slug = decodeURIComponent(location.pathname.split('/').pop());
    if (!slugs[slug]) return;
    const [prefix, type] = slugs[slug];
    const key = prefix + cfg.codigo;
    const target = type === 'local' ? localStorage : sessionStorage;
    const nativeSet = Storage.prototype.setItem;
    const nativeRemove = Storage.prototype.removeItem;
    const snapshot = cfg.snapshots?.[slug];
    let hasServerCopy = !!snapshot;
    const uiDraftKey = 'ficha-tp-ui-borrador-' + String(cfg.student_id || 'unknown') + '-' + cfg.codigo + '-' + slug;
    let uiDraftVersion = 0;
    let pendingSaves = 0;
    // No reutilizar datos locales pertenecientes a otro usuario de este equipo.
    try {
        nativeRemove.call(target, key);
        if (snapshot?.key === key && snapshot?.type === type) nativeSet.call(target, key, snapshot.value);
    } catch (error) { console.warn('No se puede acceder al borrador local:', error); }

    let banner = null;
    let statusTimer = null;
    let lastSave = Promise.resolve();
    let queue = Promise.resolve();
    function dismissStatus() {
        if (statusTimer) clearTimeout(statusTimer);
        statusTimer = null;
        if (banner) banner.hidden = true;
    }
    function status(message, error = false) {
        if (!banner) return;
        if (statusTimer) clearTimeout(statusTimer);
        banner.textContent = message;
        banner.hidden = !message;
        banner.style.background = error ? '#fff1f2' : '#e9f5ee';
        banner.style.color = error ? '#9f1239' : '#14532d';
        if (message) statusTimer = setTimeout(dismissStatus, error ? 8000 : 4300);
    }
    function synchronize(value) {
        const versionAtSave = uiDraftVersion;
        status('Guardando registro…');
        const url = '/4to/casos/' + encodeURIComponent(cfg.codigo) + '/secciones/' + encodeURIComponent(slug);
        pendingSaves++;
        const work = queue.then(async () => {
            // Saving in the active queue.
            try {
            const response = await fetch(url, {
                method: 'PUT', credentials: 'same-origin', keepalive: true,
                headers: {'Accept':'application/json', 'Content-Type':'application/json','X-CSRF-TOKEN':cfg.csrf},
                body: JSON.stringify({storage_key:key,storage_type:type,payload:value})
            });
            const data = await response.json().catch(() => ({}));
            if (!response.ok) throw new Error(data.message || `Error ${response.status} al guardar en el servidor.`);
            hasServerCopy = true;
            if (versionAtSave === uiDraftVersion) {
                try { sessionStorage.removeItem(uiDraftKey); } catch (_) {}
            }
            status('Registro guardado correctamente.');
            return data;
            } finally { pendingSaves--; }
        });
        // Permitir nuevo intento después de un error, pero flush() conserva el error anterior.
        queue = work.catch(() => {});
        lastSave = work.catch(err => {
            console.error('Error de persistencia del caso:', err);
            status('No fue posible guardar los cambios. Revisa tu conexión e inténtalo nuevamente.', true);
            throw err;
        });
        return lastSave;
    }
    Storage.prototype.setItem = function (storageKey, value) {
        if (String(storageKey) === key && this === target) {
            if (!cfg.editable) {
                status('Ficha cerrada: no se admiten nuevos registros.', true);
                throw new Error('Ficha cerrada');
            }
            nativeSet.call(this, storageKey, value);
            void synchronize(String(value)).catch(() => {});
            return;
        }
        return nativeSet.call(this, storageKey, value);
    };
    window.Ficha4toBridge = {flush: () => lastSave, editable: !!cfg.editable};

    document.addEventListener('DOMContentLoaded', () => {
        // Cada paso necesita ?caso=..., incluso el menú lateral y las pestañas
        // que el frontend original dejó con URLs sin parámetro.
        // Sin ese parámetro el middleware legítimamente devolvía a «Mis casos».
        document.querySelectorAll('a[href]').forEach(link => {
            const raw = link.getAttribute('href');
            if (!raw || raw === '#' || raw.startsWith('javascript:')) return;
            let url;
            try { url = new URL(raw, location.href); } catch { return; }
            if (url.origin !== location.origin) return;
            const match = url.pathname.match(/^\/estudiante\/([a-z0-9_-]+)$/i);
            if (!match || !slugs[match[1]]) return;
            url.searchParams.set('caso', cfg.codigo);
            link.href = url.pathname + url.search + url.hash;
        });
        // Respaldo temporal de campos que el estudiante está editando antes de pulsar
        // Guardar: conserva valores cuando visita Resumen u otra pestaña.
        // El guardado de la sección sigue siendo el que confirma persistencia en MySQL.
        if (cfg.editable) setTimeout(() => {
            const form = document.querySelector('main form');
            if (!form) return;
            const controls = () => [...form.querySelectorAll('input,select,textarea')].filter(el =>
                !['hidden','submit','button','reset','password','file'].includes(el.type));
            try {
                const raw = sessionStorage.getItem(uiDraftKey);
                const draft = raw ? JSON.parse(raw) : null;
                if (draft?.values && Array.isArray(draft.values)) {
                    controls().forEach((control, i) => {
                        const old = draft.values[i];
                        if (!old || old.name !== (control.name || '') || old.id !== (control.id || '')) return;
                        if (control.type === 'checkbox' || control.type === 'radio') control.checked = !!old.checked;
                        else if (typeof old.value === 'string') control.value = old.value;
                    });
                    form.querySelectorAll('select,input[type="checkbox"],input[type="radio"]').forEach(el => {
                        el.dispatchEvent(new Event('change', {bubbles: false}));
                    });
                    status('Se recuperaron cambios pendientes de guardar.');
                }
            } catch (error) { console.warn('No se pudo recuperar borrador de formulario:', error); }
            let timer;
            const captureUiDraft = () => {
                try {
                    sessionStorage.setItem(uiDraftKey, JSON.stringify({values: controls().map(el => ({
                        name: el.name || '', id: el.id || '', value: el.value, checked: !!el.checked
                    }))}));
                    uiDraftVersion++;
                } catch (error) { console.warn('No se pudo conservar el borrador de pantalla:', error); }
            };
            const queueUiDraft = () => { clearTimeout(timer); timer = setTimeout(captureUiDraft, 250); };
            form.addEventListener('input', queueUiDraft);
            form.addEventListener('change', queueUiDraft);
            // Al salir de una pestaña, captura el último carácter incluso si no se cumplió el debounce.
            document.addEventListener('click', event => {
                if (event.target.closest('a[href]')) { clearTimeout(timer); captureUiDraft(); }
            }, true);
        }, 0);
        // Evitar salir mientras un guardado real está pendiente: los datos deben
        // confirmarse en el servidor antes de abandonar esta sección.
        document.addEventListener('click', e => {
            if (!pendingSaves || e.defaultPrevented || e.ctrlKey || e.metaKey || e.shiftKey || e.altKey) return;
            const link = e.target.closest('a[href]');
            if (!link || link.target === '_blank') return;
            let url;
            try { url = new URL(link.href, location.href); } catch { return; }
            if (url.origin !== location.origin || url.href === location.href) return;
            e.preventDefault();
            lastSave.then(() => { location.assign(url.href); })
                .catch(error => status('No se guardaron los cambios. Inténtalo nuevamente antes de continuar.', true));
        }, true);
        // El usuario confirma cualquier reemplazo de sección previamente guardada.
        // Captura antes que el handler propio de cada formulario; no altera su diseño.
        // Las secciones con historial solo añaden registros: no sobrescriben
        // registros existentes al agregar procedimientos, medicamentos o exámenes.
        const addOnly = ['procedimientos', 'medicamentos_4to', 'examenes', 'acciones-enfermeria'].includes(slug);
        if (cfg.editable && slug !== 'destino' && !addOnly) {
            let bypass = false;
            const backdrop = document.createElement('div');
            backdrop.className = 'ficha-overwrite-overlay'; backdrop.hidden = true;
            backdrop.innerHTML = `<div class="ficha-overwrite-dialog" role="dialog" aria-modal="true" aria-labelledby="overwriteTitle">
                <h2 id="overwriteTitle">Sobrescribir registro</h2>
                <p id="overwriteDescription">Ya guardaste esta sección. ¿Deseas reemplazar el registro anterior con los datos actuales?</p>
                <div class="ficha-overwrite-actions"><button type="button" data-cancel>Cancelar</button><button type="button" data-accept>Sí, guardar cambios</button></div>
            </div>`;
            document.body.append(backdrop);
            let proceed = null;
            function close() { backdrop.hidden = true; proceed = null; }
            backdrop.querySelector('[data-cancel]').addEventListener('click', close);
            backdrop.querySelector('[data-accept]').addEventListener('click', () => {
                const fn = proceed; close(); bypass = true;
                try { fn?.(); } finally { bypass = false; }
            });
            backdrop.addEventListener('click', e => {if(e.target === backdrop)close();});
            document.addEventListener('keydown', e => {if(e.key === 'Escape' && !backdrop.hidden)close();});
            // Tanto formularios submit como botones que guardan directamente.
            document.addEventListener('submit', e => {
                if (bypass || !hasServerCopy || !cfg.editable || !e.target.closest('main')) return;
                // Los signos vitales mantienen un historial: «Guardar registro» AÑADE,
                // mientras que «Actualizar registro» reemplaza el seleccionado.
                // No se debe confundir una nueva medición con sobrescribir el historial.
                if (slug === 'signos-vitales') {
                    const isEdit = document.getElementById('guardarRegistro')?.textContent?.trim() === 'Actualizar registro';
                    if (!isEdit) return;
                    backdrop.querySelector('#overwriteDescription').textContent =
                        'Vas a reemplazar los datos del registro seleccionado del historial. ¿Deseas guardar los cambios?';
                }
                // Evitar envío del formulario en caso de inválidos; los validadores originales siguen vigentes.
                if (!e.target.checkValidity()) return;
                e.preventDefault(); e.stopImmediatePropagation();
                const form = e.target;
                proceed = () => form.requestSubmit();
                backdrop.hidden = false; backdrop.querySelector('[data-cancel]').focus();
            },true);
            document.addEventListener('click', e => {
                if (bypass || !hasServerCopy || !cfg.editable) return;
                const button = e.target.closest('button');
                if (!button || button.type === 'submit' || button.closest('.ficha-overwrite-overlay')) return;
                if (!button.closest('main') || !/guardar/i.test(button.textContent || '')) return;
                e.preventDefault(); e.stopImmediatePropagation();
                proceed = () => button.click();
                backdrop.hidden = false; backdrop.querySelector('[data-cancel]').focus();
            },true);
        }
        banner = document.createElement('div');
        banner.id = 'serverSaveStatus'; banner.setAttribute('role', 'status'); banner.setAttribute('aria-live','polite');
        banner.setAttribute('aria-label', 'Notificación. Pulsa para cerrar.');
        banner.tabIndex = 0;
        Object.assign(banner.style, {position:'fixed',bottom:'12px',right:'12px',maxWidth:'360px',zIndex:'9000',padding:'10px 14px',borderRadius:'10px',fontSize:'13px',fontWeight:'600',boxShadow:'0 4px 20px #0002',cursor:'pointer'});
        banner.addEventListener('click', dismissStatus);
        banner.addEventListener('keydown', event => {
            if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); dismissStatus(); }
        });
        document.body.append(banner);
        status(cfg.editable ? 'Ficha lista para continuar.' : 'Ficha de solo lectura: entregada o fuera de plazo.',!cfg.editable);
        if (!cfg.editable) {
            for (const form of document.querySelectorAll('form')) {
                if (form.closest('aside') || form.closest('nav')) continue;
                form.querySelectorAll('input,select,textarea,button[type="submit"]').forEach(el => {
                    if (el.type !== 'hidden') el.disabled = true;
                });
            }
        }
    });
})();
