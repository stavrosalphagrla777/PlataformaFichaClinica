
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalle del caso clínico | Ficha Clínica TP</title>
    <link rel="stylesheet" href="{{ asset('css/estudiante-detalle-caso.css') }}">
</head>
<body>
@php
    // Datos de demostración.
    // El docente decidirá qué campos precargar en la ficha clínica.
    $casos = [
        'CAS-001' => [
            'codigo' => 'CAS-001',
            'tipo' => 'Examen',
            'estado' => 'En desarrollo',
            'titulo' => 'Alteración neurológica aguda',
            'escenario' => 'Traslado al Servicio de Urgencias. Analizar continuidad del manejo',
            'presentacion' => [
                'Paciente masculino de 68 años, con antecedentes de hipertensión arterial, diabetes mellitus tipo 2, dislipidemia y tabaquismo. Familiares refieren baja adherencia al tratamiento antihipertensivo. Es traído al Servicio de Urgencias porque, aproximadamente a las 08:15 horas, mientras desayunaba, presentó inicio súbito de dificultad para hablar y pérdida de fuerza del lado derecho del cuerpo.',
                'Al ingreso se encuentra consciente, ansioso y con dificultad para expresarse. Presenta asimetría facial con desviación de la comisura bucal hacia la izquierda, disminución de fuerza en extremidad superior e inferior derecha y lenguaje poco comprensible. La familia señala que una hora antes se encontraba completamente normal.'
            ],
            'signos' => [
                ['PA', '188/104', 'mmHg'],
                ['FC', '92', 'x min'],
                ['FR', '20', 'x min'],
                ['SpO₂', '96%', 'ambiental'],
                ['T°', '36,6', '°C'],
                ['HGT', '118', 'mg/dl'],
                ['Glasgow', '13', 'puntos']
            ],
            'secciones' => [
                [
                    'titulo' => 'Evaluación neurológica',
                    'parrafos' => [
                        'Pupilas isocóricas y reactivas, hemiparesia derecha, dificultad para articular palabras y comprensión parcialmente conservada. No presenta convulsiones.'
                    ]
                ],
                [
                    'titulo' => 'Exámenes',
                    'parrafos' => [
                        'Hemoglobina 14,2 g/dl; hematocrito 42%; leucocitos 8.900/mm³; plaquetas 238.000/mm³; glicemia 118 mg/dl; sodio 139 mEq/L; potasio 4,2 mEq/L; creatinina 1,0 mg/dl. Se solicita TAC cerebral sin contraste.'
                    ]
                ]
            ]
        ],

        'CAS-002' => [
            'codigo' => 'CAS-002',
            'tipo' => 'Tarea',
            'estado' => 'Disponible',
            'titulo' => 'Crisis convulsivas en Urgencias',
            'escenario' => 'Ingreso a Urgencias',
            'presentacion' => [
                'Paciente femenina de 24 años, sin diagnóstico previo de epilepsia, es trasladada al Servicio de Urgencias después de presentar una crisis convulsiva en su lugar de trabajo. Testigos refieren mirada fija, pérdida de conciencia, caída al suelo, rigidez generalizada y posteriormente movimientos bruscos de las cuatro extremidades. La crisis tuvo una duración aproximada de 2 minutos y 30 segundos.',
                'Al finalizar la crisis permanece somnolienta y desorientada. La familia refiere que durante las últimas semanas había presentado cefaleas frecuentes. La noche anterior durmió aproximadamente 3 horas y consumió alcohol durante una celebración.'
            ],
            'signos' => [
                ['PA', '146/88', 'mmHg'],
                ['FC', '108', 'x min'],
                ['FR', '22', 'x min'],
                ['SpO₂', '94%', ''],
                ['T°', '37,0', '°C'],
                ['HGT', '58', 'mg/dl'],
                ['Glasgow', '10', 'puntos']
            ],
            'secciones' => [
                [
                    'titulo' => 'Examen físico y evolución',
                    'parrafos' => [
                        'Al examen físico se observa pequeña lesión en lengua, saliva alrededor de la boca, pupilas reactivas y respiración espontánea. Diez minutos después comienza a recuperar progresivamente el estado de conciencia.',
                        'Durante la observación presenta nuevamente movimientos tónico-clónicos generalizados. Esta segunda crisis supera los 5 minutos de duración.'
                    ]
                ],
                [
                    'titulo' => 'Exámenes',
                    'parrafos' => [
                        'Glicemia 58 mg/dl; sodio 137 mEq/L; potasio 3,9 mEq/L; calcio 8,7 mg/dl; leucocitos 9.800/mm³; hemoglobina 13,4 g/dl.'
                    ]
                ]
            ]
        ],

        'CAS-003' => [
            'codigo' => 'CAS-003',
            'tipo' => 'Examen',
            'estado' => 'Finalizado',
            'titulo' => 'Dolor torácico agudo',
            'escenario' => 'Paciente en Servicio de Urgencias. Analizar categorización, manejo inicial y continuidad hospitalaria',
            'presentacion' => [
                'Paciente masculino de 59 años, con antecedentes de hipertensión arterial, diabetes mellitus tipo 2, dislipidemia y tabaquismo, consulta en el Servicio de Urgencias por dolor intenso en el centro del pecho iniciado hace aproximadamente 40 minutos mientras caminaba.',
                'Describe el dolor como opresivo, intenso, similar a una presión muy fuerte sobre el pecho, con irradiación hacia brazo izquierdo, hombro y mandíbula. Presenta sudoración fría, náuseas, sensación de falta de aire y ansiedad.'
            ],
            'signos' => [
                ['PA', '158/94', 'mmHg'],
                ['FC', '104', 'x min'],
                ['FR', '24', 'x min'],
                ['SpO₂', '93%', ''],
                ['T°', '36,5', '°C']
            ],
            'secciones' => [
                [
                    'titulo' => 'Observación clínica y electrocardiograma',
                    'parrafos' => [
                        'Paciente pálido, diaforético y ansioso.',
                        'Electrocardiograma inicial: ritmo sinusal, elevación del segmento ST en derivaciones precordiales anteriores y alteraciones compatibles con lesión/isquemia aguda.'
                    ]
                ],
                [
                    'titulo' => 'Exámenes',
                    'parrafos' => [
                        'Troponina I elevada; CK-MB elevada; glicemia 164 mg/dl; sodio 138 mEq/L; potasio 4,5 mEq/L; creatinina 1,1 mg/dl; hemoglobina 14,8 g/dl.'
                    ]
                ],
                [
                    'titulo' => 'Evolución durante observación',
                    'parrafos' => [
                        'Mientras permanece en observación presenta aumento del dolor, FC: 118 x min y mayor dificultad respiratoria.'
                    ]
                ]
            ]
        ],

        'CAS-004' => [
            'codigo' => 'CAS-004',
            'tipo' => 'Apoyo',
            'estado' => 'Disponible',
            'titulo' => 'Hipoglicemia severa',
            'escenario' => 'Paciente encontrada en domicilio y trasladada a Urgencias. Analizar manejo inicial, traslado y recepción hospitalaria',
            'presentacion' => [
                'Paciente femenina de 32 años, con antecedentes de diabetes mellitus tipo 1, en tratamiento habitual con insulina. Es encontrada inconsciente por su madre en su habitación, quien refiere que durante los últimos días la paciente se encontraba más retraída, con cambios en su conducta y había manifestado encontrarse preocupada por problemas personales.',
                'La madre señala que la noche anterior la paciente había cenado normalmente. Sin embargo, durante la mañana no respondió a los llamados, por lo que ingresó a su habitación y la encontró inconsciente sobre la cama.'
            ],
            'signos' => [
                ['PA', '90/50', 'mmHg'],
                ['FC', '118', 'x min'],
                ['FR', '26', 'x min'],
                ['SpO₂', '96%', ''],
                ['T°', '33', '°C'],
                ['Glasgow', '5', 'puntos'],
                ['HGT', '40', 'mg/dl']
            ],
            'secciones' => [
                [
                    'titulo' => 'Hallazgos en el domicilio',
                    'parrafos' => [
                        'En la habitación se observa:',
                        'Insulina de uso habitual de la paciente.',
                        'Jeringas utilizadas.',
                        'Una libreta donde registra habitualmente sus controles de glicemia.',
                        'El último registro de HGT corresponde al día anterior.',
                        'No se encuentran restos de medicamentos diferentes a los de su tratamiento habitual.',
                        'La madre refiere que no sabe si la paciente se administró su dosis habitual de insulina esa mañana.'
                    ]
                ],
                [
                    'titulo' => 'Evaluación al ingreso',
                    'parrafos' => [
                        'Paciente pálida, fría y sudorosa, con compromiso severo de conciencia. Pupilas isocóricas y reactivas. ECG: ritmo sinusal.'
                    ]
                ],
                [
                    'titulo' => 'Manejo inicial y evolución',
                    'parrafos' => [
                        'Durante el manejo inicial se administra tratamiento para corregir la hipoglicemia y se realiza control seriado de HGT. La glicemia aumenta inicialmente, pero posteriormente vuelve a presentar un descenso, requiriendo vigilancia y nuevos controles.'
                    ]
                ],
                [
                    'titulo' => 'Diagnóstico médico inicial',
                    'parrafos' => [
                        'Hipoglicemia severa con compromiso de conciencia.'
                    ]
                ]
            ]
        ],

        'CAS-005' => [
            'codigo' => 'CAS-005',
            'tipo' => 'Tarea',
            'estado' => 'En desarrollo',
            'titulo' => 'Politraumatismo por accidente de tránsito',
            'escenario' => 'Accidente de tránsito. Analizar ABCDE, estabilización, traslado y manejo intrahospitalario',
            'presentacion' => [
                'Paciente masculino de 31 años, conductor de automóvil, involucrado en accidente de tránsito de alta energía. El vehículo presenta deformación importante en su parte frontal. El paciente permanece atrapado parcialmente durante aproximadamente 20 minutos.',
                'Al rescate se encuentra consciente, ansioso y con dificultad respiratoria. Presenta herida sangrante en cuero cabelludo, dolor intenso en tórax, dolor abdominal, deformidad evidente de fémur derecho y múltiples abrasiones.'
            ],
            'signos' => [
                ['PA', '92/58', 'mmHg'],
                ['FC', '126', 'x min'],
                ['FR', '30', 'x min'],
                ['SpO₂', '89%', ''],
                ['T°', '35,8', '°C'],
                ['Glasgow', '13', 'puntos']
            ],
            'secciones' => [
                [
                    'titulo' => 'Evaluación secundaria',
                    'parrafos' => [
                        'Tórax con respiración superficial y disminución de murmullo pulmonar en hemitórax derecho; abdomen doloroso a la palpación; extremidad inferior derecha con deformidad, edema y dolor intenso; pulso pedio derecho débil.',
                        'Paciente pálido, frío y sudoroso.'
                    ]
                ],
                [
                    'titulo' => 'Exámenes',
                    'parrafos' => [
                        'Hemoglobina 10,1 g/dl; hematocrito 31%; leucocitos 15.600/mm³; plaquetas 212.000/mm³. Gases: pH 7,28; PaCO₂ 32 mmHg; HCO₃⁻ 15 mEq/L; lactato 5,2 mmol/L.'
                    ]
                ]
            ]
        ]
    ];

    $caso = $casos[$id] ?? null;
@endphp

<div class="app app-shell" id="appShell">

    <!-- MENÚ LATERAL -->
    <aside class="sidebar" id="sidebar">

        <div class="brand">
            <div class="brand-logo">
                <img src="{{ asset('images/logo-tp.png') }}"
                     alt="Logo TP Enfermería">
            </div>
            <span>Ficha Clínica TP</span>
        </div>

        <nav aria-label="Menú principal">
            <div class="nav-title">PRINCIPAL</div>

            <a class="nav-link active"
               href="{{ route('preview.estudiante.casos') }}">
                Mis casos clínicos
            </a>


            <div class="nav-title">CUENTA</div>

            <span class="nav-link nav-disabled">Mi perfil</span>
            <span class="nav-link nav-disabled">Cerrar sesión</span>
        </nav>
    </aside>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="main-area">

        <header class="topbar">
            <div class="topbar-left">

                <button type="button"
                        class="menu-toggle"
                        id="menuToggle"
                        aria-label="Mostrar u ocultar menú"
                        aria-expanded="true"
                        aria-controls="sidebar">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <span class="topbar-title">
                    Detalle del caso clínico
                </span>
            </div>

            <div class="user-info">
                <div class="user-details">
                    <strong>Estudiante</strong>
                    <small>4.º medio</small>
                </div>

                <div class="user-avatar">
                    E
                </div>
            </div>
        </header>

        <main class="content">

            <div class="page-heading">
                <div>
                    <p class="eyebrow">HU-06 · CASOS CLÍNICOS</p>

                    <h1>Informe del caso clínico</h1>

                    <p class="muted">
                        Lee el escenario antes de ingresar a la ficha clínica.
                    </p>
                </div>

                <a class="btn btn-secondary"
                   href="{{ route('preview.estudiante.casos') }}">
                    ← Volver a mis casos
                </a>
            </div>

            @if($caso)

                <article class="report">

                    <!-- ENCABEZADO DEL INFORME -->
                    <div class="report-header">

                        <div>
                            <span class="case-code">
                                {{ $caso['codigo'] }}
                            </span>

                            <h2>{{ $caso['titulo'] }}</h2>

                            <p class="scenario">
                                <strong>Escenario principal:</strong>
                                {{ $caso['escenario'] }}
                            </p>
                        </div>

                        <div class="report-badges">
                            <span class="tag tag-type">
                                {{ $caso['tipo'] }}
                            </span>

                            <span class="tag tag-status">
                                {{ $caso['estado'] }}
                            </span>
                        </div>
                    </div>

                    <!-- PRESENTACIÓN -->
                    <section class="report-section">

                        <h3>
                            <span class="section-number">01</span>
                            Presentación del caso
                        </h3>

                        @foreach($caso['presentacion'] as $parrafo)
                            <p>{{ $parrafo }}</p>
                        @endforeach

                    </section>

                    <!-- SIGNOS VITALES -->
                    <section class="report-section">

                        <h3>
                            <span class="section-number">02</span>
                            Signos vitales informados
                        </h3>

                        <div class="vitals-grid">

                            @foreach($caso['signos'] as $signo)
                                <div class="vital">
                                    <span class="vital-label">
                                        {{ $signo[0] }}
                                    </span>

                                    <strong>{{ $signo[1] }}</strong>

                                    <small>{{ $signo[2] }}</small>
                                </div>
                            @endforeach

                        </div>
                    </section>

                    <!-- SECCIONES ADICIONALES -->
                    @foreach($caso['secciones'] as $indice => $seccion)

                        <section class="report-section">

                            <h3>
                                <span class="section-number">
                                    {{ str_pad($indice + 3, 2, '0', STR_PAD_LEFT) }}
                                </span>

                                {{ $seccion['titulo'] }}
                            </h3>

                            @foreach($seccion['parrafos'] as $parrafo)
                                <p>{{ $parrafo }}</p>
                            @endforeach

                        </section>

                    @endforeach

                    <!-- ACCIONES -->
                    <div class="report-footer">

                        <div>
                            <strong>Simulación educativa</strong>

                            <p>
                                La información del informe no implica que
                                los campos de la ficha estén precargados.
                                Esa configuración corresponde al docente.
                            </p>
                        </div>

                        
                        <a
                            class="btn btn-primary"
                            href="{{ route('preview.estudiante.admision', ['caso' => $caso['codigo']]) }}"
                        >
                            {{ $caso['estado'] === 'Finalizado'
                                ? 'Ver ficha clínica'
                                : ($caso['estado'] === 'En desarrollo'
                                    ? 'Continuar ficha clínica'
                                    : 'Iniciar ficha clínica') }}
                            →
                        </a>

                    </div>

                </article>

            @else

                <section class="report empty-report">
                    <h2>Informe no disponible</h2>

                    <p>
                        El identificador {{ $id }} no tiene un informe
                        configurado en esta demostración.
                    </p>

                    <a class="btn btn-primary"
                       href="{{ route('preview.estudiante.casos') }}">
                        Volver al listado
                    </a>
                </section>

            @endif

        </main>
    </div>
</div>

<script>
    const appShell = document.getElementById('appShell');
    const menuToggle = document.getElementById('menuToggle');

    menuToggle.addEventListener('click', function () {
        const hidden = appShell.classList.toggle('sidebar-hidden');

        menuToggle.setAttribute('aria-expanded', String(!hidden));
    });
    </script>

</body>
</html>
