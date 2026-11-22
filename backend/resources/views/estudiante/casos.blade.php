
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
        @include('shared.notifications-link')

        <a href="#" class="nav-link nav-disabled"
           aria-disabled="true">
            Mi perfil
        </a>

        <form action="{{ route('delivery.logout') }}" method="POST">@csrf<button type="submit" class="nav-link" style="width:100%;border:0;background:transparent;text-align:left;cursor:pointer">Cerrar sesión</button></form>

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
                    <strong>{{ auth()->user()?->name ?? 'Estudiante' }}</strong>
                    <small>{{ \App\Support\ThirdYear::isThirdYear(auth()->user()) ? '3.º medio' : '4.º medio' }}</small>
                </div>

                <div class="user-avatar">E</div>
            </div>

        </header>

        <main class="content">
            @if(session('error'))
                <p role="alert" style="padding:12px;background:#fff1f2;color:#9f1239;border-radius:8px">{{ session('error') }}</p>
            @endif

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
                            <option value="Finalizado">Entregado</option>
                            <option value="Tiempo agotado">Tiempo agotado</option>
                            <option value="Programada">Programada</option>
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
                        Información de tus casos asignados
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
    
    
    const cases = @json($casosPayload ?? []);

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
                'Finalizado': 'status-finished',
                'Tiempo agotado': 'status-finished',
                'Programada': 'status-available'
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
                'Finalizado': 'Ver ficha',
                'Tiempo agotado': 'Ver ficha',
                'Programada': 'Ver caso'
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

@include('shared.app-extras')
</body>
</html>
