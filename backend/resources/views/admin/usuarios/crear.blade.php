
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear usuario | Ficha Clínica TP</title>
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
        <a href="{{ route('preview.admin.usuarios') }}" class="nav-link">
            Gestión de usuarios
        </a>
        <a href="{{ route('preview.admin.crear') }}" class="nav-link active">
            Crear usuario
        </a>
        <a href="{{ route('preview.admin.roles') }}" class="nav-link">Asignar roles y niveles</a>
        <a href="{{ route('preview.admin.historial') }}" class="nav-link">Historial de actividad</a>

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
                <button
                    type="button"
                    class="menu-toggle"
                    id="menuToggle"
                    aria-label="Ocultar menú lateral"
                    aria-expanded="true"
                    aria-controls="sidebar"
                >
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <strong>Crear usuario</strong>
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
                    <h1>Nuevo usuario de la plataforma</h1>
                    <p class="subtitle">
                        Registra una cuenta y asigna su perfil de acceso
                        al sistema educativo.
                    </p>
                </div>
                <a class="primary-btn" href="{{ route('preview.admin.usuarios') }}">
                    ← Volver a usuarios
                </a>
            </div>

            <div class="create-tabs" role="tablist" aria-label="Modalidad de creación">
                <button type="button" class="create-tab active" id="singleTab" role="tab" aria-selected="true" aria-controls="singlePanel">Creación individual</button>
                <button type="button" class="create-tab" id="importTab" role="tab" aria-selected="false" aria-controls="importPanel">Importación masiva</button>
            </div>
            <div id="singlePanel" role="tabpanel" aria-labelledby="singleTab">
            <div class="create-layout">
                <section class="panel">
                    <form id="createUserForm">
                        <h2>Información personal</h2>
                        <p class="form-description">
                            Completa los datos de identificación y contacto.
                        </p>

                        <div class="form-grid">
                            <div class="form-field">
                                <label for="nombres">Nombres</label>
                                <input id="nombres" name="nombres"
                                       placeholder="Ej.: Camila Fernanda"
                                       maxlength="100" required>
                            </div>

                            <div class="form-field">
                                <label for="apellidos">Apellidos</label>
                                <input id="apellidos" name="apellidos"
                                       placeholder="Ej.: Muñoz Pérez"
                                       maxlength="100" required>
                            </div>

                            <div class="form-field">
                                <label for="rut">RUT</label>
                                <input id="rut" name="rut"
                                       placeholder="Ej.: 12.345.678-9"
                                       maxlength="12" required>
                            </div>

                            <div class="form-field">
                                <label for="correo">Correo electrónico</label>
                                <input id="correo" name="correo" type="email"
                                       placeholder="usuario@liceobicentenariohsb.cl"
                                       required>
                            </div>
                        </div>

                        <hr class="form-divider">

                        <h2>Acceso y perfil</h2>

                        <div class="form-grid">
                            <div class="form-field">
                                <label for="rol">Rol</label>
                                <select id="rol" name="rol" required>
                                    <option value="Estudiante">Estudiante</option>
                                    <option value="Docente">Docente</option>
                                    <option value="Administrador">Administrador</option>
                                </select>
                            </div>

                            <div class="form-field" id="levelField">
                                <label for="nivel">Nivel educativo</label>
                                <select id="nivel" name="nivel">
                                    <option value="3.º medio">3.º medio</option>
                                    <option value="4.º medio" selected>4.º medio</option>
                                </select>
                            </div>

                            <div class="form-field" id="courseField">
                                <label for="curso">Curso</label>
                                <select id="curso" name="curso">
                                    <option value="4.º medio A">4.º medio A</option>
                                    <option value="4.º medio B">4.º medio B</option>
                                </select>
                            </div>

                            <div class="form-field">
                                <label for="estado">Estado de la cuenta</label>
                                <select id="estado" name="estado">
                                    <option value="Activo">Activo</option>
                                    <option value="Inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>

                        <p class="security-note">
                            La contraseña inicial y su entrega deben seguir
                            la política de seguridad definida.
                        </p>

                        <div id="formMessage" class="form-message"
                             role="status" aria-live="polite"></div>

                        <div class="form-actions">
                            <a href="{{ route('preview.admin.usuarios') }}"
                               class="secondary-btn">Cancelar</a>
                            <button type="submit" class="primary-btn">
                                Crear usuario
                            </button>
                        </div>
                    </form>
                </section>

                <aside class="panel account-summary">
                    <h2>Resumen de la cuenta</h2>
                    <p class="form-description">
                        Revisa los permisos antes de crearla.
                    </p>

                    <div class="summary-highlight">
                        <span>Rol seleccionado</span>
                        <strong id="summaryRole">Estudiante</strong>
                    </div>

                    <div class="summary-row">
                        <span>Nivel</span>
                        <strong id="summaryLevel">4.º medio</strong>
                    </div>
                    <div class="summary-row">
                        <span>Curso</span>
                        <strong id="summaryCourse">4.º medio A</strong>
                    </div>
                    <div class="summary-row">
                        <span>Estado</span>
                        <strong id="summaryStatus">Activo</strong>
                    </div>

                    <div class="summary-note">
                        <strong>Consideraciones</strong>
                        <p>
                            El nivel y el curso corresponden al perfil
                            estudiantil. Para otros roles, estos campos
                            pueden no aplicar.
                        </p>
                    </div>
                </aside>
            </div>
            </div>
            <section class="panel" id="importPanel" role="tabpanel" aria-labelledby="importTab" hidden>
                <h2>Importación masiva de usuarios</h2>
                <p class="form-description">Prepara una nómina de usuarios y revisa los datos antes de su incorporación al sistema.</p>
                <div class="import-zone">
                    <h3>Cargar nómina CSV</h3>
                    <p>Exporta tu archivo Excel como CSV UTF-8 para visualizarlo aquí.</p>
                    <input type="file" id="importFile" accept=".csv,text/csv" aria-label="Seleccionar archivo CSV">
                    <div class="import-actions" style="justify-content:center">
                        <button type="button" class="secondary-btn" id="downloadTemplate">Descargar plantilla CSV</button>
                        <button type="button" class="secondary-btn admin-clean-button" id="clearImport">Limpiar vista previa</button>
                    </div>
                </div>
                <div class="import-note">Esta sección permite preparar y revisar la importación. La creación masiva de cuentas quedará deshabilitada hasta su validación con el liceo y la integración con Laravel.</div>
                <h3 style="margin-top:24px">Vista previa de usuarios</h3>
                <p id="importSummary" class="form-description" aria-live="polite">Selecciona un archivo CSV para visualizar los registros.</p>
                <div class="import-table-wrap"><table class="import-table"><thead><tr><th>Nombre</th><th>RUT</th><th>Correo</th><th>Rol</th><th>Curso</th><th>Validación</th></tr></thead><tbody id="importRows"><tr><td colspan="6">Sin registros cargados.</td></tr></tbody></table></div>
                <div class="import-actions"><button type="button" class="primary-btn" disabled title="Disponible después de validación e integración">Confirmar importación</button></div>
            </section>
        </main>
    </div>
</div>

<script>
    const singleTab = document.getElementById('singleTab');
    const importTab = document.getElementById('importTab');
    function switchCreationTab(importMode) {
        document.getElementById('singlePanel').hidden = importMode;
        document.getElementById('importPanel').hidden = !importMode;
        singleTab.classList.toggle('active', !importMode);
        importTab.classList.toggle('active', importMode);
        singleTab.setAttribute('aria-selected', String(!importMode));
        importTab.setAttribute('aria-selected', String(importMode));
    }
    singleTab.addEventListener('click', () => switchCreationTab(false));
    importTab.addEventListener('click', () => switchCreationTab(true));

    const importRows = document.getElementById('importRows');
    const importSummary = document.getElementById('importSummary');
    const escapeHtml = value => String(value).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
    function parseCSV(input) {
        const rows=[]; let row=[], field='', quoted=false;
        for(let i=0;i<input.length;i++) {
            const c=input[i];
            if(c==='"') { if(quoted && input[i+1]==='"'){field+='"';i++;} else quoted=!quoted; }
            else if(c===',' && !quoted){row.push(field);field='';}
            else if((c==='\n'||c==='\r') && !quoted){if(c==='\r' && input[i+1]==='\n')i++;row.push(field);if(row.some(v=>v.trim()))rows.push(row);row=[];field='';}
            else field+=c;
        }
        if(quoted) throw new Error('Archivo CSV con comillas sin cerrar.');
        row.push(field);if(row.some(v=>v.trim()))rows.push(row);
        return rows;
    }
    function clearImport() {
        document.getElementById('importFile').value='';
        importRows.innerHTML='<tr><td colspan="6">Sin registros cargados.</td></tr>';
        importSummary.textContent='Selecciona un archivo CSV para visualizar los registros.';
    }
    document.getElementById('clearImport').addEventListener('click', clearImport);
    document.getElementById('downloadTemplate').addEventListener('click', () => {
        const headers='nombres,apellidos,rut,correo,rol,nivel,curso,jornada,estado\r\n';
        const blob=new Blob(['\uFEFF'+headers],{type:'text/csv;charset=utf-8'});
        const url=URL.createObjectURL(blob);const a=document.createElement('a');a.href=url;a.download='plantilla-usuarios.csv';a.click();URL.revokeObjectURL(url);
    });
    document.getElementById('importFile').addEventListener('change', async event => {
        const file=event.target.files[0];if(!file){clearImport();return;}
        if(!file.name.toLowerCase().endsWith('.csv')){importSummary.textContent='Selecciona un archivo CSV.';return;}
        if(file.size>2*1024*1024){importSummary.textContent='El archivo supera el límite de vista previa de 2 MB.';return;}
        try {
            const rows=parseCSV((await file.text()).replace(/^\uFEFF/,''));
            if(rows.length<2)throw new Error('El archivo no contiene usuarios.');
            const headers=rows.shift().map(v=>v.trim().toLowerCase());
            const required=['nombres','apellidos','rut','correo','rol','nivel','curso'];
            if(required.some(h=>!headers.includes(h)))throw new Error('Faltan columnas obligatorias: '+required.filter(h=>!headers.includes(h)).join(', '));
            const seen=new Set();let valid=0;importRows.replaceChildren();
            rows.slice(0,500).forEach(values=>{
                const data=Object.fromEntries(headers.map((h,i)=>[h,(values[i]||'').trim()]));
                const key=data.rut.toLowerCase();
                const student=data.rol==='Estudiante';
                const issues=[];
                if(!data.nombres||!data.apellidos||!data.rut||!data.correo)issues.push('Datos incompletos');
                if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(data.correo))issues.push('Correo inválido');
                if(!['Estudiante','Docente','Administrador'].includes(data.rol))issues.push('Rol inválido');
                if(student && (!['3.º medio','4.º medio'].includes(data.nivel)||!/^([34])\.º medio [A-Z]$/.test(data.curso)||!data.curso.startsWith(data.nivel)))issues.push('Nivel/curso inválido');
                if(seen.has(key))issues.push('RUT repetido en archivo');seen.add(key);
                if(!issues.length)valid++;
                const tr=document.createElement('tr');
                [data.nombres+' '+data.apellidos,data.rut,data.correo,data.rol,student?data.curso:'No aplica',issues.length?issues.join('; '):'Válido'].forEach((value,i)=>{
                    const td=document.createElement('td');td.textContent=value;if(i===5)td.className=issues.length?'import-error':'import-ok';tr.appendChild(td);
                });importRows.appendChild(tr);
            });
            importSummary.textContent=`${Math.min(rows.length,500)} registros revisados · ${valid} válidos · ${Math.min(rows.length,500)-valid} con observaciones`+(rows.length>500?' · Se muestran los primeros 500 registros.':'')+'. Validación preliminar, sin consultar la base de datos.';
        }catch(error){importRows.innerHTML='<tr><td colspan="6">No se pudo generar la vista previa.</td></tr>';importSummary.textContent=error.message;}
    });
    const app = document.querySelector('.app');
    const menuToggle = document.getElementById('menuToggle');

    menuToggle.addEventListener('click', () => {
        const hidden = app.classList.toggle('sidebar-hidden');
        menuToggle.setAttribute('aria-expanded', String(!hidden));
        menuToggle.setAttribute(
            'aria-label',
            hidden ? 'Mostrar menú lateral' : 'Ocultar menú lateral'
        );
    });

    const rol = document.getElementById('rol');
    const nivel = document.getElementById('nivel');
    const curso = document.getElementById('curso');
    const estado = document.getElementById('estado');

    const courses = {
        '3.º medio': ['3.º medio A', '3.º medio B'],
        '4.º medio': ['4.º medio A', '4.º medio B']
    };

    function updateForm() {
        const isStudent = rol.value === 'Estudiante';

        document.getElementById('levelField').hidden = !isStudent;
        document.getElementById('courseField').hidden = !isStudent;

        nivel.disabled = !isStudent;
        curso.disabled = !isStudent;

        document.getElementById('summaryRole').textContent = rol.value;
        document.getElementById('summaryLevel').textContent =
            isStudent ? nivel.value : 'No aplica';
        document.getElementById('summaryCourse').textContent =
            isStudent ? curso.value : 'No aplica';
        document.getElementById('summaryStatus').textContent = estado.value;
    }

    nivel.addEventListener('change', () => {
        curso.replaceChildren();

        courses[nivel.value].forEach(name => {
            const option = document.createElement('option');
            option.value = name;
            option.textContent = name;
            curso.appendChild(option);
        });

        updateForm();
    });

    [rol, curso, estado].forEach(element => {
        element.addEventListener('change', updateForm);
    });

    updateForm();

    document.getElementById('createUserForm')
        .addEventListener('submit', event => {
            event.preventDefault();

            const message = document.getElementById('formMessage');
            message.textContent =
                'Formulario validado visualmente. La creación real del usuario ' +
                'se habilitará al conectar el backend.';
            message.classList.add('visible');
        });
</script>
</body>
</html>
