<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Evaluación ABCDE | Ficha Clínica TP</title>
    <link rel="stylesheet" href="{{ asset('css/estudiante-admision.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estudiante-triage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estudiante-formularios.css') }}">
    <style>
        .abcde-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 300px;
            gap: 18px;
            align-items: start
        }

        .abcde-sections {
            display: grid;
            gap: 16px;
            min-width: 0
        }

        .abcde-card,
        .abcde-aside {
            background: #fff;
            border: 1px solid #dce5ef;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 3px 14px rgba(15, 42, 76, .045)
        }

        .abcde-head {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            color: #fff
        }

        .abcde-letter {
            display: grid;
            place-items: center;
            width: 39px;
            height: 39px;
            flex-shrink: 0;
            border: 1px solid #ffffff80;
            border-radius: 10px;
            font-size: 22px;
            font-weight: 800
        }

        .abcde-head h2 {
            margin: 0;
            color: inherit;
            font-size: 17px
        }

        .abcde-head p {
            margin: 3px 0 0;
            color: #ffffffdc;
            font-size: 12px
        }

        .abcde-a {
            background: #c73838
        }

        .abcde-b {
            background: #1766aa
        }

        .abcde-c {
            background: #15805b
        }

        .abcde-d {
            background: #c28a12
        }

        .abcde-e {
            background: #7548a8
        }

        .abcde-body {
            padding: 18px
        }

        .abcde-fields {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px
        }

        .abcde-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
            min-width: 0
        }

        .abcde-field.full {
            grid-column: 1/-1
        }

        .abcde-field label,
        .abcde-field .field-label {
            font-weight: 650;
            font-size: 13px;
            color: #25354b
        }

        .abcde-field select,
        .abcde-field input,
        .abcde-field textarea {
            width: 100%;
            min-width: 0;
            min-height: 41px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #fff;
            color: #172a41;
            padding: 9px 11px;
            font: inherit;
            font-size: 13px
        }

        .abcde-field textarea {
            min-height: 100px;
            resize: vertical
        }

        .abcde-field input:focus,
        .abcde-field select:focus,
        .abcde-field textarea:focus {
            outline: 2px solid #93c5fd;
            outline-offset: 1px
        }

        .abcde-field small {
            color: #65758a;
            font-size: 11px
        }

        .abcde-checks {
            display: flex;
            flex-wrap: wrap;
            gap: 10px 17px;
            padding: 8px 0
        }

        .abcde-checks label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 450;
            font-size: 13px
        }

        .abcde-checks input {
            width: 16px;
            min-height: auto;
            height: 16px;
            accent-color: #0b65b9
        }

        .abcde-total {
            border-radius: 10px;
            background: #f1f6ff;
            border: 1px solid #cfe0fb;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px
        }

        .abcde-total strong {
            font-size: 26px;
            color: #164c92
        }

        .abcde-total small {
            display: block;
            font-size: 12px;
            color: #51647b
        }

        .abcde-aside {
            position: sticky;
            top: 16px;
            padding: 18px
        }

        .abcde-aside h3 {
            margin: 0 0 12px;
            font-size: 17px
        }

        .abcde-aside p {
            font-size: 13px;
            color: #52647b;
            line-height: 1.5
        }

        .abcde-progress {
            display: grid;
            gap: 10px;
            margin: 16px 0
        }

        .abcde-progress div {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #edf1f5;
            padding: 6px 0;
            font-size: 13px
        }

        .abcde-progress b {
            color: #145ba0
        }

        .abcde-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 16px
        }

        .abcde-btn {
            border: 1px solid #cbd5e1;
            border-radius: 9px;
            background: #eef3f8;
            color: #243950;
            padding: 11px 16px;
            font-weight: 650;
            cursor: pointer
        }

        .abcde-btn.primary {
            background: #0968bd;
            color: #fff;
            border-color: #0968bd
        }

        .abcde-btn:focus-visible {
            outline: 3px solid #8dbdf5
        }

        .abcde-message {
            padding: 12px 14px;
            border-radius: 9px;
            margin: 12px 0;
            display: none;
            font-size: 13px
        }

        .abcde-message.show {
            display: block
        }

        .abcde-message.ok {
            background: #e8f7ee;
            color: #14633a
        }

        .abcde-message.error {
            background: #fff0ed;
            color: #a32b25
        }

        .abcde-history {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px
        }

        .abcde-history th,
        .abcde-history td {
            padding: 10px 8px;
            border-bottom: 1px solid #e7edf3;
            text-align: left
        }

        .abcde-history th {
            background: #f1f6fb;
            color: #264665
        }

        .abcde-history button {
            border: 0;
            background: transparent;
            color: #0968bd;
            cursor: pointer;
            text-decoration: underline
        }

        .abcde-scroll {
            overflow-x: auto
        }

        .abcde-muted {
            font-size: 12px;
            color: #64748b
        }

        .abcde-note {
            padding: 12px;
            background: #f3f7fc;
            border-radius: 9px;
            font-size: 12px;
            color: #405570
        }

        .abcde-hidden {
            display: none !important
        }

        @media(max-width:1050px) {
            .abcde-layout {
                grid-template-columns: 1fr
            }

            .abcde-aside {
                position: static
            }
        }

        @media(max-width:600px) {
            .abcde-fields {
                grid-template-columns: 1fr
            }

            .abcde-body {
                padding: 13px
            }

            .abcde-head {
                padding: 12px
            }

            .abcde-actions {
                justify-content: stretch
            }

            .abcde-actions button {
                flex: 1
            }
        }

        /* Distribución ABCDE en cinco columnas como el prototipo validado */
        .abcde-layout {
            display: block;
            margin-top: 16px
        }

        .abcde-sections {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 7px;
            align-items: stretch
        }

        .abcde-card {
            min-width: 0;
            border-radius: 7px
        }

        .abcde-sections>.abcde-card {
            height: 100%
        }

        .abcde-head {
            min-height: 51px;
            padding: 10px 8px;
            gap: 6px;
            align-items: center
        }

        .abcde-letter {
            width: 24px;
            height: 27px;
            border: 0;
            border-radius: 4px;
            font-size: 16px
        }

        .abcde-head h2 {
            font-size: 12px;
            line-height: 1.25
        }

        .abcde-head p {
            display: none
        }

        .abcde-body {
            padding: 10px 8px
        }

        .abcde-fields {
            display: flex;
            flex-direction: column;
            gap: 12px
        }

        .abcde-field {
            gap: 5px
        }

        .abcde-field label,
        .abcde-field .field-label {
            font-size: 11px;
            line-height: 1.35
        }

        .abcde-field select,
        .abcde-field input,
        .abcde-field textarea {
            min-height: 33px;
            padding: 6px;
            font-size: 11px;
            border-radius: 5px
        }

        .abcde-field small {
            font-size: 10px
        }

        .abcde-checks {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 7px;
            padding: 5px 0
        }

        .abcde-checks label {
            font-size: 11px
        }

        .abcde-total {
            padding: 9px;
            gap: 4px;
            flex-wrap: wrap
        }

        .abcde-total strong {
            font-size: 18px
        }

        .abcde-total small {
            font-size: 10px
        }

        .abcde-bottom {
            display: grid;
            gap: 14px;
            margin-top: 14px
        }

        .abcde-bottom>.abcde-card:first-child .abcde-body {
            padding: 16px 20px
        }

        .abcde-bottom>.abcde-card:first-child .abcde-field label {
            font-size: 13px
        }

        .abcde-bottom>.abcde-card:first-child textarea {
            min-height: 72px;
            font-size: 13px
        }

        .abcde-aside {
            position: static;
            margin-top: 14px;
            padding: 14px 18px
        }

        .abcde-progress {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 10px
        }

        .abcde-progress div {
            display: flex;
            gap: 6px;
            flex-wrap: wrap
        }

        @media(max-width:1150px) {
            .abcde-sections {
                overflow-x: auto;
                grid-template-columns: repeat(5, minmax(205px, 1fr));
                padding-bottom: 8px
            }

            .abcde-progress {
                grid-template-columns: repeat(3, minmax(0, 1fr))
            }
        }

        @media(max-width:600px) {
            .abcde-progress {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }

            .abcde-bottom>.abcde-card:first-child .abcde-body {
                padding: 13px
            }
        }
    </style>
</head>

<body>
    <div class="app app-shell" id="appShell">
        <aside class="sidebar" id="sidebar">
            <div class="brand">
                <div class="brand-logo"><img src="{{ asset('images/logo-tp.png') }}" alt="Logo TP"></div><span>Ficha
                    Clínica TP</span>
            </div>
            <nav aria-label="Navegación principal">
                <div class="nav-title">Principal</div><a class="nav-link"
                    href="{{ route('preview.estudiante.casos') }}">Mis casos clínicos</a>
                <div class="nav-title">Atención clínica</div><a class="nav-link" id="sidebarFicha" href="#">Ficha
                    clínica</a><a class="nav-link" id="sidebarAdmision"
                    href="{{ route('preview.estudiante.admision') }}">Admisión</a><a class="nav-link" id="sidebarTriage"
                    href="{{ route('preview.estudiante.triage') }}">Triage</a><a class="nav-link" id="sidebarSignos"
                    href="{{ route('preview.estudiante.signos-vitales') }}">Signos vitales</a><a class="nav-link active"
                    id="sidebarABCDE" href="{{ route('preview.estudiante.abcde') }}" aria-current="page">Evaluación
                    clínica</a>
                <div class="nav-title">Cuenta</div><a class="nav-link nav-disabled" href="#">Mi perfil</a><a
                    class="nav-link" href="{{ url('/login') }}">Cerrar sesión</a>
            </nav>
        </aside>
        <div class="main-area">
            <header class="topbar">
                <div class="topbar-left"><button type="button" class="menu-toggle" id="menuToggle"
                        aria-label="Mostrar u ocultar menú" aria-controls="sidebar"
                        aria-expanded="true"><span></span><span></span><span></span></button><span
                        class="topbar-title">Evaluación ABCDE</span></div>
                <div class="user-info">
                    <div class="user-details"><strong>Estudiante</strong><small>4.º medio</small></div>
                    <div class="user-avatar">E</div>
                </div>
            </header>
            <main class="content">
                <div class="page-heading">
                    <div>
                        <p class="eyebrow">FICHA CLÍNICA EDUCATIVA</p>
                        <h1>Evaluación inicial ABCDE</h1>
                        <p class="muted">Valoración sistemática del paciente simulado.</p>
                    </div><a class="btn btn-secondary" id="backToCase" href="{{ route('preview.estudiante.casos') }}">←
                        Volver al caso</a>
                </div>
                <div class="case-banner">
                    <div><span class="case-code" id="caseCode">CAS-001</span><strong id="caseTitle">Caso
                            clínico</strong><small>Paciente simulado · Evaluación ABCDE</small></div><span
                        class="case-status">En desarrollo</span>
                </div>
                <nav class="tabs" aria-label="Etapas de ficha clínica"><a id="tabResumen" href="#">Resumen</a><a
                        id="tabAdmision" href="{{ route('preview.estudiante.admision') }}">Admisión</a><a
                        id="tabTriage" href="{{ route('preview.estudiante.triage') }}">Triage</a><a id="tabSignos"
                        href="{{ route('preview.estudiante.signos-vitales') }}">Signos vitales</a><a class="selected"
                        id="tabABCDE" href="{{ route('preview.estudiante.abcde') }}"
                        aria-current="page">ABCDE</a><span>Anamnesis</span><span>Examen
                        físico</span><span>Procedimientos</span><span>Medicamentos</span><span>Exámenes</span><span>Acciones
                        de enfermería</span><span>Destino</span></nav>
                <form id="abcdeForm" novalidate>
                    <div class="abcde-layout">
                        <div class="abcde-sections">
                            <section class="abcde-card" aria-labelledby="headA">
                                <div class="abcde-head abcde-a"><span class="abcde-letter">A</span>
                                    <div>
                                        <h2 id="headA">Vía aérea y control cervical</h2>
                                        <p>Airway · Evaluación de permeabilidad y protección cervical</p>
                                    </div>
                                </div>
                                <div class="abcde-body">
                                    <div class="abcde-fields">
                                        <div class="abcde-field"><label for="obstruccion">Obstrucción de vía aérea
                                                *</label><select id="obstruccion" name="obstruccion" required>
                                                <option value="">Seleccionar</option>
                                                <option>No presenta</option>
                                                <option>Parcial</option>
                                                <option>Total</option>
                                                <option>No evaluable</option>
                                            </select></div>
                                        <div class="abcde-field"><label for="collar">Control cervical / collar
                                                cervical *</label><select id="collar" name="collar" required>
                                                <option value="">Seleccionar</option>
                                                <option>No indicado</option>
                                                <option>Indicado, no colocado</option>
                                                <option>Colocado</option>
                                                <option>No evaluable</option>
                                            </select></div>
                                        <div class="abcde-field"><label for="aspiracion">Aspiración de secreciones o
                                                cuerpos extraños *</label><select id="aspiracion" name="aspiracion"
                                                required>
                                                <option value="">Seleccionar</option>
                                                <option>No requerida</option>
                                                <option>Requerida, no realizada</option>
                                                <option>Realizada</option>
                                            </select></div>
                                        <div class="abcde-field"><label for="intubacion">Manejo avanzado de vía aérea
                                                *</label><select id="intubacion" name="intubacion" required>
                                                <option value="">Seleccionar</option>
                                                <option>No requerido</option>
                                                <option>Intubación orotraqueal</option>
                                                <option>Intubación nasotraqueal</option>
                                                <option>Dispositivo supraglótico</option>
                                                <option>Otro</option>
                                            </select></div>
                                    </div>
                                </div>
                            </section>
                            <section class="abcde-card" aria-labelledby="headB">
                                <div class="abcde-head abcde-b"><span class="abcde-letter">B</span>
                                    <div>
                                        <h2 id="headB">Ventilación</h2>
                                        <p>Breathing · Evaluación ventilatoria y oxigenación</p>
                                    </div>
                                </div>
                                <div class="abcde-body">
                                    <div class="abcde-fields">
                                        <div class="abcde-field"><label for="murmullo">Murmullo pulmonar
                                                *</label><select id="murmullo" name="murmullo" required>
                                                <option value="">Seleccionar</option>
                                                <option>Conservado bilateral</option>
                                                <option>Disminuido unilateral</option>
                                                <option>Disminuido bilateral</option>
                                                <option>Ausente unilateral</option>
                                                <option>Ausente bilateral</option>
                                                <option>No evaluable</option>
                                            </select></div>
                                        <div class="abcde-field"><label for="simetria">Expansión / simetría torácica
                                                *</label><select id="simetria" name="simetria" required>
                                                <option value="">Seleccionar</option>
                                                <option>Simétrica</option>
                                                <option>Asimétrica</option>
                                                <option>No evaluable</option>
                                            </select></div>
                                        <div class="abcde-field"><label for="ventilacion">Tipo de ventilación
                                                *</label><select id="ventilacion" name="ventilacion" required>
                                                <option value="">Seleccionar</option>
                                                <option>Espontánea</option>
                                                <option>Asistida</option>
                                                <option>Mecánica invasiva</option>
                                                <option>Mecánica no invasiva</option>
                                                <option>Apnea</option>
                                                <option>No evaluable</option>
                                            </select></div>
                                        <div class="abcde-field"><label for="oxigenoterapia">Oxigenoterapia
                                                *</label><select id="oxigenoterapia" name="oxigenoterapia" required>
                                                <option value="">Seleccionar</option>
                                                <option value="sin">Sin oxígeno suplementario</option>
                                                <option value="canula">Cánula nasal</option>
                                                <option>Mascarilla simple</option>
                                                <option>Mascarilla con reservorio</option>
                                                <option>Mascarilla Venturi</option>
                                                <option value="otro">Otro dispositivo</option>
                                            </select></div>
                                        <div class="abcde-field" id="flujoWrap"><label for="flujo">Flujo de
                                                oxígeno (L/min)</label><input id="flujo" name="flujo"
                                                type="number" min="0" max="60" step="0.1"
                                                placeholder="Ej.: 2"><small>Registrar según el dispositivo utilizado en
                                                la simulación.</small></div>
                                        <div class="abcde-field abcde-hidden" id="otroOxigenoWrap">
                                            <label for="otroOxigeno">Especificar otro dispositivo *</label>
                                            <input id="otroOxigeno" name="otroOxigeno" type="text" maxlength="200"
                                                placeholder="Indica el dispositivo utilizado">
                                        </div>
                                        <div class="abcde-field"><label for="esfuerzo">Trabajo respiratorio
                                                *</label><select id="esfuerzo" name="esfuerzo" required>
                                                <option value="">Seleccionar</option>
                                                <option>Sin signos de dificultad</option>
                                                <option>Uso de musculatura accesoria</option>
                                                <option>Tiraje</option>
                                                <option>Respiración paradójica</option>
                                                <option>No evaluable</option>
                                            </select></div>
                                    </div>
                                </div>
                            </section>
                            <section class="abcde-card" aria-labelledby="headC">
                                <div class="abcde-head abcde-c"><span class="abcde-letter">C</span>
                                    <div>
                                        <h2 id="headC">Circulación</h2>
                                        <p>Circulation · Pulso, perfusión y hemorragias</p>
                                    </div>
                                </div>
                                <div class="abcde-body">
                                    <div class="abcde-fields">
                                        <div class="abcde-field"><label for="pulso">Frecuencia del pulso
                                                (lpm)</label><input id="pulso" name="pulso" type="number"
                                                min="20" max="250" step="1" placeholder="Ej.: 112">
                                        </div>
                                        <div class="abcde-field"><label for="ritmo">Ritmo cardíaco *</label><select
                                                id="ritmo" name="ritmo" required>
                                                <option value="">Seleccionar</option>
                                                <option>Regular</option>
                                                <option>Irregular</option>
                                                <option>No evaluable</option>
                                            </select></div>
                                        <div class="abcde-field"><label for="calidad">Calidad del pulso
                                                *</label><select id="calidad" name="calidad" required>
                                                <option value="">Seleccionar</option>
                                                <option>Normal / palpable</option>
                                                <option>Débil / filiforme</option>
                                                <option>Amplio / saltón</option>
                                                <option>Ausente</option>
                                                <option>No evaluable</option>
                                            </select></div>
                                        <div class="abcde-field"><label for="hemorragia">Hemorragias *</label><select
                                                id="hemorragia" name="hemorragia" required>
                                                <option value="">Seleccionar</option>
                                                <option>No presenta</option>
                                                <option>Externa activa</option>
                                                <option>Interna sospechada</option>
                                                <option>Controlada</option>
                                                <option>No evaluable</option>
                                            </select></div>
                                        <div class="abcde-field"><label for="capilar">Llenado capilar
                                                *</label><select id="capilar" name="capilar" required>
                                                <option value="">Seleccionar</option>
                                                <option>Menor o igual a 2 segundos</option>
                                                <option>Entre 2 y 4 segundos</option>
                                                <option>Mayor a 4 segundos</option>
                                                <option>No evaluable</option>
                                            </select></div>
                                        <div class="abcde-field"><label for="accesos">Accesos venosos
                                                *</label><select id="accesos" name="accesos" required>
                                                <option value="">Seleccionar</option>
                                                <option>No tiene</option>
                                                <option>1 acceso periférico</option>
                                                <option>2 o más accesos periféricos</option>
                                                <option>Acceso central</option>
                                                <option>Otro</option>
                                            </select></div>
                                    </div>
                                </div>
                            </section>
                            <section class="abcde-card" aria-labelledby="headD">
                                <div class="abcde-head abcde-d"><span class="abcde-letter">D</span>
                                    <div>
                                        <h2 id="headD">Evaluación neurológica</h2>
                                        <p>Disability · Glasgow, pupilas y respuesta AVDI</p>
                                    </div>
                                </div>
                                <div class="abcde-body">
                                    <div class="abcde-fields">
                                        <div class="abcde-field"><label for="ocular">Glasgow: apertura ocular (E)
                                                *</label><select id="ocular" name="ocular" required>
                                                <option value="">Seleccionar</option>
                                                <option value="4">Espontánea (4)</option>
                                                <option value="3">A la voz (3)</option>
                                                <option value="2">Al dolor / presión (2)</option>
                                                <option value="1">Sin apertura (1)</option>
                                                <option value="NT">No evaluable (NT)</option>
                                            </select></div>
                                        <div class="abcde-field"><label for="verbal">Glasgow: respuesta verbal (V)
                                                *</label><select id="verbal" name="verbal" required>
                                                <option value="">Seleccionar</option>
                                                <option value="5">Orientado (5)</option>
                                                <option value="4">Confuso (4)</option>
                                                <option value="3">Palabras inapropiadas (3)</option>
                                                <option value="2">Sonidos incomprensibles (2)</option>
                                                <option value="1">Sin respuesta (1)</option>
                                                <option value="NT">No evaluable (NT)</option>
                                            </select></div>
                                        <div class="abcde-field"><label for="motora">Glasgow: respuesta motora (M)
                                                *</label><select id="motora" name="motora" required>
                                                <option value="">Seleccionar</option>
                                                <option value="6">Obedece órdenes (6)</option>
                                                <option value="5">Localiza el dolor (5)</option>
                                                <option value="4">Retirada / flexión normal (4)</option>
                                                <option value="3">Flexión anormal (3)</option>
                                                <option value="2">Extensión anormal (2)</option>
                                                <option value="1">Sin respuesta (1)</option>
                                                <option value="NT">No evaluable (NT)</option>
                                            </select></div>
                                        <div class="abcde-field"><label for="pupilas">Evaluación pupilar
                                                *</label><select id="pupilas" name="pupilas" required>
                                                <option value="">Seleccionar</option>
                                                <option>Isocóricas y reactivas</option>
                                                <option>Isocóricas no reactivas</option>
                                                <option>Anisocóricas</option>
                                                <option>Midriáticas</option>
                                                <option>Mióticas</option>
                                                <option>No evaluable</option>
                                            </select></div>
                                        <div class="abcde-field"><label for="avdi">AVDI *</label><select
                                                id="avdi" name="avdi" required>
                                                <option value="">Seleccionar</option>
                                                <option>Alerta</option>
                                                <option>Responde a la voz</option>
                                                <option>Responde al dolor</option>
                                                <option>No responde</option>
                                                <option>No evaluable</option>
                                            </select></div>
                                        <div class="abcde-field full">
                                            <div class="abcde-total" aria-live="polite">
                                                <div><span class="field-label">Puntaje total Glasgow</span><small
                                                        id="glasgowDetail">Selecciona las tres respuestas</small></div>
                                                <strong id="glasgowTotal">— / 15</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <section class="abcde-card" aria-labelledby="headE">
                                <div class="abcde-head abcde-e"><span class="abcde-letter">E</span>
                                    <div>
                                        <h2 id="headE">Exposición</h2>
                                        <p>Exposure · Lesiones, quemaduras y condición térmica</p>
                                    </div>
                                </div>
                                <div class="abcde-body">
                                    <div class="abcde-fields">
                                        <div class="abcde-field full"><span class="field-label">Lesiones observadas
                                                *</span>
                                            <div class="abcde-checks" id="lesionesGrupo"><label><input
                                                        type="checkbox" name="lesiones" value="No presenta"
                                                        id="sinLesiones">No presenta</label><label><input
                                                        type="checkbox" name="lesiones"
                                                        value="Heridas">Heridas</label><label><input type="checkbox"
                                                        name="lesiones"
                                                        value="Fracturas">Fracturas</label><label><input
                                                        type="checkbox" name="lesiones"
                                                        value="Contusiones">Contusiones</label><label><input
                                                        type="checkbox" name="lesiones" value="Otras"
                                                        id="otrasLesiones">Otras</label></div>
                                        </div>
                                        <div class="abcde-field abcde-hidden" id="otrasWrap"><label
                                                for="otrasDescripcion">Especificar otras lesiones *</label><input
                                                id="otrasDescripcion" name="otrasDescripcion" maxlength="200"
                                                placeholder="Describe las lesiones"></div>
                                        <div class="abcde-field"><label for="termica">Condición térmica
                                                *</label><select id="termica" name="termica" required>
                                                <option value="">Seleccionar</option>
                                                <option>Hipotermia</option>
                                                <option>Normotermia</option>
                                                <option>Febrícula</option>
                                                <option>Fiebre</option>
                                                <option>Hipertermia</option>
                                                <option>No evaluable</option>
                                            </select><small>Clasificación descriptiva; consultar temperatura medida en
                                                Signos Vitales.</small></div>
                                        <div class="abcde-field"><label for="quemaduras">Quemaduras *</label><select
                                                id="quemaduras" name="quemaduras" required>
                                                <option value="">Seleccionar</option>
                                                <option value="No presenta">No presenta</option>
                                                <option>Superficiales</option>
                                                <option>Espesor parcial</option>
                                                <option>Espesor total</option>
                                                <option>No evaluable</option>
                                            </select></div>
                                        <div class="abcde-field abcde-hidden" id="scqWrap"><label
                                                for="scq">Superficie corporal quemada (%)</label><input
                                                id="scq" name="scq" type="number" min="0"
                                                max="100" step="0.1" placeholder="0 a 100"></div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                    <div class="abcde-bottom">
                        <section class="abcde-card">
                            <div class="abcde-body">
                                <div class="abcde-field"><label for="observaciones">Observaciones generales de la
                                        evaluación</label>
                                    <textarea id="observaciones" name="observaciones" maxlength="3000"
                                        placeholder="Registra hallazgos relevantes de A, B, C, D y E, intervenciones simuladas y reevaluación..."></textarea>
                                </div>
                                <div class="abcde-message" id="saveMessage" role="status" aria-live="polite"></div>
                                <div class="abcde-actions"><button type="button" class="abcde-btn"
                                        id="clearButton">Limpiar</button><button type="button" class="abcde-btn"
                                        id="cancelButton">Cancelar</button><button type="submit"
                                        class="abcde-btn primary" id="saveButton">Guardar evaluación</button></div>
                            </div>
                        </section>
                    </div>
                </form>
            </main>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Referencias y caso clínico de demostración
            const $ = id => document.getElementById(id);
            const casos = {
                'CAS-001': 'Alteración neurológica aguda',
                'CAS-002': 'Crisis convulsivas en Urgencias',
                'CAS-003': 'Dolor torácico agudo',
                'CAS-004': 'Hipoglicemia severa',
                'CAS-005': 'Politraumatismo por accidente de tránsito'
            };
            const solicitado = new URLSearchParams(window.location.search).get('caso');
            const casoId = Object.prototype.hasOwnProperty.call(casos, solicitado) ? solicitado : 'CAS-001';
            const query = '?caso=' + encodeURIComponent(casoId);
            $('caseCode').textContent = casoId;
            $('caseTitle').textContent = casos[casoId];
            const rutas = {
                detalle: "{{ url('/estudiante/caso') }}" + '/' + encodeURIComponent(casoId),
                admision: "{{ route('preview.estudiante.admision') }}" + query,
                triage: "{{ route('preview.estudiante.triage') }}" + query,
                signos: "{{ route('preview.estudiante.signos-vitales') }}" + query,
                abcde: "{{ route('preview.estudiante.abcde') }}" + query
            };
            const enlaces = {
                sidebarFicha: rutas.admision,
                sidebarAdmision: rutas.admision,
                sidebarTriage: rutas.triage,
                sidebarSignos: rutas.signos,
                sidebarABCDE: rutas.abcde,
                tabResumen: rutas.detalle,
                tabAdmision: rutas.admision,
                tabTriage: rutas.triage,
                tabSignos: rutas.signos,
                tabABCDE: rutas.abcde,
                backToCase: rutas.detalle
            };
            Object.entries(enlaces).forEach(([id, url]) => {
                if ($(id)) $(id).href = url;
            });
            $('menuToggle').addEventListener('click', () => {
                const closed = $('appShell').classList.toggle('sidebar-hidden');
                $('menuToggle').setAttribute('aria-expanded', String(!closed));
            });
            const form = $('abcdeForm');
            const storageKey = 'fctp_abcde_v1_' + casoId;
            let registros = [];
            let editingId = null;
            try {
                const stored = JSON.parse(sessionStorage.getItem(storageKey) || '[]');
                registros = Array.isArray(stored) ? stored : [];
            } catch (_) {
                registros = [];
            }
            const persistir = () => {
                try {
                    sessionStorage.setItem(storageKey, JSON.stringify(registros));
                    return true;
                } catch (_) {
                    mensaje('No fue posible guardar en el almacenamiento temporal del navegador.', 'error');
                    return false;
                }
            };
            const mensaje = (texto, tipo = 'ok') => {
                const el = $('saveMessage');
                el.textContent = texto;
                el.className = 'abcde-message show ' + tipo;
            };
            const selectIds = ['obstruccion', 'collar', 'aspiracion', 'intubacion', 'murmullo', 'simetria',
                'ventilacion', 'oxigenoterapia', 'esfuerzo', 'ritmo', 'calidad', 'hemorragia', 'capilar',
                'accesos', 'ocular', 'verbal', 'motora', 'pupilas', 'avdi', 'termica', 'quemaduras'
            ];
            const fields = [...selectIds, 'flujo', 'otroOxigeno', 'pulso', 'otrasDescripcion', 'scq', 'observaciones'];
            // La puntuación no se suma si algún componente Glasgow es no evaluable
            function calcularGlasgow() {
                const v = ['ocular', 'verbal', 'motora'].map(id => $(id).value);
                if (v.some(x => !x)) {
                    $('glasgowTotal').textContent = '— / 15';
                    $('glasgowDetail').textContent = 'Selecciona las tres respuestas';
                    return null;
                }
                if (v.includes('NT')) {
                    $('glasgowTotal').textContent = 'NT';
                    $('glasgowDetail').textContent = v.map((x, i) => ['E', 'V', 'M'][i] + x).join(' · ') +
                        ' · No calcular total';
                    return null;
                }
                const total = v.reduce((a, x) => a + Number(x), 0);
                $('glasgowTotal').textContent = total + ' / 15';
                $('glasgowDetail').textContent = `E${v[0]} · V${v[1]} · M${v[2]}`;
                return total;
            }

            function actualizarCondicionales() {
                const opcionOxigeno = $('oxigenoterapia').value;
                const usaCanula = opcionOxigeno === 'canula';
                const usaOtroDispositivo = opcionOxigeno === 'otro';
                $('flujoWrap').classList.toggle('abcde-hidden', !usaCanula);
                $('flujo').required = usaCanula;
                if (!usaCanula) $('flujo').value = '';
                $('otroOxigenoWrap').classList.toggle('abcde-hidden', !usaOtroDispositivo);
                $('otroOxigeno').required = usaOtroDispositivo;
                if (!usaOtroDispositivo) $('otroOxigeno').value = '';
                const otras = $('otrasLesiones').checked;
                $('otrasWrap').classList.toggle('abcde-hidden', !otras);
                $('otrasDescripcion').required = otras;
                if (!otras) $('otrasDescripcion').value = '';
                const quemadura = $('quemaduras').value && $('quemaduras').value !== 'No presenta' && $(
                    'quemaduras').value !== 'No evaluable';
                $('scqWrap').classList.toggle('abcde-hidden', !quemadura);
                if (!quemadura) $('scq').value = '';
            }

            function actualizarLesiones(event) {
                const none = $('sinLesiones');
                const boxes = [...document.querySelectorAll('input[name="lesiones"]')];
                if (event && event.target === none && none.checked) boxes.forEach(x => {
                    if (x !== none) x.checked = false;
                });
                if (event && event.target !== none && event.target.checked) none.checked = false;
                actualizarCondicionales();
            }
            // Reglas de coherencia y validación antes del guardado
            function validar() {
                actualizarCondicionales();
                for (const id of selectIds) {
                    if (!$(id).value) {
                        $(id).focus();
                        mensaje('Completa el campo obligatorio: ' + document.querySelector('label[for="' + id +
                            '"]').textContent.replace('*', '').trim() + '.', 'error');
                        return false;
                    }
                }
                if (!document.querySelector('input[name="lesiones"]:checked')) {
                    mensaje('Selecciona al menos una opción de lesiones observadas.', 'error');
                    $('sinLesiones').focus();
                    return false;
                }
                if ($('flujo').required && (!$('flujo').value || Number($('flujo').value) <= 0 || Number($('flujo')
                        .value) > 60)) {
                    mensaje('Ingresa un flujo de oxígeno válido (mayor que 0 y hasta 60 L/min).', 'error');
                    $('flujo').focus();
                    return false;
                }
                if ($('otroOxigeno').required && !$('otroOxigeno').value.trim()) {
                    mensaje('Especifica el otro dispositivo de oxigenoterapia.', 'error');
                    $('otroOxigeno').focus();
                    return false;
                }
                if ($('otrasLesiones').checked && !$('otrasDescripcion').value.trim()) {
                    mensaje('Especifica las otras lesiones observadas.', 'error');
                    $('otrasDescripcion').focus();
                    return false;
                }
                for (const id of ['pulso', 'scq']) {
                    if ($(id).value && !$(id).checkValidity()) {
                        mensaje('Revisa el valor ingresado en ' + id + '.', 'error');
                        $(id).focus();
                        return false;
                    }
                }
                if ($('sinLesiones').checked && document.querySelectorAll('input[name="lesiones"]:checked').length >
                    1) {
                    mensaje('No presenta lesiones no puede combinarse con otros tipos de lesión.', 'error');
                    return false;
                }
                return true;
            }

            function datosFormulario() {
                const data = {};
                fields.forEach(id => data[id] = $(id).value);
                data.lesiones = [...document.querySelectorAll('input[name="lesiones"]:checked')].map(x => x.value);
                data.glasgow = calcularGlasgow();
                return data;
            }

            function limpiar() {
                form.reset();
                editingId = null;
                $('saveButton').textContent = 'Guardar evaluación';
                actualizarCondicionales();
                calcularGlasgow();
                $('saveMessage').className = 'abcde-message';
                $('saveMessage').textContent = '';
            }
            form.addEventListener('submit', async event => {
    event.preventDefault();
    if (!validar()) return;

    const data = datosFormulario();
    const fecha = new Date().toISOString();

    mensaje('Guardando evaluación en la base de datos...', 'ok');

    try {
        const token = localStorage.getItem('auth_token');

        // Payload adaptado al backend (SignosVitalesController -> EvaluacionAbcdeController)
        const payload = {
            // A
            obstruccion_via_aerea: data.obstruccion === 'No presenta' ? 'no' : (data.obstruccion === 'Parcial' ? 'parcial' : 'total'),
            collar_cervical: data.collar === 'No indicado' ? 'no_indicado' : (data.collar === 'Colocado' ? 'colocado' : 'indicado'),
            aspiracion_secreciones: data.aspiracion === 'No requerida' ? 'no_realizada' : 'realizada',
            intubacion: data.intubacion === 'No requerido' ? 'no_realizada' : 'orotraqueal',
            observaciones_a: data.observaciones || null,
            // B
            murmullo_pulmonar: data.murmullo === 'Conservado bilateral' ? 'normal' : 'disminuido',
            simetria_toracica: data.simetria === 'Simétrica' ? 'simetrica' : 'asimetrica',
            oxigenoterapia: data.oxigenoterapia !== 'sin' ? true : false,
            tipo_dispositivo_o2: data.oxigenoterapia || null,
            flujo_o2_lmin: data.flujo ? Number(data.flujo) : null,
            ventilacion: data.ventilacion === 'Espontánea' ? 'espontanea' : 'asistida',
            observaciones_b: data.observaciones || null,
            // C
            pulso_frecuencia: data.pulso ? Number(data.pulso) : null,
            pulso_ritmo: data.ritmo === 'Regular' ? 'regular' : 'irregular',
            pulso_calidad: data.calidad === 'Normal / palpable' ? 'fuerte' : 'debil',
            hemorragias: data.hemorragia === 'No presenta' ? 'no_presenta' : 'externas',
            llenado_capilar: data.capilar === 'Menor o igual a 2 segundos' ? 'menor_2seg' : (data.capilar === 'Entre 2 y 4 segundos' ? '2_4seg' : 'mayor_4seg'),
            accesos_venosos_perifericos: data.accesos === 'No tiene' ? 0 : 1,
            observaciones_c: data.observaciones || null,
            // D
            glasgow_apertura_ocular: data.ocular !== 'NT' ? Number(data.ocular) : 1,
            glasgow_respuesta_verbal: data.verbal !== 'NT' ? Number(data.verbal) : 1,
            glasgow_respuesta_motora: data.motora !== 'NT' ? Number(data.motora) : 1,
            pupilas: data.pupilas === 'Isocóricas y reactivas' ? 'iguales_reactivas' : 'anisocoricas',
            escala_avdi: data.avdi === 'Alerta' ? 'alerta' : 'verbal',
            observaciones_d: data.observaciones || null,
            // E
            lesiones_heridas: data.lesiones.includes('Heridas'),
            lesiones_fracturas: data.lesiones.includes('Fracturas'),
            lesiones_contusiones: data.lesiones.includes('Contusiones'),
            temperatura_corporal: data.termica === 'Normotermia' ? 'normal' : 'hipotermia_leve',
            quemaduras: data.quemaduras !== 'No presenta',
            porcentaje_scq: data.scq ? Number(data.scq) : 0,
            observaciones_e: data.observaciones || null,
            observaciones_generales: data.observaciones || null,
        };

        const response = await fetch('/api/fichas/1/evaluacion-abcde', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(payload),
        });

        const result = await response.json();

        if (response.status === 201 || response.status === 200) {
            mensaje('Evaluación ABCDE guardada en la base de datos. ✅', 'ok');
            
            // También guardar en sessionStorage (respaldo)
            if (editingId) {
                const index = registros.findIndex(r => r.id === editingId);
                if (index >= 0) {
                    registros[index] = { ...registros[index], data, actualizado: fecha };
                }
            } else {
                registros.push({
                    id: (window.crypto && crypto.randomUUID ? crypto.randomUUID() : String(Date.now()) + '_' + Math.random().toString(36).slice(2)),
                    fecha,
                    data
                });
            }
            persistir();
            limpiar();
        } else {
            console.error('Error del servidor:', result);
            mensaje('Error al guardar en la base de datos: ' + (result.message || 'Error desconocido'), 'error');
        }
    } catch (error) {
        console.error('Error de red:', error);
        mensaje('Error de conexión. Se guardó localmente en esta sesión.', 'error');
        
        // Guardar localmente como respaldo
        if (!editingId) {
            registros.push({
                id: (window.crypto && crypto.randomUUID ? crypto.randomUUID() : String(Date.now()) + '_' + Math.random().toString(36).slice(2)),
                fecha,
                data
            });
        }
        persistir();
        limpiar();
    }
});
            $('clearButton').addEventListener('click', () => {
                if (confirm('¿Limpiar los campos del formulario? Los registros guardados no se borrarán.'))
                    limpiar();
            });
            $('cancelButton').addEventListener('click', () => {
                window.location.href = rutas.signos;
            });
            ['ocular', 'verbal', 'motora'].forEach(id => $(id).addEventListener('change', calcularGlasgow));
            ['oxigenoterapia', 'quemaduras'].forEach(id => $(id).addEventListener('change',
                actualizarCondicionales));
            document.querySelectorAll('input[name="lesiones"]').forEach(x => x.addEventListener('change',
                actualizarLesiones));
            actualizarCondicionales();
            calcularGlasgow();
        });
    </script>
</body>

</html>
