# Ficha Clínica TP — Plataforma educativa

Aplicación de simulación clínica (3.º CESFAM y 4.º Urgencias) desarrollada con Laravel, MySQL y Docker. Integra backend de Alexis e interfaces de Pablo. **Solo usar datos ficticios**.

**Documento de referencia para Alexis:** [`docs/INFORME_TECNICO_Y_TRANSFERENCIA.md`](docs/INFORME_TECNICO_Y_TRANSFERENCIA.md).

## Inicio local en Windows

1. Abrir Docker Desktop (contenedores Linux).
2. En PowerShell, desde la carpeta del proyecto:

   ```powershell
   powershell -ExecutionPolicy Bypass -File .\INICIAR_LOCAL.ps1
   ```

3. Abrir <http://localhost:8000/login>.
4. Si el entorno ya estaba configurado, basta `docker compose up -d`.

No ejecutar `migrate:fresh` ni `docker compose down -v` en una base de datos que deba conservarse.

**Pendiente antes de producción:** correo real para recuperación de contraseña (Alexis), almacenamiento persistente y descarga segura de adjuntos, pruebas de aceptación y configuración segura de despliegue. Ver el informe completo para rutas, datos, auditoría, pruebas, estados y responsabilidades.
