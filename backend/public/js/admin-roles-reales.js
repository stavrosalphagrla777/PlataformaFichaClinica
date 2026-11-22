document.addEventListener('DOMContentLoaded', () => {
    'use strict';
    const $ = id => document.getElementById(id);
    let multiple = false, previousFocus = null;
    const checkboxes = [...document.querySelectorAll('.role-user-check')];
    const rows = [...document.querySelectorAll('.roles-user-row[data-rol]')];
    const courses = {'3_medio':['3.º Medio D','3.º Medio F','V3ro'], '4_medio':['4.º Medio C','4.º Medio G','V4to']};
    function checkedIds() { return multiple ? checkboxes.filter(c => c.checked && !c.closest('.roles-user-row')?.hidden).map(c => c.value) : ($('usuario').value ? [$('usuario').value] : []); }
    function syncCourses(preferred = '') {
        const opts = courses[$('nivel_educativo').value] || [];
        $('curso').replaceChildren(new Option('Seleccionar curso',''));
        opts.forEach(v => $('curso').add(new Option(v,v)));
        if(opts.includes(preferred)) $('curso').value = preferred;
    }
    function updateForm() {
        const isStudent = $('rol').value === 'estudiante';
        $('levelField').hidden = $('courseField').hidden = !isStudent;
        $('nivel_educativo').disabled = $('curso').disabled = !isStudent;
        $('nivel_educativo').required = $('curso').required = isStudent;
        $('summaryRole').textContent = $('rol').selectedOptions[0]?.textContent || '—';
        $('summaryLevel').textContent = isStudent ? ($('nivel_educativo').selectedOptions[0]?.textContent || '—') : 'No aplica';
        $('summaryCourse').textContent = isStudent ? ($('curso').value || '—') : 'No aplica';
        $('summaryName').textContent = `${checkedIds().length} usuario(s) seleccionado(s)`;
        $('selectedCount').textContent = `${checkedIds().length} usuario(s) seleccionado(s)`;
    }
    function filterRows() {
        [...$('usuario').options].forEach(opt => { if (!opt.value) return; opt.hidden = ((($('jornadaRoles')?.value || 'diurno') === 'vespertino') !== /^V(?:3ro|4to)$/i.test(opt.dataset.curso || '')) && (opt.dataset.rol === 'estudiante'); });
        const q = $('searchUsers').value.trim().toLocaleLowerCase('es');
        const role = $('filterRole').value, level = $('filterLevel').value, letter = $('filterLetter').value, jornada = $('jornadaRoles')?.value || 'diurno';
        let visible=0;
        rows.forEach(row => {
            const good = ((jornada === 'vespertino') === /^V(?:3ro|4to)$/i.test(row.dataset.curso.trim()) || row.dataset.rol !== 'estudiante') && row.dataset.search.includes(q) && (!role || row.dataset.rol===role) && (!level || row.dataset.nivel===level) && (!letter || row.dataset.curso.trim().toUpperCase().endsWith(letter));
            row.hidden = !good; if(good) visible++;
        });
        $('visibleUserCount').textContent = `${visible} de ${rows.length} usuario(s) visibles`;
        const shown=rows.filter(row=>!row.hidden).map(row=>row.querySelector('input'));
        $('selectRolesAll').checked = shown.length>0 && shown.every(c=>c.checked);
        $('selectRolesAll').indeterminate = shown.some(c=>c.checked)&&!$('selectRolesAll').checked;
        updateForm();
    }
    function chooseMode(value) {
        multiple = value;
        $('individualArea').hidden=value; $('multipleArea').hidden=!value;
        $('modeIndividual').classList.toggle('active',!value); $('modeMultiple').classList.toggle('active',value);
        $('modeIndividual').setAttribute('aria-pressed',String(!value)); $('modeMultiple').setAttribute('aria-pressed',String(value));
        updateForm();
    }
    $('modeIndividual').addEventListener('click',()=>chooseMode(false));
    $('modeMultiple').addEventListener('click',()=>chooseMode(true));
    $('usuario').addEventListener('change', () => {
        const opt = $('usuario').selectedOptions[0];
        if(opt?.value){$('rol').value=opt.dataset.rol||'estudiante';$('nivel_educativo').value=opt.dataset.nivel||'';syncCourses(opt.dataset.curso||'');}
        updateForm();
    });
    checkboxes.forEach(c=>c.addEventListener('change',filterRows));
    $('selectRolesAll').addEventListener('change',e=>{rows.filter(r=>!r.hidden).forEach(r=>r.querySelector('input').checked=e.target.checked);filterRows();});
    $('clearSelection').addEventListener('click',()=>{checkboxes.forEach(c=>c.checked=false);filterRows();});
    ['searchUsers','filterRole','filterLevel','filterLetter'].forEach(id=>$(id).addEventListener(id==='searchUsers'?'input':'change',filterRows));
    $('jornadaRoles').addEventListener('change',()=>{window.location.href = '/admin/roles-niveles?jornada=' + encodeURIComponent($('jornadaRoles').value);});
    $('clearUserFilters').addEventListener('click',()=>{['searchUsers','filterRole','filterLevel','filterLetter'].forEach(id=>$(id).value='');filterRows();});
    $('rol').addEventListener('change', updateForm);
    $('nivel_educativo').addEventListener('change',()=>{syncCourses();updateForm();});
    $('curso').addEventListener('change',updateForm);
    $('resetChanges').addEventListener('click',()=>{
        $('rol').value='estudiante';$('nivel_educativo').value='';syncCourses();$('usuario').value='';checkboxes.forEach(c=>c.checked=false);filterRows();updateForm();
    });
    const modal=$('rolesModal');
    function close(){modal.hidden=true;if(previousFocus?.isConnected)previousFocus.focus();}
    $('cancelRoles').addEventListener('click',close);
    modal.addEventListener('click',e=>{if(e.target===modal)close();});
    document.addEventListener('keydown',e=>{if(e.key==='Escape'&&!modal.hidden){e.preventDefault();close();}});
    $('rolesForm').addEventListener('submit',e=>{
        e.preventDefault();const ids=checkedIds();
        if(!ids.length){$('rolesNotice').textContent='Selecciona al menos un usuario.';return;}
        if(!$('rolesForm').reportValidity())return;
        const target=$('roleIds');target.replaceChildren();
        ids.forEach(id=>{const hidden=document.createElement('input');hidden.type='hidden';hidden.name='ids[]';hidden.value=id;target.append(hidden);});
        $('rolesModalDescription').textContent=`¿Deseas aplicar el rol ${$('rol').value} a ${ids.length} usuario(s)? Esta operación cambia permisos reales.`;
        previousFocus=document.activeElement;modal.hidden=false;$('cancelRoles').focus();
    });
    $('confirmRoles').addEventListener('click',()=>{modal.hidden=true;$('rolesForm').submit();});
    filterRows();syncCourses();updateForm();
});
