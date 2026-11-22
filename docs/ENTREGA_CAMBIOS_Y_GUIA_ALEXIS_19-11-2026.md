# Ficha Clínica TP - Entrega de cambios e instrucciones para Alexis

**Fecha:** 19 de noviembre de 2026  
**Base:** `PlataformaFichaClinica-main(3).zip` suministrado para esta entrega.  
**Tecnología:** Laravel/PHP, MySQL 8, Docker Compose, Blade y JavaScript.  
**Alcance:** correcciones de navegación, cursos, notificaciones, pautas, cuentas de QA y aislamiento de Docker. No se reemplazaron las funcionalidades clínicas existentes.

## 1. Modificaciones implementadas

### 1.1 Cursos confirmados por el liceo

- 3.º medio diurno: **3.º Medio D** y **3.º Medio F**.
- 4.º medio diurno: **4.º Medio C** y **4.º Medio G**.
- Vespertino: **V3ro** y **V4to**, cada uno asociado al nivel correspondiente.
- «Crear/editar usuario» del administrador usa un menú desplegable que cambia según el nivel y solo presenta los cursos anteriores.
- «Asignar roles y niveles» y «Asignación de casos» muestran las mismas opciones autorizadas.
- El servidor valida también los cursos de usuarios creados individualmente, en lote y mediante archivo CSV; no basta con ocultar opciones en el navegador.
- Las asignaciones históricas no se borran. «Todos los cursos» deja de ofrecerse para asignaciones nuevas, pero se conserva su compatibilidad de lectura para registros anteriores.
- La separación de los usuarios diurnos y vespertinos se mantiene en los listados y evaluaciones.

### 1.2 Notificaciones en el encabezado y menú lateral

- Se mantiene el enlace **Notificaciones** en el menú lateral, conforme a la decisión final.
- Se añadió una **campana en el encabezado, antes del nombre del usuario**, a las vistas autenticadas. Incluye contador de no leídas y lista desplegable con hasta seis avisos recientes.
- Los avisos tienen título, mensaje, fecha, estado de lectura y un botón para abrir su destino. «Ver todas» lleva a la bandeja existente.
- Un aviso de asignación lleva al **caso clínico específico** del estudiante, no solamente a su listado.
- Un aviso de evaluación finalizada lleva al caso correspondiente, donde el estudiante puede consultar su resultado y descargar su evaluación cuando está disponible.
- Los avisos de ficha entregada llevan al docente/administrador a **Evaluaciones** con la ficha preseleccionada mediante `?ficha=ID`.
- Los administradores reciben, además, avisos de alta de usuarios realizada por otros administradores; administradores y docentes responsables pueden recibir avisos de entregas. Se evita duplicar destinatarios de una misma entrega.
- Las redirecciones usan rutas internas para no depender del host/puerto y evitar redirecciones externas.
- Las notificaciones se almacenan en la tabla `notifications` existente; no requieren WebSockets, correo SMTP ni servicios nuevos.

### 1.3 Pautas de evaluación

- En **Pautas y rúbricas** se muestra un catálogo de las dos pautas predeterminadas y todas las pautas personalizadas existentes.
- Cada pauta dispone de **«Ver criterios»** y **«Descargar PDF»**. La vista previa incluye nombre, nivel, autor, nueve criterios y descripciones.
- El PDF de plantilla utiliza el generador A4 del proyecto, siguiendo el formato del PDF de evaluación: encabezado institucional, escala de notas, tabla de criterios, casillas y espacio de retroalimentación y firmas. No contiene notas de un alumno.
- Se conservan los formularios de creación de nuevas pautas y de asignación de pauta por defecto a un caso.
- La pantalla **Asignación de casos** permite elegir una pauta guardada del mismo nivel para **esa asignación específica**; dejarla vacía conserva la pauta del caso o la predeterminada del nivel.
- Se agregó la columna opcional `rubric_id` a `case_assignments`, sin borrar asignaciones anteriores.
- El panel de evaluaciones da prioridad a la pauta de la asignación y, si no hay, utiliza la pauta del caso; después se guarda una copia de criterios en la evaluación para conservar el historial.
- Por integridad académica, **no puede modificarse la pauta de una asignación que ya tiene fichas iniciadas**. Se debe crear una nueva asignación o renovarla.

### 1.4 Footer institucional

- Se incorporó el pie del login (nombre de Ficha Clínica TP, subtítulo y logotipo) a las vistas administrativas, docentes y estudiantiles, y a la bandeja de notificaciones.
- Las pantallas de autenticación ya tenían este pie y se conservan sin duplicarlo.

### 1.5 Usuarios de prueba

El seeder `CredencialesPruebaSeeder` crea o actualiza estas **seis cuentas ficticias** solo en entornos `local` o `testing`:

| Usuario | Contraseña | Rol | Curso |
|---|---|---|---|
| `admin` | `admin` | administrador | Sin curso |
| `docente` | `docente` | docente | Sin curso |
| `estudiante3` | `password` | estudiante | 3.º Medio D |
| `estudiante4` | `password` | estudiante | 4.º Medio C |
| `estudiante3v` | `password` | estudiante | V3ro |
| `estudiante4v` | `password` | estudiante | V4to |

- La ejecución del seeder es **manual** para evitar alterar usuarios reales al iniciar una base de datos antigua.
- Se crean dos casos clínicos ficticios de QA y asignaciones a las jornadas indicadas, sin datos clínicos de personas reales.
- No se sustituyen usuarios ajenos a QA: ante una colisión de nombre de usuario con correo que no termina en `@ficha.test`, el seeder se detiene.
- Estas credenciales son inseguras y **no deben cargarse en producción**.

### 1.6 Docker aislado

- Se eliminaron los nombres fijos `container_name: ficha_app`, `ficha_db`, `ficha_pma` y `ficha_mail`.
- El proyecto Compose predeterminado se denomina `ficha_clinica_entrega`, distinto de las instalaciones antiguas, lo que aísla contenedores, red y volumen MySQL.
- Puertos de host predeterminados para evitar colisiones: aplicación `8002`, MySQL `3309`, phpMyAdmin `8083`, Mailpit `8027`, SMTP de Mailpit `1027`.
- Los puertos y el nombre del proyecto se pueden personalizar en un archivo `.env` **en la raíz** de la carpeta, copiando `.env.example`.
- Los puertos internos de MySQL, Laravel y Mailpit no cambian; Laravel continúa conectando a `DB_HOST=db` y `DB_PORT=3306`.
- El ZIP no incluye `backend/.env`, datos de MySQL, archivos clínicos de prueba que había en una carpeta pública ni caché de vistas. Alexis debe configurar su propio entorno.

## 2. Registro de errores y correcciones

| ID | Comportamiento previo | Corrección |
|---|---|---|
| EC-01 | El curso se introducía como texto libre al crear usuarios. | Menú desplegable dependiente del nivel y comprobación en el servidor. |
| EC-02 | Se listaban cursos no confirmados (por ejemplo, A y B) para nuevas asignaciones. | Lista restringida a 3.º D/F, 4.º C/G, V3ro y V4to. |
| EC-03 | Solo se podía consultar la bandeja desde el menú lateral. | Campana con avisos desplegables y contador, manteniendo el menú. |
| EC-04 | El aviso de asignación llevaba al listado de casos. | Enlace directo al caso del estudiante. |
| EC-05 | El catálogo no tenía vista previa ni descarga PDF de pautas sin calificar. | Listado, detalle por pauta y PDF A4. |
| EC-06 | No se podía escoger una pauta específica desde la pantalla de asignación de casos. | Nuevo selector y referencia opcional por asignación en MySQL. |
| EC-07 | Los nombres de Docker chocaban con `ficha_mail` y `ficha_db` de instalaciones anteriores. | Compose con nombres de proyecto y puertos configurables; sin nombres de contenedor fijos. |
| EC-08 | El seeder incluía solo cuatro cuentas y cursos antiguos. | Seeder QA de seis usuarios con contraseñas acordadas y cursos vigentes. |
| EC-09 | El pie institucional estaba concentrado en las pantallas de autenticación. | Pie compartido en vistas de la aplicación. |
| EC-10 | Un URL de notificación absoluto podía depender de puertos anteriores. | Rutas relativas internas y saneamiento al abrir avisos. |

## 3. Instrucciones para Alexis - nueva instalación sin afectar la anterior

### Requisitos

- Windows 10/11, PowerShell y Docker Desktop iniciado, con contenedores Linux habilitados.
- Descomprimir el **ZIP completo** en una carpeta nueva. No copiar encima de la carpeta de otro proyecto ni reemplazar su `backend/.env`.
- Si utiliza datos existentes, respaldar primero **MySQL y `backend/storage`**. La entrega nueva usa un volumen independiente por defecto y no realiza una migración automática de los datos antiguos.

### Instalación nueva

Abrir PowerShell en la carpeta raíz `PlataformaFichaClinica-main` y ejecutar:

```powershell
powershell -ExecutionPolicy Bypass -File .\INICIAR_LOCAL.ps1
```

El script comprueba Docker, crea `backend/.env` desde `.env.example` si falta, construye la imagen, instala dependencias, inicia los servicios, genera `APP_KEY` cuando corresponde y ejecuta migraciones y roles sin borrar datos.

### Crear las cuentas de prueba (solo local)

```powershell
docker compose exec app php artisan db:seed --class=CredencialesPruebaSeeder --force
```

### Verificar servicios y migraciones

```powershell
docker compose ps
docker compose exec app php artisan migrate:status
docker compose exec app php artisan optimize:clear
```

### Accesos predeterminados

- Plataforma: `http://localhost:8002/login`
- phpMyAdmin: `http://localhost:8083`
- Mailpit: `http://localhost:8027`
- MySQL desde Windows: `localhost:3309`

### Si esos puertos también están ocupados

1. Crear un archivo `.env` en la **raíz**, a partir de `.env.example`.
2. Editar `COMPOSE_PROJECT_NAME` para hacerlo único (por ejemplo `ficha_alexis_qa`) y cambiar `FICHA_APP_PORT`, `FICHA_DB_PORT`, `FICHA_PMA_PORT`, `FICHA_MAIL_PORT`, `FICHA_SMTP_PORT` a puertos libres.
3. Si se cambia el puerto de aplicación, modificar también `APP_URL` y `FRONTEND_URL` en `backend/.env` para que coincidan con `http://localhost:PUERTO`.
4. Ejecutar de nuevo el script de inicio.

**No se recomienda renombrar o eliminar los contenedores antiguos.** Este Compose usa sus propios nombres. Nunca ejecutar `docker compose down -v`, `docker volume prune` ni `php artisan migrate:fresh` si hay datos que conservar.

### Si Alexis ya tiene una instalación anterior funcionando

- Mantenerla intacta y abrir la entrega en una carpeta diferente.
- Los puertos nuevos permiten ejecutar ambas copias si están disponibles. Cada Compose mantiene su propio volumen MySQL.
- Una base nueva requiere su seeder de QA para acceder con las seis cuentas.
- Para transferir usuarios/casos reales de una base previa, se debe planificar una copia y restauración controlada de MySQL y almacenamiento privado; **este ZIP no migra los registros existentes por sí solo**.

## 4. Lista de validación funcional para probar con Docker/MySQL

- [ ] Iniciar sesión con las seis cuentas y comprobar cada rol.
- [ ] Desde administrador, crear/editar un estudiante de 3.º: solo 3.º D/F y V3ro; 4.º: solo 4.º C/G y V4to.
- [ ] Intentar importar CSV con un curso no permitido y verificar rechazo sin inserciones parciales.
- [ ] Verificar la separación Diurnos/Vespertinos en tablas administrativas y docentes.
- [ ] Crear un caso y asignarlo al estudiante; confirmar campana y aviso lateral; pulsar el aviso y abrir el caso exacto.
- [ ] Entregar una ficha y revisar el aviso del docente/administrador; confirmar apertura de la ficha en Evaluaciones.
- [ ] Finalizar evaluación y comprobar el aviso y el PDF del estudiante.
- [ ] Crear una pauta de nueve criterios, verla en el catálogo, consultar la vista previa y descargar el PDF de plantilla.
- [ ] Asignar una pauta específica desde Asignación de casos; verificar que sus nueve criterios se muestran en Evaluaciones y que queda registrada por asignación.
- [ ] Iniciar una ficha y verificar que ya no se puede cambiar la pauta de esa asignación.
- [ ] Comprobar footer y campana en vistas de administración, docente, 3.º, 4.º y tamaños de pantalla móviles.
- [ ] Comprobar que la instalación anterior conserva sus contenedores, datos y puertos.

## 5. Comprobaciones realizadas en esta entrega

**Realizado:** inspección del ZIP base; comprobación de sintaxis de archivos PHP modificados (`php -l`); comprobación sintáctica de JavaScript modificado (`node --check`); carga de rutas Laravel (`php artisan route:list`) que muestra las siete rutas de pautas; generación manual de un PDF A4 de plantilla, inspección de metadatos y representación visual de sus dos páginas.

**No comprobado aún:** pruebas PHPUnit automatizadas (al entorno de comprobación le faltan extensiones PHP `dom`, `mbstring` y `xmlwriter`); ejecución integral con MySQL, inicio de sesión en navegador, subida de archivos, notificaciones con usuarios reales, flujos completos de evaluación y pruebas de concurrencia. Los nuevos tests unitarios de cursos y PDF se incluyen para ejecutarlos en Docker.

**Comandos de pruebas que Alexis puede ejecutar en Docker:**

```powershell
docker compose exec app php artisan test --filter=CourseAccessTest
docker compose exec app php artisan test --filter=RubricPdfTemplateTest
```

## 6. Archivos y componentes principales

- `backend/app/Support/CourseAccess.php` - reglas de cursos.
- `backend/resources/views/admin/operativo/usuario-form.blade.php` - desplegable administrador.
- `backend/public/js/admin-roles-reales.js` y `backend/public/js/docente/asignaciones.js` - listas de cursos y pauta por asignación.
- `backend/app/Http/Controllers/Delivery/TeacherController.php` - validación y persistencia de asignaciones.
- `backend/app/Http/Controllers/Delivery/RubricController.php` - catálogo, vista previa y descarga de pautas.
- `backend/app/Support/ClinicalRubricPdf.php` - PDF sin calificar y evaluaciones previas.
- `backend/resources/views/docente/rubricas.blade.php` y `rubrica-detalle.blade.php` - interfaz de pautas.
- `backend/database/migrations/2026_11_19_000003_add_rubric_to_case_assignments.php` - migración nueva no destructiva.
- `backend/resources/views/shared/{notification-center,app-extras,app-footer}.blade.php` - campana y footer compartidos.
- `backend/public/{css/app-extras.css,js/app-extras.js}` - presentación e interacción de campana y footer.
- `backend/app/Support/CaseNotifications.php` y `backend/app/Http/Controllers/Delivery/NotificationController.php` - eventos y destinos de avisos.
- `backend/database/seeders/CredencialesPruebaSeeder.php` - seis usuarios y casos ficticios para QA.
- `docker-compose.yml`, `INICIAR_LOCAL.ps1`, `.env.example` raíz y `backend/.env.example` - configuración local aislada.

## 7. Advertencias de entrega

- Las credenciales `admin/admin` y las demás son solamente de prueba.
- No subir fichas con datos clínicos reales al entorno de ensayo.
- Antes de producción: validar reglas clínicas y pautas con el liceo, configurar HTTPS y SMTP, diseñar respaldos de MySQL y almacenamiento, y ejecutar pruebas de navegador y carga con usuarios representativos.
