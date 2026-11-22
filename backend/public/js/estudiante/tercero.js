
document.addEventListener('DOMContentLoaded', () => {
  // Control global de menú para todas las vistas CESFAM.
  const shell = document.getElementById('appShell');
  const toggle = document.getElementById('menuToggle');

  if (shell && toggle) {
    const syncMenu = () => {
      const open = !shell.classList.contains('sidebar-hidden');
      toggle.setAttribute('aria-expanded', String(open));
      toggle.setAttribute(
        'aria-label',
        open ? 'Cerrar menú lateral' : 'Abrir menú lateral'
      );
    };

    toggle.addEventListener('click', (event) => {
      event.preventDefault();
      shell.classList.toggle('sidebar-hidden');
      syncMenu();
    });

    syncMenu();
  }

  const form = document.querySelector('[data-ces-form]');
  if (!form) return;

  const key = `ficha-tp-3ro:${form.dataset.caso}:${form.dataset.cesForm}`;

  // Recuperación de borradores.
  try {
    const saved = JSON.parse(localStorage.getItem(key) || '{}');

    for (const field of form.elements) {
      if (!field.name || !(field.name in saved)) continue;

      if (field.type === 'checkbox') {
        field.checked = saved[field.name] === true;
      } else if (field.type === 'radio') {
        field.checked = field.value === saved[field.name];
      } else {
        field.value = saved[field.name];
      }
    }
  } catch (err) {
    console.warn('No se pudo recuperar el borrador', err);
  }

  // El cálculo es pedagógico: se muestra la fórmula, nunca se completa por el alumno.
  const peso = document.getElementById('cesPeso');
  const talla = document.getElementById('cesTalla');
  const imc = document.getElementById('cesImc');
  function calc() {
    if (!peso || !talla || !imc) return;
    const p=Number(peso.value), t=Number(talla.value)/100, entered=Number(imc.value);
    const valid = p>0 && t>0 && imc.value.trim() !== '';
    const ok=valid && Math.abs(entered-p/(t*t)) <= .11;
    const aviso=document.getElementById('cesImcAviso');
    if (aviso) aviso.textContent=valid ? (ok ? 'IMC correcto.' : 'El IMC no coincide con peso y talla; revisa la fórmula.') : '';
    imc.setCustomValidity(valid && !ok ? 'El IMC ingresado no coincide con peso y talla.' : '');
  }
  [peso,talla,imc].forEach(el=>el?.addEventListener('input',calc));
  calc();

  // EVA opcional.
  const eva = document.getElementById('cesEva');
  const evaCheck = document.getElementById('cesEvaHabilitada');
  const evaValue = document.getElementById('cesEvaValor');

  function syncEva() {
    if (!eva || !evaCheck) return;

    eva.disabled = !evaCheck.checked;

    if (evaValue) {
      evaValue.textContent = evaCheck.checked
        ? `EVA: ${eva.value}/10`
        : 'Sin registrar';
    }

    eva.style.setProperty(
      '--eva-progress',
      `${Number(eva.value) * 10}%`
    );
  }

  evaCheck?.addEventListener('change', syncEva);
  evaCheck?.addEventListener('input', syncEva);
  eva?.addEventListener('input', syncEva);
  syncEva();

  // Guardado local.
  const message = document.getElementById('cesMessage');

  form.addEventListener(window.fichaThirdYear ? 'ces-local-save' : 'submit', (event) => {
    event.preventDefault();
    calc();

    const data = {};

    for (const field of form.elements) {
      if (!field.name) continue;

      // Solo se guarda la alternativa seleccionada.
      if (field.type === 'radio' && !field.checked) continue;

      data[field.name] = field.type === 'checkbox'
        ? field.checked
        : field.value;
    }

    if (eva && evaCheck && !evaCheck.checked) {
      data.eva = '';
    }

    try {
      localStorage.setItem(key, JSON.stringify(data));

      if (message) {
        message.textContent =
          'Preparando el guardado...';
      }
    } catch (err) {
      if (message) {
        message.textContent = 'No fue posible guardar el borrador.';
      }
    }
  });

  // Modal de limpieza.
  const clearBtn = document.getElementById('cesClearButton');
  const modal = document.getElementById('cesClearModal');
  const cancel = document.getElementById('cesClearCancel');
  const confirm = document.getElementById('cesClearConfirm');

  let focusBeforeModal = null;

  function closeModal() {
    if (!modal) return;

    modal.hidden = true;
    focusBeforeModal?.focus();
  }

  clearBtn?.addEventListener('click', () => {
    if (!modal) return;

    focusBeforeModal = document.activeElement;
    modal.hidden = false;
    cancel?.focus();
  });

  cancel?.addEventListener('click', closeModal);

  modal?.addEventListener('click', (event) => {
    if (event.target === modal) closeModal();
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && modal && !modal.hidden) {
      closeModal();
    }

    if (event.key === 'Tab' && modal && !modal.hidden) {
      const buttons = [cancel, confirm].filter(Boolean);

      if (!buttons.length) return;

      if (event.shiftKey && document.activeElement === buttons[0]) {
        event.preventDefault();
        buttons.at(-1).focus();
      } else if (
        !event.shiftKey &&
        document.activeElement === buttons.at(-1)
      ) {
        event.preventDefault();
        buttons[0].focus();
      }
    }
  });

  confirm?.addEventListener('click', () => {
    form.reset();

    try {
      localStorage.removeItem(key);
    } catch (err) {
      console.warn('No se pudo eliminar el borrador', err);
    }

    calc();
    syncEva();

    if (message) {
      message.textContent = 'Formulario limpiado.';
    }

    closeModal();
  });

  // =========================================
  // SIGNOS VITALES 3RO - RANGOS DINÁMICOS
  // =========================================

  if (form.dataset.cesForm === 'signos-vitales-3ro') {
    const thresholds = {
      fc: {
        min: 20,
        max: 250,
        unit: 'lpm',
        classify(v) {
          return v <= 50
            ? ['critical', 'Bradicardia', 'Bradicardia crítica']
            : v < 60
              ? ['warning', 'Bradicardia', 'Bradicardia']
              : v <= 100
                ? ['normal', 'Normocardia', 'Frecuencia cardíaca normal']
                : v <= 120
                  ? ['warning', 'Taquicardia', 'Taquicardia']
                  : ['critical', 'Taquicardia', 'Taquicardia crítica'];
        }
      },

      fr: {
        min: 5,
        max: 60,
        unit: 'rpm',
        classify(v) {
          return v < 10
            ? ['critical', 'Bradipnea', 'Bradipnea crítica']
            : v < 12
              ? ['warning', 'Bradipnea', 'Bradipnea']
              : v <= 20
                ? ['normal', 'Eupnea', 'Frecuencia respiratoria normal']
                : v <= 28
                  ? ['warning', 'Taquipnea', 'Taquipnea']
                  : ['critical', 'Taquipnea', 'Taquipnea crítica'];
        }
      },

      spo2: {
        min: 50,
        max: 100,
        unit: '%',
        classify(v) {
          return v < 86
            ? ['critical', 'Hipoxia severa', 'Hipoxia severa']
            : v < 90
              ? ['warning', 'Hipoxia', 'Alerta de saturación']
              : v < 95
                ? ['info', 'Hipoxia leve', 'Hipoxia leve']
                : ['normal', 'Saturación normal', 'Saturación normal'];
        }
      },

      temperatura: {
        min: 30,
        max: 43,
        unit: '°C',
        classify(v) {
          return v < 35.5
            ? ['critical', 'Hipotermia', 'Hipotermia']
            : v < 37.3
              ? ['normal', 'Afebril', 'Temperatura normal']
              : v < 37.8
                ? ['warning', 'Subfebril', 'Febrícula']
                : ['critical', 'Fiebre', 'Hipertermia'];
        }
      }
    };

    const valueOf = (name) => {
      const field = form.elements.namedItem(name);

      if (!field || field.value.trim() === '') return null;

      const n = Number(field.value);
      return Number.isFinite(n) ? n : null;
    };

    const paint = (name, result, warning = '') => {
      const field = form.elements.namedItem(name);
      const wrapper = field?.closest(
        '.ces-vital-field, .ces-presion-field'
      );

      if (!wrapper) return;

      const alert = wrapper.querySelector('.ces-threshold-alert');
      const atypical = wrapper.querySelector('.ces-atypical-alert');

      if (!alert || !atypical) return;

      wrapper.classList.remove(
        'status-normal',
        'status-warning',
        'status-critical',
        'status-info',
        'status-invalid'
      );

      alert.replaceChildren();
      atypical.replaceChildren();
      alert.className = 'ces-threshold-alert';

      if (result) {
        const [status, physiological, clinical] = result;

        wrapper.classList.add('status-' + status);
        alert.classList.add('alert-' + status);

        [
          ['Rango fisiológico: ', physiological],
          ['Clasificación clínica: ', clinical]
        ].forEach(([heading, content]) => {
          const line = document.createElement('div');
          const strong = document.createElement('strong');

          strong.textContent = heading;
          line.append(
            strong,
            document.createTextNode(content)
          );

          alert.append(line);
        });
      }

      if (warning) {
        const strong = document.createElement('strong');
        strong.textContent = '⚠ Valor atípico detectado';

        const desc = document.createElement('span');
        desc.textContent = warning;

        const note = document.createElement('small');
        note.textContent = 'Verifica el valor antes de continuar.';

        atypical.append(strong, desc, note);
        wrapper.classList.add('status-invalid');
      }
    };

    const classifyPressure = (pas, pad) =>
      pas >= 180 || pad >= 110
        ? ['critical', 'Crisis hipertensiva', 'Crisis hipertensiva']
        : pas >= 140 || pad >= 90
          ? ['critical', 'Hipertensión', 'HTA etapa I / II']
          : pas < 90 || pad < 60
            ? ['critical', 'Hipotensión severa', 'Hipotensión severa']
            : pas >= 121 || pad >= 81
              ? ['warning', 'Normal alta', 'Normal alta']
              : ['normal', 'Normotensión', 'Presión arterial normal'];

    const classifyIMC = (v) =>
      v < 18.5
        ? ['warning', 'Bajo peso', 'Bajo peso']
        : v < 25
          ? ['normal', 'Normal', 'Peso normal']
          : v < 30
            ? ['warning', 'Sobrepeso', 'Sobrepeso']
            : ['critical', 'Obesidad', 'Obesidad'];

    function refreshVitals() {
      for (const [name, rule] of Object.entries(thresholds)) {
        const v = valueOf(name);
        const field = form.elements.namedItem(name);

        const valid =
          v !== null &&
          v >= rule.min &&
          v <= rule.max &&
          field.checkValidity();

        paint(
          name,
          valid ? rule.classify(v) : null,
          v !== null && !valid
            ? `Valor fuera del rango de referencia (${rule.min} a ${rule.max} ${rule.unit}).`
            : ''
        );
      }

      const pas = valueOf('pas');
      const pad = valueOf('pad');

      const complete = pas !== null && pad !== null;

      const valid =
        complete &&
        pas >= 1 &&
        pas <= 300 &&
        pad >= 1 &&
        pad <= 200 &&
        pas > pad;

      const warning = complete && !valid
        ? pas <= pad
          ? 'La presión sistólica debe superar a la diastólica.'
          : 'Verifica los valores de presión arterial ingresados.'
        : '';

      // Un solo cuadro de clasificación para ambas presiones.
      paint(
        'pas',
        valid ? classifyPressure(pas, pad) : null,
        warning
      );

      for (const [name, min, max, unit] of [
        ['peso', 1, 350, 'kg'],
        ['talla', 30, 250, 'cm']
      ]) {
        const v = valueOf(name);

        paint(
          name,
          null,
          v !== null && (v < min || v > max)
            ? `Valor fuera del rango de referencia (${min} a ${max} ${unit}).`
            : ''
        );
      }

      calc();

      const p = valueOf('peso');
      const t = valueOf('talla');

      const validAnthro =
        p !== null &&
        p >= 1 &&
        p <= 350 &&
        t !== null &&
        t >= 30 &&
        t <= 250;



      paint(
        'imc',
        validAnthro && imc?.value && !imc.validationMessage
          ? classifyIMC(Number(imc.value))
          : null
      );
    }

    form.addEventListener('input', refreshVitals);
    form.addEventListener('change', refreshVitals);

    document.getElementById('cesClearConfirm')
      ?.addEventListener('click', () => {
        queueMicrotask(refreshVitals);
      });

    refreshVitals();
  }

  // ANTECEDENTES 3RO: opciones, tabla y guardado se gestionan abajo.
});

// ============================================
// ANTECEDENTES 3RO — lógica exclusiva de la vista
// ============================================

// ============================================
// ANTECEDENTES 3RO - TABLAS EDITABLES
// No modifica las funciones de otras vistas.
// ============================================

document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector(
    '[data-ces-form="antecedentes-3ro"]'
  );

  if (!form) return;

  const storageKey =
    `ficha-tp-3ro:${form.dataset.caso}:antecedentes-3ro:tablas`;

  const definitions = {

    patologias: {
      tbody: 'cesPatologiasRows',
      fields: ['patologia'],
      placeholders: ['Ej.: Hipertensión arterial'],
      radio: 'tiene_patologias'
    },



    alergias: {
      tbody: 'cesAlergiasRows',
      fields: ['nombre'],
      placeholders: ['Ej.: Penicilina'],
      radio: 'tiene_alergias'
    },


    medicamentos: {
      tbody: 'cesMedicamentosRows',
      fields: [
        'medicamento',
        'dosis',
        'via',
        'frecuencia'
      ],
      placeholders: [
        'Ej.: Metformina',
        'Ej.: 850 mg',
        'Ej.: Oral',
        'Ej.: C/12 h'
      ],
      radio: 'tiene_medicamentos'
    }
  };

  const tables = {
    patologias: [],
    alergias: [],
    medicamentos: []
  };

  // Recupera tablas guardadas previamente.
  // También admite el formato anterior de patologías.
  try {
    const saved = JSON.parse(
      localStorage.getItem(storageKey) || '{}'
    );

    for (const type of Object.keys(definitions)) {
      if (Array.isArray(saved[type])) {
        tables[type] = saved[type];
      }
    }
  } catch (error) {
    console.warn(
      'No se pudieron recuperar las tablas de antecedentes.',
      error
    );
  }

  // Migración de patologías guardadas por el JS anterior.
  if (!localStorage.getItem(storageKey)) {
    try {
      const oldKey =
        `ficha-tp-3ro:${form.dataset.caso}:antecedentes-3ro:tablas`;

      const oldSaved = JSON.parse(
        localStorage.getItem(oldKey) || '{}'
      );

      if (Array.isArray(oldSaved.patologias)) {
        tables.patologias = oldSaved.patologias;
      }
    } catch (error) {
      console.warn(
        'No se pudo recuperar el formato anterior.',
        error
      );
    }
  }

  const selectedValue = (radioName) => {
    if (!radioName) return 'si';

    return form.querySelector(
      `input[name="${radioName}"]:checked`
    )?.value || 'no_informado';
  };

  const isEnabled = (type) => {
    const radio = definitions[type].radio;
    return !radio || selectedValue(radio) === 'si';
  };

  function createRow(type, row, index) {
    const definition = definitions[type];
    const tr = document.createElement('tr');

    definition.fields.forEach((fieldName, columnIndex) => {
      const td = document.createElement('td');
      const input = document.createElement('input');

      input.type = 'text';
      input.value = row[fieldName] || '';
      input.placeholder =
        definition.placeholders[columnIndex];

      input.disabled = !isEnabled(type);

      input.setAttribute(
        'aria-label',
        `${fieldName} - fila ${index + 1}`
      );

      input.addEventListener('input', () => {
        tables[type][index][fieldName] = input.value;
      });

      td.appendChild(input);
      tr.appendChild(td);
    });

    const actions = document.createElement('td');
    const remove = document.createElement('button');

    remove.type = 'button';
    remove.className = 'ces-ant-remove';
    remove.textContent = 'Eliminar';
    remove.disabled = !isEnabled(type);

    remove.setAttribute(
      'aria-label',
      `Eliminar fila ${index + 1}`
    );

    remove.addEventListener('click', () => {
      tables[type].splice(index, 1);
      renderTable(type);
    });

    actions.appendChild(remove);
    tr.appendChild(actions);

    return tr;
  }

  function renderTable(type) {
    const definition = definitions[type];
    const tbody = document.getElementById(
      definition.tbody
    );

    if (!tbody) return;

    tbody.replaceChildren();

    const enabled = isEnabled(type);
    const addButton = form.querySelector(
      `[data-add-row="${type}"]`
    );

    if (addButton) {
      addButton.disabled = !enabled;
    }

    if (!tables[type].length) {
      const tr = document.createElement('tr');
      tr.className = 'ces-ant-empty';

      const td = document.createElement('td');
      td.colSpan = definition.fields.length + 1;

      td.textContent = !enabled
        ? 'Selecciona "Sí refiere" para registrar información.'
        : 'No hay registros. Utiliza el botón Añadir.';

      tr.appendChild(td);
      tbody.appendChild(tr);
      return;
    }

    tables[type].forEach((row, index) => {
      tbody.appendChild(
        createRow(type, row, index)
      );
    });
  }

  function renderAll() {
    Object.keys(definitions).forEach(renderTable);
  }

  // Añadir filas.
  form.querySelectorAll('[data-add-row]').forEach(button => {
    button.addEventListener('click', () => {
      const type = button.dataset.addRow;

      if (!definitions[type] || !isEnabled(type)) {
        return;
      }

      const row = {};

      definitions[type].fields.forEach(field => {
        row[field] = '';
      });

      tables[type].push(row);
      renderTable(type);

      const tbody = document.getElementById(
        definitions[type].tbody
      );

      tbody?.querySelector(
        'tr:last-child input'
      )?.focus();
    });
  });

  // Cambiar las opciones de alergias o medicamentos.
  // Se deshabilita la tabla si no se selecciona "Sí refiere".

  ['tiene_patologias', 'tiene_alergias', 'tiene_medicamentos'].forEach(name => {

    form.querySelectorAll(
      `input[name="${name}"]`
    ).forEach(radio => {
      radio.addEventListener('change', () => {
        renderAll();
      });
    });
  });

  // Guardar todas las tablas junto con el formulario.
  // El controlador general guarda los campos normales.
  form.addEventListener(window.fichaThirdYear ? 'ces-local-save' : 'submit', () => {
    const data = {};

    for (const type of Object.keys(definitions)) {
      data[type] = isEnabled(type)
        ? tables[type].map(row => ({ ...row }))
        : [];
    }

    try {
      localStorage.setItem(
        storageKey,
        JSON.stringify(data)
      );
    } catch (error) {
      console.warn(
        'No se pudieron guardar las tablas.',
        error
      );
    }
  });

  // Limpieza confirmada.
  const clearConfirm = document.getElementById(
    'cesClearConfirm'
  );

  clearConfirm?.addEventListener('click', () => {
    for (const type of Object.keys(tables)) {
      tables[type] = [];
    }


    ['tiene_patologias', 'tiene_alergias', 'tiene_medicamentos'].forEach(name => {

      const option = form.querySelector(
        `input[name="${name}"][value="no_informado"]`
      );

      if (option) option.checked = true;
    });

    try {
      localStorage.removeItem(storageKey);
    } catch (error) {
      console.warn(
        'No se pudieron eliminar las tablas.',
        error
      );
    }

    renderAll();
  });

  // Evita que la limpieza nativa deje estados desactualizados.
  form.addEventListener('reset', () => {
    queueMicrotask(renderAll);
  });

  renderAll();
});
