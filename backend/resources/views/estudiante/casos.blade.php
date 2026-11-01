
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Mis casos clínicos | Ficha Clínica TP</title>

    <link rel="stylesheet"
          href="{{ asset('css/estudiante-casos.css') }}">
</head>

<body>
<div class="app">

    <!-- MENÚ LATERAL -->
    <aside class="sidebar" id="sidebar">

        <div class="brand">
            <div class="brand-logo">
                <img src="{{ asset('images/logo-tp.png') }}"
                     alt="Logotipo TP Enfermería">
            </div>

            <span>Ficha Clínica TP</span>
        </div>

        <div class="nav-title">PRINCIPAL</div>

        <a href="{{ route('preview.estudiante.casos') }}"
           class="nav-link active">
            Mis casos clínicos
        </a>

        <div class="nav-title">CUENTA</div>

        <a href="#" class="nav-link nav-disabled"
           aria-disabled="true">
            Mi perfil
        </a>

        <a href="#" class="nav-link nav-disabled"
           aria-disabled="true">
            Cerrar sesión
        </a>

    </aside>

    <!-- CONTENIDO -->
    <div class="main">

        <header class="topbar">

            <div class="topbar-left">

                <button type="button"
                        class="menu-toggle"
                        id="menuToggle"
                        aria-label="Ocultar menú lateral"
                        aria-expanded="true"
                        aria-controls="sidebar">

                    <span></span>
                    <span></span>
                    <span></span>

                </button>

                <strong>Mis casos clínicos</strong>

            </div>

            <div class="user-info">
                <div class="user-details">
                    <strong>Estudiante</strong>
                    <small>4.º medio</small>
                </div>

                <div class="user-avatar">E</div>
            </div>

        </header>

        <main class="content">

            <div class="page-heading">
                <div>
                    <h1>Mis casos clínicos</h1>

                    <p>
                        Consulta los casos y pacientes asignados,
                        revisa su información y accede a los
                        registros clínicos correspondientes.
                    </p>
                </div>
            </div>

            <!-- TARJETAS DE RESUMEN -->
            <section class="stats-grid"
                     aria-label="Resumen de casos">

                <article class="stat-card">
                    <span>Casos asignados</span>
                    <strong id="totalCases">0</strong>
                    <small>Total de casos disponibles</small>
                </article>

                <article class="stat-card">
                    <span>Disponibles</span>
                    <strong id="availableCases">0</strong>
                    <small>Pendientes de iniciar</small>
                </article>

                <article class="stat-card">
                    <span>En desarrollo</span>
                    <strong id="progressCases">0</strong>
                    <small>Actividades iniciadas</small>
                </article>

                <article class="stat-card">
                    <span>Finalizados</span>
                    <strong id="finishedCases">0</strong>
                    <small>Actividades completadas</small>
                </article>

            </section>

            <!-- TABLA DE CASOS -->
            <section class="cases-panel">

                <div class="panel-heading">
                    <div>
                        <h2>Casos asignados</h2>
                        <p>
                            Selecciona un caso para consultar
                            su información clínica.
                        </p>
                    </div>

                    <span class="results-count"
                          id="resultsCount">
                        0 casos
                    </span>
                </div>

                <!-- FILTROS -->
                <div class="filters">

                    <div class="filter-field search-field">
                        <label for="searchCases">
                            Buscar caso o paciente
                        </label>

                        <input type="search"
                               id="searchCases"
                               placeholder="ID, nombre del caso o paciente">
                    </div>

                    <div class="filter-field">
                        <label for="statusFilter">Estado</label>

                        <select id="statusFilter">
                            <option value="">Todos</option>
                            <option value="Disponible">Disponible</option>
                            <option value="En desarrollo">En desarrollo</option>
                            <option value="Finalizado">Finalizado</option>
                        </select>
                    </div>

                    <div class="filter-field">
                        <label for="esiFilter">Prioridad ESI</label>

                        <select id="esiFilter">
                            <option value="">Todas</option>
                            <option value="1">ESI 1</option>
                            <option value="2">ESI 2</option>
                            <option value="3">ESI 3</option>
                            <option value="4">ESI 4</option>
                            <option value="5">ESI 5</option>
                            <option value="Sin clasificar">
                                Sin clasificar
                            </option>
                        </select>
                    </div>

                    <button type="button"
                            class="clear-btn"
                            id="clearFilters">
                        Limpiar filtros
                    </button>

                </div>

                <div class="table-scroll">
                    <table class="cases-table">

                        <thead>
                            <tr>
                                <th>ID del caso</th>
                                <th>ESI</th>
                                <th>Nombre del caso</th>
                                <th>Paciente</th>
                                <th>Tipo de caso</th>
                                <th>Fecha de asignación</th>
                                <th>Última modificación</th>
                                <th>Estado</th>
                                <th>Logro / Nota</th>
                                <th>Acción</th>
                            </tr>
                        </thead>

                        <tbody id="casesTableBody"></tbody>

                    </table>
                </div>

                <div class="empty-state"
                     id="emptyState"
                     hidden>
                    No se encontraron casos con los filtros seleccionados.
                </div>

                <div class="table-footer">
                    <span id="tableInfo"></span>
                    <span>
                        Datos de demostración
                    </span>
                </div>

            </section>

        </main>

    </div>
</div>

<script>
    // Menú lateral
    const app = document.querySelector('.app');
    const menuToggle = document.getElementById('menuToggle');

    menuToggle.addEventListener('click', () => {
        const hidden = app.classList.toggle('sidebar-hidden');

        menuToggle.setAttribute(
            'aria-expanded',
            String(!hidden)
        );

        menuToggle.setAttribute(
            'aria-label',
            hidden
                ? 'Mostrar menú lateral'
                : 'Ocultar menú lateral'
        );
    });

    // Datos ficticios para probar la interfaz
    
    
    const cases = [
        {
            id: 'CAS-001',
            esi: 'Sin clasificar',
            name: 'Alteración neurológica aguda',
            patient: 'Masculino, 68 años',
            type: 'Examen',
            assigned: '27/10/2026 09:30',
            modified: '28/10/2026 11:15',
            status: 'En desarrollo',
            grade: null,
            achievement: null,
            description:
                'Paciente masculino de 68 años, con antecedentes de ' +
                'hipertensión arterial, diabetes mellitus tipo 2, ' +
                'dislipidemia y tabaquismo. Es trasladado al Servicio ' +
                'de Urgencias tras presentar inicio súbito de dificultad ' +
                'para hablar y pérdida de fuerza del lado derecho del cuerpo.'
        },
        {
            id: 'CAS-002',
            esi: 'Sin clasificar',
            name: 'Crisis convulsivas en Urgencias',
            patient: 'Femenino, 24 años',
            type: 'Tarea',
            assigned: '27/10/2026 10:00',
            modified: 'Sin modificaciones',
            status: 'Disponible',
            grade: null,
            achievement: null,
            description:
                'Paciente femenina de 24 años, sin diagnóstico previo ' +
                'de epilepsia, trasladada a Urgencias después de una ' +
                'crisis convulsiva en su lugar de trabajo. Durante la ' +
                'observación presenta una segunda crisis tónico-clónica ' +
                'generalizada que supera los cinco minutos.'
        },
        {
            id: 'CAS-003',
            esi: 'Sin clasificar',
            name: 'Dolor torácico agudo',
            patient: 'Masculino, 59 años',
            type: 'Examen',
            assigned: '25/10/2026 14:20',
            modified: '26/10/2026 16:45',
            status: 'Finalizado',
            grade: 6.5,
            achievement: 92,
            description:
                'Paciente masculino de 59 años, con antecedentes de ' +
                'hipertensión arterial, diabetes mellitus tipo 2, ' +
                'dislipidemia y tabaquismo. Consulta por dolor torácico ' +
                'opresivo intenso, irradiado al brazo izquierdo, hombro ' +
                'y mandíbula. El electrocardiograma presenta elevación ' +
                'del segmento ST en derivaciones precordiales anteriores.'
        },
        {
            id: 'CAS-004',
            esi: 'Sin clasificar',
            name: 'Hipoglicemia severa',
            patient: 'Femenino, 32 años',
            type: 'Apoyo',
            assigned: '28/10/2026 08:00',
            modified: 'Sin modificaciones',
            status: 'Disponible',
            grade: null,
            achievement: null,
            description:
                'Paciente femenina de 32 años, con diabetes mellitus ' +
                'tipo 1 en tratamiento con insulina. Es encontrada ' +
                'inconsciente en su domicilio y trasladada a Urgencias. ' +
                'Al ingreso presenta HGT de 40 mg/dl y Glasgow de ' +
                '5 puntos. Durante el manejo requiere controles ' +
                'seriados de glicemia por nuevos descensos.'
        },
        {
            id: 'CAS-005',
            esi: 'Sin clasificar',
            name: 'Politraumatismo por accidente de tránsito',
            patient: 'Masculino, 31 años',
            type: 'Tarea',
            assigned: '28/10/2026 12:30',
            modified: '29/10/2026 09:10',
            status: 'En desarrollo',
            grade: null,
            achievement: null,
            description:
                'Paciente masculino de 31 años, conductor involucrado ' +
                'en un accidente de tránsito de alta energía. Presenta ' +
                'dificultad respiratoria, herida sangrante en cuero ' +
                'cabelludo, dolor torácico y abdominal, deformidad ' +
                'del fémur derecho y múltiples abrasiones. El escenario ' +
                'requiere analizar ABCDE, estabilización y traslado.'
        }
    ];



    const tableBody = document.getElementById('casesTableBody');
    const searchInput = document.getElementById('searchCases');
    const statusFilter = document.getElementById('statusFilter');
    const esiFilter = document.getElementById('esiFilter');

    function makeCell(text) {
        const cell = document.createElement('td');
        cell.textContent = text;
        return cell;
    }

    function makeBadge(text, className) {
        const badge = document.createElement('span');
        badge.className = className;
        badge.textContent = text;
        return badge;
    }

    
    function renderCases() {
        const search = searchInput.value
            .toLocaleLowerCase('es')
            .trim();

        const status = statusFilter.value;
        const esi = esiFilter.value;

        const filtered = cases.filter(item => {
            const searchable = [
                item.id,
                item.name,
                item.patient
            ].join(' ').toLocaleLowerCase('es');

            return searchable.includes(search)
                && (!status || item.status === status)
                && (!esi || item.esi === esi);
        });

        tableBody.replaceChildren();

        filtered.forEach(item => {
            const row = document.createElement('tr');

            // ID del caso
            const idCell = makeCell(item.id);
            idCell.className = 'case-id';

            // Clasificación ESI
            const esiCell = document.createElement('td');

            esiCell.appendChild(
                makeBadge(
                    item.esi === 'Sin clasificar'
                        ? 'Sin clasificar'
                        : `ESI ${item.esi}`,
                    'esi-badge esi-' +
                    (item.esi === 'Sin clasificar'
                        ? 'pending'
                        : item.esi)
                )
            );

            // Nombre del caso
            const nameCell = makeCell(item.name);
            nameCell.className = 'case-name';

            // Nombre del paciente
            const patientCell = makeCell(item.patient);

            // Tipo de caso
            const typeCell = document.createElement('td');

            const typeClass = {
                'Examen': 'type-exam',
                'Tarea': 'type-task',
                'Apoyo': 'type-support'
            }[item.type] || 'type-support';

            typeCell.appendChild(
                makeBadge(
                    item.type,
                    'case-type ' + typeClass
                )
            );

            // Fecha de asignación
            const assignedCell = makeCell(item.assigned);
            assignedCell.className = 'date-cell';

            // Última modificación
            const modifiedCell = makeCell(item.modified);
            modifiedCell.className = 'date-cell';

            // Estado del caso
            const statusCell = document.createElement('td');

            const statusClass = {
                'Disponible': 'status-available',
                'En desarrollo': 'status-progress',
                'Finalizado': 'status-finished'
            }[item.status] || 'status-available';

            statusCell.appendChild(
                makeBadge(
                    item.status,
                    'status-badge ' + statusClass
                )
            );

            // Porcentaje de logro y nota
            const gradeCell = document.createElement('td');

            if (item.type === 'Apoyo') {

                gradeCell.textContent = 'No aplica';
                gradeCell.className = 'grade-pending';

            } else if (
                item.grade === null ||
                item.grade === undefined ||
                item.achievement === null ||
                item.achievement === undefined
            ) {

                gradeCell.textContent = 'Pendiente';
                gradeCell.className = 'grade-pending';

            } else {

                const gradeContainer = document.createElement('div');
                gradeContainer.className = 'grade-container';

                // Porcentaje de logro primero
                const achievementText = document.createElement('span');
                achievementText.className = 'evaluation-value';
                achievementText.textContent = `${item.achievement}%`;

                // Nota debajo, con el mismo estilo
                const gradeText = document.createElement('span');
                gradeText.className = 'evaluation-value';
                gradeText.textContent = Number(item.grade).toFixed(1);

                gradeContainer.append(
                    achievementText,
                    gradeText
                );

                gradeCell.appendChild(gradeContainer);
            }

            // Botón de acción
            const actionCell = document.createElement('td');

            const actionLink = document.createElement('a');
            actionLink.className = 'action-btn';

            actionLink.textContent = {
                'Disponible': 'Ver caso',
                'En desarrollo': 'Continuar',
                'Finalizado': 'Ver ficha'
            }[item.status] || 'Ver caso';

            // Todos pasan primero por el detalle del caso
            actionLink.href =
                '{{ route("preview.estudiante.caso", ["id" => "__ID__"]) }}'
                .replace('__ID__', encodeURIComponent(item.id));

            actionCell.appendChild(actionLink);

            // Incorporar las diez columnas en orden
            row.append(
                idCell,
                esiCell,
                nameCell,
                patientCell,
                typeCell,
                assignedCell,
                modifiedCell,
                statusCell,
                gradeCell,
                actionCell
            );

            tableBody.appendChild(row);
        });

        // Actualizar cantidad de resultados
        document.getElementById('resultsCount').textContent =
            `${filtered.length} casos`;

        document.getElementById('tableInfo').textContent =
            `Mostrando ${filtered.length} de ${cases.length} casos`;

        // Mostrar mensaje cuando no hay resultados
        document.getElementById('emptyState').hidden =
            filtered.length !== 0;
    }


    document.getElementById('totalCases').textContent =
        cases.length;

    document.getElementById('availableCases').textContent =
        cases.filter(item => item.status === 'Disponible').length;

    document.getElementById('progressCases').textContent =
        cases.filter(item => item.status === 'En desarrollo').length;

    document.getElementById('finishedCases').textContent =
        cases.filter(item => item.status === 'Finalizado').length;

    searchInput.addEventListener('input', renderCases);
    statusFilter.addEventListener('change', renderCases);
    esiFilter.addEventListener('change', renderCases);

    document.getElementById('clearFilters')
        .addEventListener('click', () => {
            searchInput.value = '';
            statusFilter.value = '';
            esiFilter.value = '';
            renderCases();
        });

    renderCases();
</script>

</body>
</html>
