/* Persistencia de fichas CESFAM con confirmación antes de sobrescribir. */
(() => {
  'use strict';
  const ctx = window.fichaThirdYear;
  if (!ctx?.codigo || !ctx.section) return;
  const key = `ficha-tp-3ro:${ctx.codigo}:${ctx.section}`;
  const tableKey = key + ':tablas';
  try {
    if (ctx.snapshot && typeof ctx.snapshot === 'object') {
      localStorage.setItem(key, JSON.stringify(ctx.snapshot.form || {}));
      if (ctx.section === 'antecedentes-3ro') localStorage.setItem(tableKey, JSON.stringify(ctx.snapshot.tables || {}));
    } else {
      localStorage.removeItem(key);
      if (ctx.section === 'antecedentes-3ro') localStorage.removeItem(tableKey);
    }
  } catch (error) { console.warn('No se pudo preparar el borrador:', error); }

  document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector(`[data-ces-form="${ctx.section}"]`);
    if (!form) return;
    const $ = id => document.getElementById(id);
    const message = $('cesMessage');
    const buttonSubmit = form.querySelector('button[type="submit"]');
    let submitting = false, busy = false;

    function notice(text, isError=false) {
      if (!message) return;
      message.textContent = text;
      message.style.color = isError ? '#b42318' : '#17663b';
    }

    // Elementos reutilizables y accesibles; el resto del diseño original se conserva.
    const backdrop = document.createElement('div');
    backdrop.className = 'ces-persist-backdrop'; backdrop.hidden = true;
    backdrop.innerHTML = `<div class="ces-persist-modal" role="dialog" aria-modal="true" aria-labelledby="cesPersistTitle">
      <div class="ces-persist-symbol" aria-hidden="true">!</div>
      <h2 id="cesPersistTitle">Confirmar guardado</h2>
      <p id="cesPersistDescription"></p>
      <div class="ces-persist-actions"><button type="button" id="cesPersistCancel">Cancelar</button><button type="button" id="cesPersistAccept">Confirmar</button></div>
    </div>`;
    document.body.append(backdrop);
    let acceptAction = null;
    const close = () => { backdrop.hidden = true; acceptAction = null; };
    function confirmAction(title, description, action) {
      $('cesPersistTitle').textContent = title;
      $('cesPersistDescription').textContent = description;
      acceptAction = action; backdrop.hidden = false;
      $('cesPersistCancel').focus();
    }
    $('cesPersistCancel').addEventListener('click', close);
    $('cesPersistAccept').addEventListener('click', () => { const fn = acceptAction; close(); fn?.(); });
    backdrop.addEventListener('click', e => { if(e.target === backdrop)close(); });
    document.addEventListener('keydown', e => { if(e.key === 'Escape' && !backdrop.hidden) close(); });

    const professional = form.querySelector('[name="profesional_asignado"]');
    if (professional) {
      professional.value = ctx.usuario || professional.value;
      professional.readOnly = true;
    }
    if (!ctx.editable) {
      for (const field of form.elements) if (!['button'].includes(field.type) || field.id === 'cesSaveAndSubmit' || field.type === 'submit') field.disabled = true;
      if ($('cesSaveAndSubmit')) $('cesSaveAndSubmit').disabled = true;
      notice('Ficha de solo lectura: ya fue entregada o se cerró el plazo.');
      return;
    }

    async function save(deliver) {
      if (busy) return;
      // No se permite guardar antecedentes sin un motivo de consulta.
      if (ctx.section === 'antecedentes-3ro') {
        const motivo = form.elements.namedItem('motivo_consulta');
        if (!motivo?.value.trim()) { notice('Debes ingresar el motivo de consulta antes de guardar.', true); motivo?.focus(); return; }
        for (const name of ['patologias','alergias','medicamentos']) {
          const choice = form.querySelector(`[name="tiene_${name}"]:checked`)?.value;
          const inputs = [...form.querySelectorAll(`#ces${name[0].toUpperCase() + name.slice(1)}Rows input`)];
          if (choice === 'si' && !inputs.some(input => input.value.trim())) {
            notice(`Seleccionaste «Sí refiere» para ${name}. Añade al menos un registro.`, true); return;
          }
        }
      }
      if (!form.reportValidity()) return;
      busy = true;
      if (buttonSubmit) buttonSubmit.disabled = true;
      if ($('cesSaveAndSubmit')) $('cesSaveAndSubmit').disabled = true;
      notice(deliver ? 'Guardando y entregando...' : 'Guardando registro...');
      try {
        // El script CESFAM genera el formulario y las tablas con el mismo formato original.
        form.dispatchEvent(new Event('ces-local-save', {bubbles:false}));
        const formData = JSON.parse(localStorage.getItem(key) || '{}');
        if (professional) formData.profesional_asignado = ctx.usuario;
        const tables = ctx.section === 'antecedentes-3ro' ? JSON.parse(localStorage.getItem(tableKey) || '{}') : {};
        const response = await fetch(`/3ro/casos/${encodeURIComponent(ctx.codigo)}/secciones/${encodeURIComponent(ctx.section)}`, {
          method:'PUT',credentials:'same-origin',headers:{'Accept':'application/json','Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},
          body:JSON.stringify({form:formData,tables})
        });
        const result = await response.json().catch(()=>({}));
        if (!response.ok) throw new Error(result.errors ? Object.values(result.errors).flat().join(' ') : (result.message || 'No se pudo guardar el registro.'));
        ctx.snapshot = {form:formData,tables};
        if (deliver) {
          const entregado = await fetch(`/3ro/casos/${encodeURIComponent(ctx.codigo)}/entregar`,{
            method:'POST',credentials:'same-origin',headers:{'Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}
          });
          const salida = await entregado.json().catch(()=>({}));
          if (!entregado.ok) throw new Error(salida.message || 'No fue posible entregar la ficha.');
          window.location.assign(`/estudiante/caso/${encodeURIComponent(ctx.codigo)}`);
          return;
        }
        notice('Registro guardado correctamente.');
      } catch (error) { notice(error.message || 'Error al guardar.', true); }
      finally { busy = false; if (buttonSubmit) buttonSubmit.disabled = false; if ($('cesSaveAndSubmit')) $('cesSaveAndSubmit').disabled = false; }
    }

    function requestSave(deliver) {
      if (busy) return;
      if (deliver) {
        confirmAction('Guardar y entregar ficha',
          'Se guardará esta última sección y la ficha se entregará al docente. Después no podrás modificarla. ¿Confirmas la entrega?',
          () => save(true));
      } else if (ctx.snapshot) {
        confirmAction('Sobrescribir registro',
          'Ya existe un registro guardado para esta sección. ¿Deseas reemplazarlo por los datos actuales?',
          () => save(false));
      } else save(false);
    }
    form.addEventListener('submit', e => {e.preventDefault(); e.stopImmediatePropagation(); requestSave(false);}, true);
    $('cesSaveAndSubmit')?.addEventListener('click', ()=>requestSave(true));
  });
})();
