/* Presentación compartida y segura para fichas de 3.º y 4.º medio.
 * Nunca convierte contenido del estudiante a HTML; todas las celdas usan textContent. */
(function () {
    'use strict';
    const names = {
        id: 'Identificador', casoId: 'Código del caso', CasoId: 'Código del caso', actualizadoEn: 'Última actualización', ActualizadoEn: 'Última actualización',
        fechaActualizacion: 'Fecha de actualización', FechaActualizacion: 'Fecha de actualización', fecha: 'Fecha del registro',
        pas: 'Presión sistólica', pad: 'Presión diastólica', fc: 'Frecuencia cardíaca', fr: 'Frecuencia respiratoria',
        spo2: 'Saturación de oxígeno (SpO₂)', imc: 'IMC', eva: 'Escala EVA',
        motivoConsulta: 'Motivo de consulta', MotivoConsulta: 'Motivo de consulta', enfermedadActual: 'Enfermedad actual', EnfermedadActual: 'Enfermedad actual',
        antecedentesMorbidos: 'Antecedentes mórbidos', antecedentesQuirurgicos: 'Antecedentes quirúrgicos',
        antecedentesFamiliares: 'Antecedentes familiares', detalleMorbidos: 'Detalle de antecedentes mórbidos',
        detalleQuirurgicos: 'Detalle de antecedentes quirúrgicos', familiaresEstado: 'Antecedentes familiares',
        riesgoCaida: 'Riesgo de caída', actividadFisica: 'Actividad física',
        detalleAlergias: 'Detalle de alergias', detalleMedicamentos: 'Detalle de medicamentos',
        motivo_consulta: 'Motivo de consulta', interpretacion_clinica: 'Interpretación clínica',
        fecha_hora: 'Fecha y hora', profesional_asignado: 'Profesional asignado',
        tiene_alergias: 'Alergias', tiene_medicamentos: 'Medicamentos habituales',
        tiene_patologias: 'Patologías crónicas', estado_conciencia: 'Estado de conciencia',
        fecha_citacion: 'Fecha de citación', archivoNombre: 'Nombre del archivo',
        nombre: 'Nombre del material', materiales: 'Materiales y resultados',
        oxigenoterapia: 'Oxigenoterapia', glasgow: 'Puntaje Glasgow', scq: 'Superficie corporal quemada (%)',
        obstruccion: 'Obstrucción de vía aérea', collar: 'Control cervical', aspiracion: 'Aspiración de secreciones',
        intubacion: 'Manejo avanzado de vía aérea', murmullo: 'Murmullo pulmonar',
        simetria: 'Simetría torácica', ventilacion: 'Tipo de ventilación',
        esfuerzo: 'Trabajo respiratorio', pulso: 'Frecuencia del pulso', capilar: 'Llenado capilar',
        lesiones: 'Lesiones observadas', termica: 'Condición térmica',
        actualizado: 'Última actualización', data: 'Hallazgos de la evaluación'
    };
    const label = key => names[key] || String(key)
        .replace(/([a-záéíóúñ])([A-ZÁÉÍÓÚÑ])/g, '$1 $2')
        .replace(/[_-]+/g, ' ')
        .replace(/\b\p{L}/gu, v => v.toUpperCase());
    const dateFormat = (value, key) => {
        if (typeof value !== 'string' || !/(fecha|actualizado|updated|created|timestamp)/i.test(key)) return null;
        if (!/^\d{4}-\d{2}-\d{2}(?:T\d{2}:\d{2})?/.test(value)) return null;
        const d = new Date(value.length === 10 ? value + 'T12:00:00' : value);
        return Number.isNaN(d.getTime()) ? null : (value.length === 10 ? d.toLocaleDateString('es-CL') : d.toLocaleString('es-CL'));
    };
    const scalar = (value, key = '') => {
        if (value === null || value === undefined || value === '') return 'Sin registrar';
        if (typeof value === 'boolean') return value ? 'Sí' : 'No';
        if (typeof value === 'number') return String(value);
        const d = dateFormat(value, key); if (d) return d;
        if (typeof value === 'string') return value.length > 3500 ? value.slice(0, 3500) + '…' : value;
        if (Array.isArray(value)) return value.map(v => scalar(v, key)).join(', ');
        return 'Información disponible en el detalle';
    };
    const elem = (tag, cls, content) => {const el = document.createElement(tag); if (cls) el.className = cls; if (content !== undefined) el.textContent = String(content); return el;};
    const skip = key => key === 'id' || key === 'casoId' || key.startsWith('_');
    const abcdeGroups = [
        ['A · Vía aérea y control cervical', ['obstruccion','collar','aspiracion','intubacion']],
        ['B · Ventilación', ['murmullo','simetria','ventilacion','oxigenoterapia','flujo','otroOxigeno','esfuerzo']],
        ['C · Circulación', ['pulso','ritmo','calidad','hemorragia','capilar','accesos']],
        ['D · Evaluación neurológica', ['ocular','verbal','motora','pupilas','avdi','glasgow']],
        ['E · Exposición', ['lesiones','otrasDescripcion','termica','quemaduras','scq']]
    ];

    function render(raw, type = '') {
        const root = elem('div', 'clinical-readable');
        if (raw == null || raw === '') { root.append(elem('p','clinical-muted','Sin datos guardados.')); return root; }
        let data = raw;
        if (typeof raw === 'string') {try {data = JSON.parse(raw);} catch {data = raw;}}
        const field = (k,v, parent) => {const item=elem('div','clinical-field');item.append(elem('strong','',label(k)),elem('span','',scalar(v,k)));parent.append(item);};
        const group = (title,parent) => {const box=elem('section','clinical-group');box.append(elem('h5','',title));parent.append(box);return box;};
        function objectContents(obj,parent,depth) {
            if (depth > 7) return;
            const grid=elem('div','clinical-grid');
            for (const [k,v] of Object.entries(obj)) {
                if (skip(k)) continue;
                if (v != null && typeof v === 'object') {
                    if (k === 'materiales' && Array.isArray(v)) {
                        const section=group('Materiales y resultados', parent);
                        if (!v.length) section.append(elem('p','clinical-muted','Sin materiales asociados.'));
                        v.forEach((mat,i) => {
                            const card=group(`Material ${i+1}${mat?.nombre ? ' · '+mat.nombre : ''}`,section);
                            if (mat && typeof mat === 'object') {
                                const name = mat.archivoNombre || mat.nombreArchivo || mat.fileName || mat.filename || null;
                                if (name) field('archivoNombre', name, card);
                                else field('archivoNombre', 'Sin archivo adjunto', card);
                                walk(mat,card,depth+1,['archivoNombre','nombreArchivo','fileName','filename','id','nombre']);
                            } else walk(mat,card,depth+1);
                        });
                    } else {
                        const box=group(label(k),parent); walk(v,box,depth+1);
                    }
                } else field(k,v,grid);
            }
            if (grid.childNodes.length) parent.prepend(grid);
        }
        function walk(value,parent,depth=0,excluded=[]) {
            if (depth>7) return;
            if (Array.isArray(value)) {
                if (!value.length) {parent.append(elem('p','clinical-muted','Sin registros.'));return;}
                // ABCDE: cada entrada contiene data con la evaluación clínica, no una celda [object Object].
                if (value.every(v=>v && typeof v==='object' && !Array.isArray(v) && v.data && typeof v.data==='object')) {
                    value.forEach((entry,i)=>{
                        const card=group('Evaluación '+(i+1),parent);
                        if (entry.fecha) field('fecha',entry.fecha,card);
                        if (entry.actualizado) field('actualizado',entry.actualizado,card);
                        const data = entry.data;
                        const used = new Set();
                        abcdeGroups.forEach(([title, keys]) => {
                            const matches = keys.filter(k => Object.prototype.hasOwnProperty.call(data,k));
                            if (!matches.length) return;
                            const section = group(title,card);
                            const values=elem('div','clinical-grid');
                            matches.forEach(k => {field(k,data[k],values);used.add(k);});
                            section.append(values);
                        });
                        const extras=Object.fromEntries(Object.entries(data).filter(([key])=>!used.has(key)));
                        if(Object.keys(extras).length){const section=group('Observaciones y datos adicionales',card);walk(extras,section,depth+1);}
                    });return;
                }
                if (value.every(v=>v && typeof v==='object' && !Array.isArray(v))) {
                    const keys=[...new Set(value.flatMap(item=>Object.keys(item)))].filter(k=>!skip(k));
                    // En presencia de datos anidados, se muestran tarjetas legibles en lugar de una tabla rota.
                    if (keys.some(k=>value.some(v=>v[k] && typeof v[k]==='object'))) {
                        value.forEach((v,i)=>{const card=group('Registro '+(i+1),parent);walk(v,card,depth+1);});return;
                    }
                    const scroll=elem('div','clinical-table-scroll'),table=elem('table','clinical-table');
                    const tr=elem('tr');keys.forEach(k=>tr.append(elem('th','',label(k))));
                    const thead=elem('thead');thead.append(tr);table.append(thead);
                    const tbody=elem('tbody');value.forEach(item=>{const row=elem('tr');keys.forEach(k=>row.append(elem('td','',scalar(item[k],k))));tbody.append(row);});table.append(tbody);
                    scroll.append(table);parent.append(scroll);return;
                }
                value.forEach((v,i)=>{
                    if (v === null || typeof v !== 'object') field('Elemento '+(i+1),v,parent);
                    else {const g=group('Elemento '+(i+1),parent);walk(v,g,depth+1);}
                });return;
            }
            if (value && typeof value === 'object') {
                if (excluded.length) value=Object.fromEntries(Object.entries(value).filter(([k])=>!excluded.includes(k)));
                if ('form' in value || 'tables' in value) {
                    walk(value.form || {},parent,depth+1);
                    const tables=value.tables||{};
                    if (Array.isArray(tables)) {if (tables.length)walk(tables,group('Registros adicionales',parent),depth+1);}
                    else Object.entries(tables).forEach(([k,v])=>walk(v,group(label(k),parent),depth+1));
                    return;
                }
                objectContents(value,parent,depth);return;
            }
            parent.append(elem('p','clinical-plain',scalar(value)));
        }
        walk(data,root);
        return root;
    }
    window.FichaRecordView = {render, label};
})();
