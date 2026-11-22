# Ficha Clínica TP - Plataforma Digital de Ficha Clínica

Proyecto completo para el Hospital de Simulación Virtual del Liceo Bicentenario Hermanos Sotomayor Baeza, basado en el ZIP `PlataformaFichaClinica-main(3).zip` proporcionado el 19-11-2026. Se conserva la plataforma original de Laravel/PHP, MySQL 8 y las vistas de administrador, docente y estudiantes de 3.º y 4.º medio.

## Instalación local en Windows, sin afectar instalaciones anteriores

1. Iniciar Docker Desktop con contenedores Linux.
2. Descomprimir esta entrega en una carpeta nueva.
3. Abrir PowerShell en la carpeta donde está `docker-compose.yml` y ejecutar:

```powershell
powershell -ExecutionPolicy Bypass -File .\INICIAR_LOCAL.ps1
```

4. Crear los seis usuarios de prueba, solo en local:

```powershell
docker compose exec app php artisan db:seed --class=CredencialesPruebaSeeder --force
```

5. Abrir `http://localhost:8002/login`.

La nueva configuración Compose **no usa nombres de contenedor fijos**, utiliza el proyecto `ficha_clinica_entrega` y puertos libres por defecto: app `8002`, MySQL `3309`, phpMyAdmin `8083`, Mailpit `8027` y SMTP `1027`.

Para personalizar nombre y puertos, copiar `.env.example` de la raíz a `.env` en esa misma carpeta y editar los valores. Si se cambia el puerto web, actualizar `APP_URL` y `FRONTEND_URL` en `backend/.env`.

**Advertencia:** el nuevo proyecto Compose usa su propia base de datos vacía. No elimina ni importa automáticamente los datos previos. No utilizar `docker compose down -v` ni `php artisan migrate:fresh` si se quieren conservar datos.

## Cuentas ficticias de QA

| Usuario | Contraseña | Rol | Curso |
|---|---|---|---|
| `admin` | `admin` | administrador | - |
| `docente` | `docente` | docente | - |
| `estudiante3` | `password` | estudiante | 3.º Medio D |
| `estudiante4` | `password` | estudiante | 4.º Medio C |
| `estudiante3v` | `password` | estudiante | V3ro |
| `estudiante4v` | `password` | estudiante | V4to |

Las cuentas se crean únicamente mediante el seeder manual y no deben usarse en producción.

## Funcionalidades de esta entrega

- Menú desplegable de cursos aprobados por el liceo en administración, roles y asignación de casos.
- Campana de notificaciones antes del nombre, con contador, lista desplegable y destinos directos, **conservando el enlace lateral**.
- Catálogo de pautas por defecto y personalizadas, vista previa, PDF A4 y selección de pauta durante la asignación del caso.
- Footer institucional en las vistas de la aplicación.
- Seeder de seis usuarios y casos ficticios QA.
- Docker aislado de instalaciones anteriores, configurable sin renombrar contenedores antiguos.

## Documentación para Alexis

Consultar `docs/ENTREGA_CAMBIOS_Y_GUIA_ALEXIS_19-11-2026.docx` o `.md`, que incluyen registro de errores, lista de cambios, comandos de Docker, credenciales y pruebas recomendadas.

## Verificar y mantener

```powershell
docker compose ps
docker compose exec app php artisan migrate:status
docker compose exec app php artisan optimize:clear
```

Los datos clínicos simulados se guardan en MySQL y los adjuntos privados se almacenan en `backend/storage`. Respaldar ambos cuando corresponda.

**Pendiente de validación:** integración completa con MySQL, flujos en navegador, retroalimentación del cliente, SMTP real, carga concurrente y seguridad de despliegue. Las comprobaciones de código y PDF están documentadas en la guía; no sustituyen las pruebas funcionales.
