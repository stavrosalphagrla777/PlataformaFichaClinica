
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Asignar roles y niveles | Ficha Clínica TP</title>
    <link rel="stylesheet" href="{{ asset('css/admin-usuarios.css') }}">
</head>
<body>
<div class="app">
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <div class="brand-logo">
                <img src="{{ asset('images/logo-tp.png') }}"
                     alt="Logotipo TP Enfermería">
            </div>
            <span>Ficha Clínica TP</span>
        </div>

        <div class="nav-title">Principal</div>
        <a href="{{ route('preview.admin.dashboard') }}" class="nav-link">Dashboard</a>

        <div class="nav-title">Acciones</div>
        <a href="{{ route('preview.admin.usuarios') }}" class="nav-link">Gestión de usuarios</a>
        <a href="{{ route('preview.admin.crear') }}" class="nav-link">Crear usuario</a>
        <a href="{{ route('preview.admin.roles') }}" class="nav-link active">Asignar roles y niveles</a>
        <a href="{{ route('preview.admin.historial') }}" class="nav-link" >Historial de actividad</a>
        <div class="nav-title">Funciones docentes</div>
            <a href="#" class="nav-link">Crear casos clínicos</a>
            <a href="#" class="nav-link">Asignación de casos</a>
            <a href="#" class="nav-link">Revisión de fichas</a>
            <a href="#" class="nav-link">Evaluaciones</a>
        <div class="nav-title">Configuración</div>
            <a href="#" class="nav-link">Configuración</a>
            <a href="#" class="nav-link">Cerrar sesión</a>
    </aside>

    <div class="main">
        <header class="topbar">
            <div class="topbar-left">
                <button type="button" class="menu-toggle"
                        id="menuToggle"
                        aria-label="Ocultar menú lateral"
                        aria-expanded="true"
                        aria-controls="sidebar">
                    <span></span><span></span><span></span>
                </button>
                <strong>Asignar roles y niveles</strong>
            </div>

            <div class="user-info">
                <div>
                    <strong>Administrador</strong>
                    <small>Panel administrativo</small>
                </div>
                <div class="user-avatar"><span>A</span></div>
            </div>
        </header>

        <main class="content">
            <div class="heading">
                <div>
                    <h1>Asignación de roles y niveles</h1>
                    <p class="subtitle">
                        Administra los perfiles de acceso de los usuarios
                        de la plataforma educativa.
                    </p>
                </div>

                <a href="{{ route('preview.admin.usuarios') }}"
                   class="primary-btn">← Volver a usuarios</a>
            </div>

            <div class="roles-mode-bar" role="group" aria-label="Modalidad de asignación">
                <button type="button" class="roles-mode active" id="modeIndividual" aria-pressed="true">Asignación individual</button>
                <button type="button" class="roles-mode" id="modeMultiple" aria-pressed="false">Asignación múltiple</button>
            </div>
            <div class="create-layout">
                <section class="panel">
                    <form id="rolesForm">
                        <div id="individualArea">
                            <h2>Seleccionar usuario</h2>
                            <p class="form-description">Selecciona la cuenta cuya configuración deseas actualizar.</p>
                            <div class="form-field">
                                <label for="usuario">Usuario</label>
                                <select id="usuario"><option value="">Selecciona un usuario...</option></select>
                            </div>
                        </div>
                        <div id="multipleArea" hidden>
                            <h2>Seleccionar usuarios</h2>
                            <p class="form-description">Selecciona varias cuentas para aplicarles una misma configuración.</p>
                            <div class="roles-selection-tools">
                                <input type="search" id="searchUsers" placeholder="Buscar usuario..." aria-label="Buscar usuarios">
                                <button type="button" class="secondary-btn admin-clean-button" id="clearSelection">Deseleccionar</button>
                            </div>
                            <div class="roles-filter-grid" aria-label="Filtros de usuarios">
                                <div><label for="filterRole">Rol</label><select id="filterRole"><option value="">Todos los roles</option><option>Estudiante</option><option>Docente</option><option>Administrador</option></select></div>
                                <div><label for="filterLevel">Nivel educativo</label><select id="filterLevel"><option value="">Todos los niveles</option><option>3.º medio</option><option>4.º medio</option></select></div>
                                <div><label for="filterLetter">Letra del curso</label><select id="filterLetter"><option value="">Todas las letras</option><option value="A">A</option><option value="B">B</option></select></div>
                            </div>
                            <div class="roles-filter-reset"><button type="button" class="secondary-btn admin-clean-button" id="clearUserFilters">Limpiar filtros</button></div>
                            <div class="roles-users-list">
                                <label class="roles-user-row roles-select-all"><input type="checkbox" id="selectAll"> <strong>Seleccionar todos los visibles</strong></label>
                                <div id="usersCheckboxes"></div>
                            </div>
                            <p class="roles-filter-count" id="visibleUserCount" aria-live="polite"></p>
                            <p class="roles-selection-count" id="selectedCount">0 usuarios seleccionados</p>
                        </div>
                        <hr class="form-divider">
                        <h2>Configuración de acceso</h2>
                        <div class="form-grid">
                            <div class="form-field"><label for="rol">Rol asignado</label>
                                <select id="rol" required disabled><option>Estudiante</option><option>Docente</option><option>Administrador</option></select></div>
                            <div class="form-field" id="levelField"><label for="nivel">Nivel educativo</label>
                                <select id="nivel" disabled><option>3.º medio</option><option>4.º medio</option></select></div>
                            <div class="form-field" id="courseField"><label for="curso">Curso</label>
                                <select id="curso" disabled></select></div>
                        </div>
                        <p class="security-note">Los cambios de roles y niveles afectan los permisos de acceso. Confirma la configuración antes de aplicarla.</p>
                        <div class="form-actions">
                            <button type="button" class="secondary-btn admin-clean-button" id="resetChanges" disabled>Restablecer</button>
                            <button type="submit" class="primary-btn" id="saveChanges" disabled>Guardar asignación</button>
                        </div>
                        <p class="form-message" id="rolesNotice" role="status" aria-live="polite"></p>
                    </form>
                </section>
                <aside class="panel account-summary">
                    <h2>Resumen de la asignación</h2>
                    <p class="form-description">Comprueba la configuración antes de confirmar.</p>
                    <div class="summary-highlight"><span id="summaryTargetLabel">Usuario seleccionado</span><strong id="summaryName">Ningún usuario seleccionado</strong></div>
                    <div class="summary-row"><span>Rol</span><strong id="summaryRole">—</strong></div>
                    <div class="summary-row"><span>Nivel educativo</span><strong id="summaryLevel">—</strong></div>
                    <div class="summary-row"><span>Curso</span><strong id="summaryCourse">—</strong></div>
                    <div class="summary-note"><strong>Permisos del perfil</strong><p id="permissionsText">Selecciona uno o más usuarios para consultar la configuración.</p></div>
                </aside>
            </div>
        </main>
    </div>
</div>

<!-- Ventana de confirmación -->
<div class="modal-overlay" id="rolesModal" hidden>
    <div class="modal-card"
         role="dialog"
         aria-modal="true"
         aria-labelledby="rolesModalTitle"
         aria-describedby="rolesModalDescription">

        <div class="modal-icon activate">✓</div>

        <h2 id="rolesModalTitle">Confirmar asignación</h2>

        <p id="rolesModalDescription">
            ¿Deseas aplicar esta configuración?
        </p>

        <div class="modal-actions">
            <button type="button" class="modal-cancel"
                    id="cancelRoles">
                Cancelar
            </button>

            <button type="button" class="modal-confirm"
                    id="confirmRoles">
                Confirmar cambios
            </button>
        </div>
    </div>
</div>

<script>
(() => {
 const $ = id => document.getElementById(id);
 const app=document.querySelector('.app');
 $('menuToggle').addEventListener('click',()=>{const hidden=app.classList.toggle('sidebar-hidden');$('menuToggle').setAttribute('aria-expanded',String(!hidden));$('menuToggle').setAttribute('aria-label',hidden?'Mostrar menú lateral':'Ocultar menú lateral');});
 // Registros de demostración; el backend sustituirá esta colección por usuarios reales.
 const users={
  '1':{name:'Camila Torres',role:'Estudiante',level:'4.º medio',course:'4.º A'},
  '2':{name:'Matías Rojas',role:'Estudiante',level:'3.º medio',course:'3.º B'},
  '3':{name:'Daniela Pérez',role:'Docente',level:'',course:''},
  '4':{name:'Felipe Muñoz',role:'Estudiante',level:'4.º medio',course:'4.º B'},
  '5':{name:'Andrea Silva',role:'Administrador',level:'',course:''}
 };
 const courses={'3.º medio':['3.º A','3.º B'],'4.º medio':['4.º A','4.º B']};
 const permissions={Estudiante:'Acceso a las funciones educativas de su nivel y curso.',Docente:'Acceso a la creación de casos, revisión de fichas y evaluaciones.',Administrador:'Acceso a la administración de usuarios y a todas las funciones docentes.'};
 let mode='individual',selected=new Set(),pending=null,previousFocus=null;
 const entries=Object.entries(users);
 for(const [id,u] of entries){
  const opt=document.createElement('option');opt.value=id;opt.textContent=u.name+' — '+u.role;$('usuario').appendChild(opt);
  const label=document.createElement('label');label.className='roles-user-row';label.dataset.search=(u.name+' '+u.role+' '+u.level+' '+u.course).toLocaleLowerCase('es');label.dataset.role=u.role;label.dataset.level=u.level;label.dataset.letter=(u.course.match(/\b([A-Z])$/)||[])[1]||'';
  const cb=document.createElement('input');cb.type='checkbox';cb.value=id;cb.className='user-check';cb.addEventListener('change',()=>{if(cb.checked)selected.add(id);else selected.delete(id);update();});
  const span=document.createElement('span');const strong=document.createElement('strong');strong.textContent=u.name;const small=document.createElement('small');small.textContent=[u.role,u.level,u.course].filter(Boolean).join(' · ');span.append(strong,small);label.append(cb,span);$('usersCheckboxes').append(label);
 }
 function populateCourses(value=''){const old=value||$('curso').value;$('curso').replaceChildren();for(const name of courses[$('nivel').value]||[]){const opt=document.createElement('option');opt.value=name;opt.textContent=name;$('curso').append(opt);}if(old&&courses[$('nivel').value].includes(old))$('curso').value=old;}
 function visibleChecks(){return [...document.querySelectorAll('.user-check')].filter(cb=>!cb.closest('.roles-user-row').hidden);}
 function update(){
  const ids=mode==='individual'?($('usuario').value?[$('usuario').value]:[]):[...selected];const enabled=ids.length>0;
  $('rol').disabled=!enabled;$('saveChanges').disabled=!enabled;$('resetChanges').disabled=!enabled;
  const student=$('rol').value==='Estudiante';$('levelField').hidden=!student;$('courseField').hidden=!student;$('nivel').disabled=!enabled||!student;$('curso').disabled=!enabled||!student;
  $('summaryTargetLabel').textContent=mode==='individual'?'Usuario seleccionado':'Usuarios seleccionados';
  $('summaryName').textContent=mode==='individual'?(users[ids[0]]?.name||'Ningún usuario seleccionado'):(ids.length?ids.length+' usuarios':'Ningún usuario seleccionado');
  $('summaryRole').textContent=enabled?$('rol').value:'—';$('summaryLevel').textContent=enabled?(student?$('nivel').value:'No aplica'):'—';$('summaryCourse').textContent=enabled?(student?$('curso').value:'No aplica'):'—';
  $('permissionsText').textContent=enabled?permissions[$('rol').value]:'Selecciona uno o más usuarios para consultar la configuración.';
  $('selectedCount').textContent=selected.size+' usuario'+(selected.size===1?'':'s')+' seleccionado'+(selected.size===1?'':'s');
  const visible=visibleChecks();$('selectAll').checked=visible.length>0&&visible.every(cb=>cb.checked);$('selectAll').indeterminate=visible.some(cb=>cb.checked)&&!$('selectAll').checked;
  $('saveChanges').textContent=mode==='individual'?'Guardar asignación':'Aplicar a seleccionados';
 }
 function loadIndividual(){const u=users[$('usuario').value];if(u){$('rol').value=u.role;$('nivel').value=u.level||'4.º medio';populateCourses(u.course);}update();}
 function switchMode(next){mode=next;$('individualArea').hidden=next!=='individual';$('multipleArea').hidden=next!=='multiple';$('modeIndividual').classList.toggle('active',next==='individual');$('modeMultiple').classList.toggle('active',next==='multiple');$('modeIndividual').setAttribute('aria-pressed',String(next==='individual'));$('modeMultiple').setAttribute('aria-pressed',String(next==='multiple'));$('rolesNotice').classList.remove('visible');if(next==='individual')loadIndividual();else update();}
 $('modeIndividual').addEventListener('click',()=>switchMode('individual'));$('modeMultiple').addEventListener('click',()=>switchMode('multiple'));
 $('usuario').addEventListener('change',loadIndividual);
 $('rol').addEventListener('change',update);
 $('nivel').addEventListener('change',()=>{populateCourses();update();});$('curso').addEventListener('change',update);
 function filterUsers(){
  const q=$('searchUsers').value.trim().toLocaleLowerCase('es');
  const role=$('filterRole').value,level=$('filterLevel').value,letter=$('filterLetter').value;
  let visible=0;
  for(const row of $('usersCheckboxes').children){
   const show=row.dataset.search.includes(q)&&(!role||row.dataset.role===role)&&(!level||row.dataset.level===level)&&(!letter||row.dataset.letter===letter);
   row.hidden=!show;if(show)visible++;
  }
  $('visibleUserCount').textContent='Mostrando '+visible+' de '+entries.length+' usuarios.';
  update();
 }
 ['searchUsers','filterRole','filterLevel','filterLetter'].forEach(id=>$(id).addEventListener(id==='searchUsers'?'input':'change',filterUsers));
 $('clearUserFilters').addEventListener('click',()=>{$('searchUsers').value='';$('filterRole').value='';$('filterLevel').value='';$('filterLetter').value='';filterUsers();});
 $('selectAll').addEventListener('change',e=>{for(const cb of visibleChecks()){cb.checked=e.target.checked;if(cb.checked)selected.add(cb.value);else selected.delete(cb.value);}update();});
 $('clearSelection').addEventListener('click',()=>{selected.clear();document.querySelectorAll('.user-check').forEach(cb=>cb.checked=false);update();});
 $('resetChanges').addEventListener('click',()=>{if(mode==='individual')loadIndividual();else{$('rol').value='Estudiante';$('nivel').value='4.º medio';populateCourses();update();}});
 function closeModal(){$('rolesModal').hidden=true;document.body.style.overflow='';if(previousFocus)previousFocus.focus();}
 $('rolesForm').addEventListener('submit',e=>{
  e.preventDefault();const ids=mode==='individual'?[$('usuario').value]:[...selected];if(!ids.length||ids.some(id=>!users[id]))return;
  pending={ids,role:$('rol').value,level:$('rol').value==='Estudiante'?$('nivel').value:'',course:$('rol').value==='Estudiante'?$('curso').value:''};
  const extra=pending.role==='Administrador'?' Atención: este rol concede permisos administrativos.':'';
  $('rolesModalDescription').textContent='¿Confirmas asignar el rol '+pending.role+(pending.level?' ('+pending.level+', '+pending.course+')':'')+' a '+ids.length+' usuario'+(ids.length===1?'':'s')+'?'+extra;
  previousFocus=document.activeElement;$('rolesModal').hidden=false;document.body.style.overflow='hidden';$('cancelRoles').focus();
 });
 $('cancelRoles').addEventListener('click',closeModal);
 $('confirmRoles').addEventListener('click',()=>{
  if(!pending)return;
  for(const id of pending.ids){Object.assign(users[id],{role:pending.role,level:pending.level,course:pending.course});}
  for(const [id,u] of entries){const opt=$('usuario').querySelector('option[value="'+id+'"]');if(opt)opt.textContent=u.name+' — '+u.role;const cb=document.querySelector('.user-check[value="'+id+'"]');if(cb){const small=cb.closest('label').querySelector('small');small.textContent=[u.role,u.level,u.course].filter(Boolean).join(' · ');cb.closest('label').dataset.search=(u.name+' '+u.role+' '+u.level+' '+u.course).toLocaleLowerCase('es');cb.closest('label').dataset.role=u.role;cb.closest('label').dataset.level=u.level;cb.closest('label').dataset.letter=(u.course.match(/\b([A-Z])$/)||[])[1]||'';}}
  const count=pending.ids.length;pending=null;closeModal();$('rolesNotice').textContent='Configuración aplicada a '+count+' usuario'+(count===1?'':'s')+' en esta vista.';$('rolesNotice').classList.add('visible');if(mode==='individual')loadIndividual();else update();
 });
 $('rolesModal').addEventListener('click',e=>{if(e.target===$('rolesModal'))closeModal();});
 document.addEventListener('keydown',e=>{if($('rolesModal').hidden)return;if(e.key==='Escape')closeModal();if(e.key==='Tab'){const first=$('cancelRoles'),last=$('confirmRoles');if(e.shiftKey&&document.activeElement===first){e.preventDefault();last.focus();}else if(!e.shiftKey&&document.activeElement===last){e.preventDefault();first.focus();}}});
 populateCourses();switchMode('individual');
 filterUsers();
})();
</script>
@include('shared.app-extras')
</body>
</html>
