# Ficha Clínica TP — Informe técnico, estado y transferencia a desarrollo

**Proyecto:** Plataforma Digital de Ficha Clínica Educativa — Hospital de Simulación Virtual  
**Contexto:** Liceo Bicentenario Hermanos Sotomayor Baeza; estudiantes de 3.º y 4.º medio de TP Enfermería  
**Corte de revisión del código:** 13 de noviembre de 2026, sobre `FichaClinica(10).zip`  
**Destinatario principal:** Alexis (continuación backend, integración y despliegue)  
**Naturaleza del documento:** inventario verificable del código y guía de continuidad, **no** certificación de producción

> **Advertencia de alcance.** Esta entrega consolida código Laravel, vistas y lógica del historial. Se revisó el contenido del ZIP y se efectuaron verificaciones estáticas. No se dispuso de la instancia MySQL, de la sesión del navegador de los usuarios ni de un SMTP real para ejecutar pruebas integrales en el entorno del informe. Las evidencias funcionales descritas como «probadas por usuario» proceden del ciclo de pruebas locales; las funciones nuevas de auditoría requieren validación en el equipo de desarrollo. Los datos de pacientes deben continuar siendo exclusivamente ficticios.

## 1. Resumen ejecutivo

La plataforma es una aplicación web educativa en **Laravel 11 / PHP 8.4**, **MySQL 8.0** y **Docker Compose**. Integra componentes del backend de Alexis con las interfaces del frontend original de Pablo, distinguiendo **administradores**, **docentes** y **estudiantes** mediante sesión web y roles de Spatie Permission. La interfaz permite desarrollar y revisar fichas clínicas de simulación; **no procesa datos de pacientes reales**.

El estado funcional incorporado en el ZIP permite administrar usuarios y casos; asignar casos publicados por curso; registrar fichas de CESFAM (3.º) y Urgencias (4.º); conservar su progreso en `case_sections`; entregar y bloquear fichas; revisarlas por docente; proponer comprobaciones automáticas sobre la presencia de datos; finalizar evaluaciones con los **nueve criterios**; entregar retroalimentación individual; y generar una pauta PDF cuando la evaluación está finalizada.

**En esta actualización** se amplía la bitácora de actividad para registrar el ciclo académico completo: casos creados/eliminados, asignaciones creadas/modificadas/eliminadas, fichas entregadas y evaluaciones definitivas con **nota y porcentaje**. Se documentan los **19 controladores funcionales** (comentarios de clase y métodos) y se consolidan los documentos anteriores en este informe y un README breve.

### 1.1 Estado por módulo

| Área | Disponible en el código | Consideraciones / pendientes |
|---|---|---|
| Login, sesión y roles | Acceso por usuario o correo y contraseña; rutas protegidas | Probar bloqueo de usuarios inactivos y todas las rutas por rol en entorno real |
| Administración | Dashboard, usuarios, alta, edición, activación, carga CSV, roles y cursos | Validar reglas de negocio y uso con usuarios reales; proteger credenciales |
| Casos docentes | Catálogo compartido, creación, detalles, filtro de autoría, eliminación restringida | Caso con ficha iniciada no se elimina; la eliminación no se permite a docentes ajenos |
| Asignaciones | Cualquier docente/admin puede asignar casos publicados; duración por asignación | Validar solapamientos, fechas, cursos y pruebas de concurrencia |
| 3.º medio — CESFAM | Antecedentes, signos vitales, examen físico, citaciones, guardado y entrega | Revisar campos y exigencias clínicas finales con el liceo |
| 4.º medio — Urgencias | 11 secciones, navegación, borradores y entrega desde Destino | Verificar todas las rutas/recargas y persistencia por sección con MySQL |
| Revisión y evaluación | Fichas legibles, pauta de nueve criterios, borrador/final, comentarios por criterio | Los criterios clínicos deben evaluarse por el docente; calibrar oficialmente la fórmula |
| Pauta PDF | Descarga de pauta finalizada por estudiante propio, docente autorizado o administrador | Validar formato institucional, RUT/firma y exportación con casos reales de prueba |
| Historial | Filtros, 15 filas/página, auditoría de usuarios y flujo académico | No reconstruye operaciones anteriores a la instrumentación; revisar retención y respaldo |
| Recuperación de contraseña | Rutas web/API y notificaciones Laravel presentes | **Alexis:** configurar proveedor SMTP real y verificar envío, enlace y seguridad |
| Exámenes y archivos | Solicitudes y metadatos en fichas | **Pendiente crítico:** subir bytes de JPG/PNG/PDF al servidor, permisos y descarga duradera |
| Despliegue | Compose local y borrador alternativo para VM/GCP | No está homologado para producción: TLS, SMTP, almacenamiento, backups, seguridad |

## 2. Arquitectura y separación de responsabilidades

```text
Navegador
  ├── Blade + CSS + JS (recursos públicos)
  │     ├── Admin: usuarios, roles, historial
  │     ├── Docente: casos, asignaciones, revisión, evaluación
  │     ├── 3.º CESFAM: cuatro formularios
  │     └── 4.º Urgencias: once formularios + resumen
  ├── Sesión web + CSRF (rutas /login, /admin, /docente, /estudiante)
  └── API heredada token (rutas /api/...; auth.token)
           ↓
Laravel 11 / PHP 8.4
  ├── Controllers/Delivery: flujo integrado educativo
  ├── Controllers/Api: backend API conservado de Alexis
  ├── Controllers/Web + Admin: vistas y funciones heredadas
  ├── Models / Support: validaciones, plazos, rúbricas y PDF
  └── ActivityAudit: eventos de coordinación
           ↓
MySQL 8.0 (volumen Docker db_data)
  ├── users / roles / permisos
  ├── clinical_cases / case_assignments
  ├── student_cases / case_sections / case_evaluations
  ├── audit_events
  └── tablas clínicas originales (fichas_clinicas, medical_records, ...)
```

**Importante para Alexis:** conviven dos vías de registro: la API histórica (`Api/*`, tablas como `evaluaciones_abcde`) y el flujo educativo web integrado (`Delivery/*`, secciones JSON en `case_sections`). Para el estudiante en las vistas web de 4.º, **ABCDE se persiste dentro de `case_sections` bajo la clave `abcde`**, no necesariamente en la tabla antigua `evaluaciones_abcde`. No fusionar o borrar tablas históricas sin migración y verificación de consumidores.

El frontend de 4.º utiliza `public/js/estudiante/4to-backend-bridge.js` para sincronizar estados con Laravel; CESFAM usa `tercero-bridge.js`. Los textos `storage_type` `session` o `local` son etiquetas de compatibilidad de las secciones, **no significan que la información definitiva de la ficha se guarde únicamente en el navegador**. Los payloads del flujo integrado se almacenan en la base de datos cuando el backend confirma el guardado. **Excepción:** los bytes de adjuntos de Exámenes aún no cuentan con almacenamiento permanente.

## 3. Estructura de directorios

```text
FichaClinica/
├── README.md                               Punto de entrada a esta documentación
├── docs/
│   └── INFORME_TECNICO_Y_TRANSFERENCIA.md  Informe único actualizado
├── docker-compose.yml                      Entorno local: Laravel, MySQL, phpMyAdmin, Mailpit
├── docker-compose.production.yml           Propuesta separada para despliegue VM (no homologada)
├── .env.production.example                 Ejemplo de variables de producción; NO guardar secretos
├── INICIAR_LOCAL.ps1                        Preparación inicial desde Windows
├── VERIFICAR_LOCAL.ps1                     Diagnóstico básico de Docker, Laravel y API
├── deployment/nginx.conf                    Configuración de Nginx para propuesta de VM
└── backend/
    ├── app/
    │   ├── Http/Controllers/
    │   │   ├── Delivery/                   Flujo web integrado
    │   │   ├── Api/                        API histórica de Alexis
    │   │   ├── Web/                        Compatibilidad de vistas / recuperación
    │   │   └── Admin/                      Controlador de usuario heredado
    │   ├── Http/Middleware/               Token, autorización de casos de 4.º
    │   ├── Models/                        Entidades y relaciones Eloquent
    │   └── Support/                       Reglas de negocio, auditoría, rúbrica y PDF
    │       ├── ActivityAudit.php           NUEVO: auditoría centralizada
    │       ├── FourthYear.php              Rutas lógicas, curso y plazos de 4.º
    │       ├── ThirdYear.php               Cursos y secciones de 3.º
    │       ├── EvaluationAssistance.php     Reglas de sugerencias, no juicios clínicos
    │       ├── UrgencyRubric.php            Escala y cálculo de nueve criterios
    │       └── ClinicalRubricPdf.php        Generación de PDF de las pautas
    ├── routes/web.php                      Sesión + rutas de administración, docente y estudiante
    ├── routes/api.php                      API con autenticación por token
    ├── resources/views/                   Plantillas Blade por rol
    ├── public/css/ y public/js/            Diseños e interacción
    ├── database/migrations/               Esquema versionado (incluida audit_events)
    ├── database/seeders/                   Roles, datos demo y credenciales QA
    ├── tests/Feature/                     Pruebas API y de integración, ActivityAuditTest.php
    ├── Dockerfile                          Imagen local basada en php:8.4-cli
    ├── Dockerfile.production               Imagen propuesta para VM
    ├── composer.json y composer.lock        Dependencias PHP
    └── .env.example                        Ejemplo local, nunca contiene APP_KEY real
```

**Limpieza documental de esta transferencia:** se sustituyeron los múltiples informes de etapas antiguas y documentos de diseño preliminar por **este informe**, que refleja el estado actual. Se mantienen los archivos `.example`, scripts y configuraciones de despliegue. El ZIP limpio para Alexis **omite** `.env` real, `vendor/`, logs, cachés y carpetas de respaldos de parches: deben recrearse o permanecer en el equipo original, nunca mezclarse con el código fuente entregable.

## 4. Base de datos y entidades relevantes

| Tabla / modelo | Función y relaciones |
|---|---|
| `users` / `User` | Identidad, usuario, correo, clave hasheada, nivel, curso, activo y roles (Spatie) |
| `clinical_cases` / `ClinicalCase` | Código `CAS-...`, título, autor (`docente_id`), nivel, servicio, estado, duración y hallazgos segmentarios |
| `case_assignments` / `CaseAssignment` | Relación caso-curso-docente, ventanas de inicio/término, duración efectiva y estado |
| `student_cases` / `StudentCase` | Ficha del estudiante para una asignación, comienzo, entrega y estado |
| `case_sections` / `CaseSection` | Sección de una ficha: `clave`, `storage_key`, `storage_type`, `payload` JSON codificado como cadena |
| `case_evaluations` / `CaseEvaluation` | Nueve criterios, comentarios por criterio (`notas`), retroalimentación general, nota, porcentaje, borrador/final |
| `audit_events` / `AuditEvent` | `actor_id`, `target_id`, `action`, `description`, marcas de tiempo; actor y destino son usuarios |
| `personal_access_tokens` | Autenticación API heredada, distinta de la sesión web |
| `medical_records`, `fichas_clinicas`, `triages`, `signos_vitales`, `evaluaciones_abcde` | Esquema API original; conservar por compatibilidad |

La bitácora nueva **no exige migración adicional**, porque reutiliza `audit_events`, creada por `2026_11_12_110000_create_audit_events.php`. Sí corresponde ejecutar `php artisan migrate` en una instalación desde cero para que el conjunto completo de tablas esté presente.

### 4.1 Reglas y permisos

- **Administrador:** acceso a usuarios, roles, historial y funciones docentes. Puede eliminar casos sin ficha iniciada aunque pertenezcan a otro autor.
- **Docente:** ve todo el catálogo publicado, puede crear casos, asignar cualquier caso publicado, revisar fichas autorizadas según autoría/asignación y eliminar únicamente casos creados por él, siempre que no haya fichas iniciadas.
- **Estudiante:** accede solo a casos asignados a su nivel/curso y disponibles dentro del plazo; visualiza/guarda sus secciones, entrega la ficha una única vez y ve evaluación/PDF solo cuando está finalizada.
- La actividad de evaluación (rubrica clínica) no se determina exclusivamente por completar campos. Las propuestas automáticas comprueban presencia de datos, y el docente decide los criterios clínicos.
- Las fichas entregadas quedan bloqueadas para edición; los periodos y duración efectiva de la asignación limitan el guardado.
- El historial administrativo (`/admin/historial`) está restringido al rol administrador. **No** se expone la bitácora a estudiantes o docentes.

## 5. Registro de actividad y coordinación — actualización

### 5.1 Eventos registrados

| Código de acción | Responsable | Momento de registro | Detalle conservado |
|---|---|---|---|
| `crear_usuario`, `actualizar_usuario`, `activar_usuario`, `desactivar_usuario`, etc. | Administrador | Operaciones del panel administrativo | Usuario involucrado y operación |
| `crear_caso` | Docente o administrador | Caso creado con código definitivo | Código, título, nivel y estado |
| `eliminar_caso` | Autor o administrador autorizado | Caso eliminado sin fichas iniciadas | Código y título eliminados |
| `asignar_caso` | Docente o administrador | Nueva asignación | Código, nivel, curso, ID, estado y fecha de inicio |
| `actualizar_asignacion` | Docente o administrador autorizado | Cambio de asignación | Identificación y datos de la asignación actualizados |
| `eliminar_asignacion` | Docente o administrador autorizado | Eliminación sin fichas iniciadas | Identificación de la asignación eliminada |
| `entregar_ficha` | Estudiante | Entrega final CESFAM o Urgencias | Código, número de ficha y curso |
| `evaluar_ficha` | Docente o administrador autorizado | **Solo finalización**, no borradores | Código, número de ficha, estudiante, **nota y porcentaje de logro** |

La implementación utiliza `app/Support/ActivityAudit.php` y **transacciones** alrededor de la operación y del evento. La entrega de ficha bloquea la fila antes de marcarla como entregada, de manera que dos envíos concurrentes no generen dos entregas válidas. La evaluación definitiva también comprueba dentro de la transacción que no exista una pauta ya finalizada.

**No se registran borradores ni cada tecla o edición parcial.** La bitácora es de seguimiento académico y no almacena datos clínicos detallados, observaciones de pacientes, contraseñas ni payload JSON. El nombre del estudiante y la nota son datos educativos de acceso restringido al administrador. Definir con el liceo el plazo de conservación, responsable de acceso y política de respaldo.

### 5.2 Consultar el historial

Ruta web: `GET /admin/historial`. Filtros combinables: búsqueda de texto, **responsable** (administrador/docente/estudiante), tipo de acción y rango de fechas. La tabla presenta **15 eventos por página** y conserva los filtros en los enlaces de paginación. El parámetro antiguo `administrador` se sigue aceptando para enlaces guardados; el nuevo es `responsable`.

**Límite histórico:** `audit_events` no contenía todos estos eventos antes de esta actualización. No se deben inventar registros retroactivos a partir de casos existentes. La actividad nueva empezará a registrarse después de instalar el código actualizado.

### 5.3 Probar la auditoría de forma manual

1. Ingresar como administrador y comprobar que `Historial de actividad del sistema` carga y filtra por responsable.
2. Ingresar como docente. Crear un caso ficticio; revisar `crear_caso` con código.
3. Asignar ese caso a un curso; comprobar `asignar_caso` (y opcionalmente `actualizar_asignacion`).
4. Como estudiante autorizado, guardar las secciones obligatorias y entregar; comprobar una sola fila `entregar_ficha` cuyo responsable sea el estudiante.
5. Como docente revisor, finalizar la pauta (no solo borrador); comprobar `evaluar_ficha`, código, alumno, nota y porcentaje.
6. Repetir clic de entrega/finalización: la operación debe bloquearse; no deben generarse eventos duplicados.
7. Crear un segundo caso de prueba sin fichas iniciadas y eliminarlo; comprobar `eliminar_caso`. Para asignación sin fichas, comprobar `eliminar_asignacion`.
8. Buscar con filtros, forzar más de 15 eventos y verificar cambio de página.

## 6. Flujo de estudiante y evaluaciones

### 6.1 CESFAM — 3.º medio

Ruta de entrada: `GET /estudiante/casos`; resumen `GET /estudiante/caso/{codigo}`. Cuatro vistas en `/estudiante/3ro/`:

1. `antecedentes-3ro`: antecedentes, motivo de consulta, patologías, alergias y medicamentos.
2. `signos-vitales-3ro`: constantes, antropometría e interpretación.
3. `examen-fisico-3ro`: examen general y observaciones.
4. `citaciones-3ro`: controles, educación y entrega final.

Guardado: `PUT /3ro/casos/{code}/secciones/{section}`. Entrega: `POST /3ro/casos/{code}/entregar`. Se valida motivo de consulta y coherencia de registros de antecedentes; en citaciones se asigna el profesional según el usuario autenticado. Los hallazgos segmentarios del caso escritos por docente se muestran como información fija donde corresponde.

### 6.2 Urgencias — 4.º medio

Entrada y resumen equivalentes. Once secciones:

1. Admisión (`admision`).
2. Triage (`triage`).
3. Signos vitales (`signos-vitales`).
4. Evaluación ABCDE (`abcde`).
5. Anamnesis (`anamnesis`).
6. Examen físico (`examen-fisico`).
7. Procedimientos (`procedimientos`).
8. Medicamentos (`medicamentos_4to`).
9. Exámenes (`examenes`).
10. Acciones de enfermería (`acciones-enfermeria`).
11. Destino (`destino`).

Guardado: `PUT /4to/casos/{code}/secciones/{section}` con clave autorizada y JSON. Lectura: `GET /4to/casos/{code}/secciones`. Entrega: `POST /4to/casos/{code}/entregar` (requiere la sección `destino`). Los estados publicados en el listado se basan en las asignaciones y en el progreso del estudiante.

**Materiales de Exámenes:** hay registro de metadatos y nombres cuando fueron incluidos en el estado de la sección, pero **no hay implementación verificada de carga binaria en almacenamiento persistente ni endpoint seguro de descarga**. Un nombre visible no significa que el PDF/imagen esté disponible después de cerrar navegador, reiniciar servidor o cambiar de dispositivo.

### 6.3 Pautas de cotejo y notas

Cada nivel utiliza **nueve criterios**. La evaluación se guarda en `case_evaluations` con estado `borrador` o `finalizada`, resultados por criterio y comentarios del docente. En finalizada, el estudiante puede ver la retroalimentación general e individual y descargar su pauta PDF. El PDF respeta el carácter de cotejo (**Sí/No**), incorpora porcentaje, nota, observaciones por criterio, comentarios generales y líneas de firma.

**Escala configurada en código:** 20 % corresponde a nota **2,0**, 60 % a **4,0** y 100 % a **7,0**, con interpolación por tramos. Esta fórmula fue configurada a partir de las indicaciones recogidas durante el desarrollo y **debe ratificarse formalmente con el liceo**. Las sugerencias automáticas no sustituyen la revisión profesional docente. Las pautas manuales heredadas de 3.º pueden tener datos parciales y requieren validación antes de reportes oficiales.

## 7. Controladores documentados y puntos de extensión

Se añadieron comentarios PHPDoc de propósito de clase y función a los **19 controladores** existentes (sin contar la clase abstracta `Controller`). Los métodos críticos tienen documentación de autorización y efectos de auditoría. Este índice es el mapa de transferencia para Alexis:

| Controlador | Responsabilidad principal |
|---|---|
| `Delivery/AdminPanelController.php` | Dashboard y CRUD de usuarios, cambio de estado, roles, importación masiva, historial y filtros |
| `Delivery/TeacherController.php` | Casos, permiso de eliminación, asignaciones, indicadores docentes; registra creación/eliminación/asignación |
| `Delivery/StudentCaseController.php` | Listado, resumen, secciones y entrega definitiva de Urgencias |
| `Delivery/ThirdYearController.php` | Secciones CESFAM y entrega definitiva de 3.º |
| `Delivery/ReviewController.php` | Revisión de fichas, sugerencias de pauta, borradores y evaluación definitiva |
| `Delivery/ThirdReviewController.php` | Compatibilidad con evaluación manual previa de CESFAM |
| `Delivery/EvaluationPdfController.php` | Control de permisos y descarga de pauta PDF finalizada |
| `Delivery/SessionController.php` | Login web, sesiones y logout |
| `Api/AuthController.php` | Token Sanctum, login, cuenta y logout API |
| `Api/UserManagementController.php` | Gestión de usuarios a través de API |
| `Api/StudentCasesController.php` | Consulta de casos asignados por API |
| `Api/AdmisionController.php` | Registro de admisión de la API clínica original |
| `Api/TriageController.php` | Triage y asignación de box de la API original |
| `Api/SignosVitalesController.php` | Signos vitales de la API original |
| `Api/EvaluacionAbcdeController.php` | ABCDE de la API original (distinto de JSON web) |
| `Api/PasswordResetController.php` | Recuperación de contraseña por token de API |
| `Web/PasswordRecoveryController.php` | Solicitud y confirmación de recuperación web |
| `Web/WebController.php` | Pantallas, aliases y compatibilidad con rutas visuales previas |
| `Admin/UserController.php` | Controlador administrativo heredado; evaluar redundancia antes de retirar |

**Dónde comenzar cambios nuevos:** colocar reglas de negocio generales en `app/Support`, permisos en middleware/controladores y endpoints reales en `routes/web.php` o `routes/api.php`. Mantener `audit_events` en el mismo commit/transacción que la acción y añadir tests para roles y estados. Evitar duplicar la lógica de grabación en JavaScript sin verificar la respuesta del servidor.

## 8. Instalación local de Alexis — Windows / Docker Desktop

### 8.1 Requisitos

- Windows con Docker Desktop iniciado en modo **Linux containers** (y WSL2 disponible).
- PowerShell 5.1 o superior, puertos locales `8000`, `3307`, `8081`, `8025`, `1025` disponibles.
- Código de este entregable. **No** subir ni transferir el `.env` personal del equipo de Pablo.

### 8.2 Primer arranque del proyecto limpio

Desde la carpeta que contiene `docker-compose.yml`:

```powershell
cd C:\Ruta\FichaClinica
powershell -ExecutionPolicy Bypass -File .\INICIAR_LOCAL.ps1
```

El script crea `backend/.env` a partir del ejemplo si falta, construye la imagen, instala Composer en el contenedor, inicia servicios, genera `APP_KEY` si hace falta, ejecuta migraciones y seeder **solo de roles**. En una instalación de desarrollo aislada, si se necesitan cuentas de prueba:

```powershell
docker compose exec app php artisan db:seed --class=CredencialesPruebaSeeder
```

**Nunca ejecutar este seeder en producción.** Las contraseñas de ejemplo son débiles por diseño. No reutilizar cuentas QA en despliegue real.

### 8.3 Inicio habitual si ya está configurado

```powershell
cd C:\Ruta\FichaClinica
docker compose up -d
docker compose ps
```

Abrir: **aplicación** `http://localhost:8000/login`, **phpMyAdmin** `http://localhost:8081` y **Mailpit** `http://localhost:8025` (captura correos de desarrollo, no entrega a cuentas externas).

### 8.4 Actualización de código existente

1. **Respaldar código y base de datos** antes de copiar archivos. Los cambios de este paquete no borran tablas; la bitácora utiliza una migración ya existente.
2. Para el parche de esta entrega, utilizar el instalador PowerShell que copia solo archivos modificados y respalda los anteriores; en un traspaso completo, utilizar el ZIP limpio.
3. Con Docker activo, ejecutar:

```powershell
docker compose exec app php artisan migrate
docker compose exec app php artisan optimize:clear
```

4. Recargar el navegador con `Ctrl + F5`. Si aparece `419 Page Expired`, probar ventana privada y revisar sesión/cookies/CSRF. **No** borrar MySQL para solucionarlo.

### 8.5 Diagnóstico

```powershell
powershell -ExecutionPolicy Bypass -File .\VERIFICAR_LOCAL.ps1
docker compose logs --tail=100 app
docker compose exec app php artisan about
docker compose exec app php artisan migrate:status
docker compose exec app php artisan route:list
```

Para problemas: revisar `backend/storage/logs/laravel.log`, `docker compose ps`, variables `DB_HOST=db`, `APP_KEY`, permisos de `storage` y `bootstrap/cache`. Si Docker devuelve error de `dockerDesktopLinuxEngine`, encender Docker Desktop antes de tocar Laravel. **No ejecutar** `php artisan migrate:fresh`, `db:wipe` o `docker compose down -v` en una instalación con datos de pruebas que interese preservar.

## 9. Plan mínimo de pruebas de aceptación para Alexis y el liceo

| ID | Rol | Caso de prueba | Resultado esperado |
|---|---|---|---|
| QA-01 | Admin | Crear/editar/desactivar usuario; cambiar rol y curso | Permisos correctos, datos persistidos y actividad en historial |
| QA-02 | Docente | Crear caso; revisar catálogo completo | Caso con código único; evento `crear_caso`; otros casos visibles |
| QA-03 | Docente | Intentar eliminar caso ajeno | Respuesta 403; sin eliminar datos |
| QA-04 | Admin | Eliminar caso sin respuestas | Eliminación y bitácora; impedir eliminación con fichas iniciadas |
| QA-05 | Docente | Asignar caso creado por otro docente | Asignación válida y registro `asignar_caso` |
| QA-06 | Docente | Editar/anular asignación sin fichas iniciadas | Cambio/borrado consistente, auditado |
| QA-07 | Estudiante 3.º | Completar CESFAM y entregar | Cuatro secciones, bloqueo de edición y `entregar_ficha` |
| QA-08 | Estudiante 4.º | Completar Urgencias y entregar desde Destino | Once secciones, revisión de ABCDE, bloqueo y `entregar_ficha` |
| QA-09 | Estudiante | Enviar entrega por segunda vez | Error de bloqueo, sin segundo evento |
| QA-10 | Docente | Guardar borrador y finalizar evaluación | Solo finalización registra `evaluar_ficha` con nota y logro |
| QA-11 | Docente | Intentar finalizar pauta por segunda vez | Bloqueo; nota y bitácora inmutables |
| QA-12 | Estudiante | Consultar retroalimentación y PDF propio | Nueve comentarios y PDF de su evaluación finalizada |
| QA-13 | Estudiante | Intentar leer historial admin o PDF ajeno | Denegación 403/404; no filtrar notas de terceros |
| QA-14 | Admin | Historial con más de 15 eventos y filtros | Paginación estable, responsables de todos los roles |
| QA-15 | Docente | Revisar ABCDE y nombre de materiales de Exámenes | Objetos formateados, sin `[object Object]` |
| QA-16 | Alexis | SMTP real de recuperar contraseña | Correo real recibido, token válido, expiración y nueva clave |
| QA-17 | Alexis | Guardar JPG/PNG/PDF; salir y volver desde otro dispositivo | Archivo descargable solo por usuarios autorizados (pendiente de implementar) |

### 9.1 Pruebas automatizadas disponibles

`backend/tests/Feature/ActivityAuditTest.php` (añadida en esta entrega) comprueba creación/eliminación de caso, asignación, entrega única y evaluación final con nota, además de la protección del historial. Hay otras pruebas existentes de 4.º medio, usuarios, inicio de sesión, reinicio de contraseña y despliegue.

El archivo `backend/phpunit.xml` configura **SQLite en memoria** para tests. La imagen de Docker local conserva el `Dockerfile` original y **no instala explícitamente `pdo_sqlite` ni `dom`**. Antes de ejecutar la suite en contenedor, Alexis deberá habilitar extensiones de desarrollo necesarias (como `pdo_sqlite`/DOM) o usar un entorno de pruebas aislado que las tenga. **No apuntar los tests con `RefreshDatabase` a MySQL real del proyecto: podrían reiniciar tablas.** En un entorno de pruebas separado y apto:

```bash
cd backend
php artisan test --filter=ActivityAuditTest
php artisan test
```

La sintaxis (`php -l`) puede comprobarse sin esas extensiones; los casos funcionales siguen **pendientes de ejecución en un entorno habilitado**.

## 10. Tareas pendientes recomendadas para Alexis

### P0 — Antes de entregar o desplegar

1. **SMTP de recuperación de contraseña (responsable: Alexis).** Actualmente existen controladores y rutas Laravel (`/recuperar`, `/restablecer/{token}`, endpoints API) y Mailpit local. Configurar credenciales del proveedor de correo real por entorno, remitente autorizado, TLS, colas si corresponde, protección contra abuso y reintentos; validar emisión, enlace, expiración, usuario inactivo, token usado y ausencia de enumeración de correos. No describir la recuperación como operativa por correo externo hasta probarla.
2. **Almacenamiento permanente de adjuntos.** Implementar endpoint multipart, validación del tipo real/MIME y tamaño (JPG/PNG/PDF, límite a confirmar), volumen persistente o almacenamiento privado, tabla/metadatos, autorización por estudiante/asignación/docente, antivirus o política de validación, descarga autenticada y borrado/retención. No confiar en el nombre del archivo enviado por navegador. Verificar recuperación luego de recargar y recrear contenedores.
3. **Pruebas finales del liceo.** Confirmar campos y restricciones por nivel, controles de escalas, resultados, comentarios y formato PDF, con Macarena y docentes responsables; registrar aceptación.
4. **Copias de seguridad y recuperación** de MySQL y adjuntos; probar restauración sin perder historial o evaluaciones.
5. **Seguridad preproducción:** HTTPS, `APP_DEBUG=false`, `APP_ENV=production`, credenciales únicas, SMTP real, protección CSRF/token, seguridad de sesiones, cabeceras, límites de subida, acceso a logs y retención de datos.

### P1 — Calidad, mantenimiento y funcionamiento

6. Ejecutar pruebas automáticas en entorno aislado con extensiones PHP; revisar que tests antiguos no dependan de rutas o payloads históricos.
7. Completar auditoría de reglas clínicas y revisar escalas, peso de criterios, excepciones por turno/curso, correcciones docentes y publicación de retroalimentación.
8. Registrar de modo controlado errores de guardado de formularios, vencimientos de ficha y fallos de envío de correo sin exponer datos clínicos.
9. Normalizar representación de cursos históricos (`4° A`, `4.º Medio A`, etc.) antes de operar con matrícula real.
10. Revisar controladores y vistas heredadas (`Admin/UserController`, algunas vistas en `admin/`, rutas de compatibilidad) y eliminarlos **solo después** de buscar dependencias activas.
11. Decidir política de retención/depuración de `audit_events` y acceso a notas; considerar exportación administrativa solo si el liceo la solicita.
12. Validar compatibilidad real del `docker-compose.production.yml`, `Dockerfile.production`, Nginx, certificados y dominio. No dar por hecha la disponibilidad del despliegue GCP.

## 11. Configuración para producción: precauciones específicas

El archivo `docker-compose.production.yml` es **una propuesta separada** que monta un volumen `ficha_storage` en `storage` y `ficha_database` para MySQL, con Nginx en el puerto 80 destinado a estar **detrás de HTTPS**. No debe usarse sin revisión de red, secretos y almacenamiento de adjuntos. Los datos permanentes de formularios están en MySQL; los adjuntos, cuando se implementen, necesitarán una ubicación que sobreviva a despliegues y rollbacks. Si se migra a S3/GCS, sustituir el disco local y garantizar descargas autorizadas en Laravel.

El `.env` de un desarrollador **no** se incluye en el paquete limpio; se debe crear uno nuevo para cada entorno. No colocar las contraseñas del Compose de desarrollo en internet. `Mailpit` sirve exclusivamente para pruebas locales. Antes de desplegar, ejecutar migraciones controladas, backups, pruebas de rol, restauración y un checklist de aceptación.

## 12. Registro resumido de implementación y transferencia

| Etapa | Implementación actual | Estado |
|---|---|---|
| Base backend Alexis | Laravel/API, tablas clínicas originales, Docker, roles | Conservado e integrado |
| Frontend Pablo | Interfaces para roles, CESFAM y Urgencias | Integrado y ajustado en el ciclo de pruebas |
| Paneles | Usuarios, roles, casos, asignaciones, revisión | Implementados con pruebas locales parciales |
| Formularios | 3.º (4 secciones), 4.º (11 secciones), JSON por ficha | Implementados; validar todos los casos reales de prueba |
| Evaluaciones | Pautas de 9 criterios, sugerencias, notas, comentarios, PDF | Implementado; confirmar reglas con el liceo |
| Auditoría ampliada — esta entrega | Casos, asignaciones, entregas, evaluaciones finales con nota; filtros y paginación | **Código incorporado; pendiente QA de integración** |
| Comentarios PHPDoc — esta entrega | 19 controladores documentados y nuevo `ActivityAudit` | Incorporado |
| Consolidación documental — esta entrega | Un único informe más README corto y prueba de auditoría | Incorporado |
| Recuperación externa de contraseña | Estructura Laravel, SMTP local Mailpit | **Pendiente Alexis: SMTP real y prueba integral** |
| Archivos adjuntos permanentes | Nombre/metadatos y vista previa | **Pendiente** |
| Producción | Configuración propuesta de GCP/VM | **No certificado** |

---

**Guía rápida de continuidad:** empezar por la sección **8 (entorno)**, validar el nuevo flujo con **5.3 y 9**, y asignar primero a Alexis **SMTP y almacenamiento de adjuntos (sección 10)**. Si se observan diferencias entre este informe y código modificado posteriormente, el código y la prueba de integración más reciente prevalecen: actualizar este documento con fecha y decisión técnica.
