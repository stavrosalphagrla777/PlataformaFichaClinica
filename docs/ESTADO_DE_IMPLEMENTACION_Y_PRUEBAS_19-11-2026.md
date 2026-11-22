# Estado de implementación y plan de pruebas - 19/11/2026

## Origen y alcance

Este proyecto completo parte del archivo `PlataformaFichaClinica-main(2).zip` proporcionado como versión actual de Alexis. Sobre ese código se integraron los cambios y correcciones solicitados en la reunión del 17/11/2026, además de una revisión final de la interfaz y la persistencia.

**No es un parche.** El ZIP de entrega contiene la raíz completa `PlataformaFichaClinica-main`, el `backend` original, `vendor`, Docker, documentación y migraciones. No contiene una base de datos MySQL exportada ni el archivo de configuración privado `.env`.

## Arquitectura de almacenamiento

- **MySQL 8:** usuarios, roles, casos, asignaciones, fichas, secciones (`student_cases`, `case_sections`), evaluaciones, auditoría, notificaciones y rúbricas.
- **Laravel:** autenticación de sesión y CSRF, validación y autorización por rol, curso y asignación.
- **3.º medio:** formularios CESFAM conservan el estado temporal en el navegador para capturar valores, pero `tercero-bridge.js` envía las secciones a `ThirdYearController::save`, que las guarda en `CaseSection` de MySQL. La apertura recupera el JSON desde el servidor.
- **4.º medio:** formularios originales escriben un JSON temporal al almacenamiento del navegador. `4to-backend-bridge.js` intercepta los guardados de la sección activa, los envía a `StudentCaseController::saveSection`, confirma la respuesta y rehidrata datos desde `CaseSection` al reabrir. Los borradores de interfaz que aún no se han guardado no sustituyen a los registros de la base.
- **Archivos de Exámenes:** bytes en el almacenamiento privado `storage/app/private/clinical-exams/...` (disco `local` según configuración Laravel); metadatos, nombre, URL autorizada y asociación a la solicitud en la sección JSON guardada en MySQL. Los archivos se pueden volver a abrir después de cerrar el navegador, siempre que se conserve el volumen de almacenamiento.

El término "local" en un `storage_type` de las secciones representa la compatibilidad del formulario y **no indica que MySQL se omita**. No obstante, no se ejecutó una prueba de lectura/escritura de una instalación MySQL real en este entorno de análisis.

## Funcionalidades integradas

- Alergias CESFAM con nombre, sin campo Reacción.
- IMC manual de 3.º y 4.º medio, fórmula visible, contraste con peso y talla y aviso si existe discrepancia. Glasgow opcional.
- Frecuencia cardíaca identificada en rojo y temperatura en azul; gráfico de 4.º medio coherente con la identificación.
- Cursos diurnos 3.º D/F y 4.º C/G. Categorías vespertinas V3ro y V4to separadas en validaciones, listados y asignaciones, reutilizando los módulos de su nivel.
- Rúbricas de nueve criterios creadas y asignadas por docentes, con preservación de la evaluación usada en la exportación PDF.
- Tiempo restante del caso calculado desde el servidor y renovación mediante una nueva asignación sin sobrescribir entregas previas.
- Notificaciones internas para estudiantes y docentes según asignaciones, entregas y evaluaciones.
- Carga de materiales de Exámenes vinculada a una sola solicitud, protección contra doble envío y descarga privada autorizada.
- Revisión de fichas docentes en presentación legible, incluida la pantalla alternativa de 3.º medio.
- Mensajes de interfaz comprensibles, sin instrucciones para programadores ni afirmaciones obsoletas sobre almacenamiento temporal.

## Instalación local para Alexis

Desde la raíz del proyecto en Windows, con Docker Desktop iniciado:

```powershell
powershell -ExecutionPolicy Bypass -File .\INICIAR_LOCAL.ps1
```

Si ya existe instalación y base de datos:

```powershell
docker compose up -d --build app
docker compose exec app php artisan migrate --force
docker compose exec app php artisan optimize:clear
```

**Importante:** antes de migraciones en instalaciones existentes, respaldar la base de datos y los archivos de `backend/storage`. No ejecutar `migrate:fresh` ni `docker compose down -v`. El script no sobrescribe `backend/.env` cuando ya está presente. Revisar las variables de SMTP, dominio y credenciales antes de implementar en producción.

## Pruebas funcionales mínimas

1. Crear usuario 3.º D y V3ro, crear una asignación diurna y otra vespertina y verificar que cada estudiante solo acceda a su jornada. Repetir con 4.º C y V4to.
2. Abrir una ficha de 3.º medio, guardar signos vitales, cerrar sesión, volver a entrar y confirmar que los datos se restauran; comprobar la fila correspondiente de `case_sections` mediante phpMyAdmin.
3. Repetir en 4.º medio con Admisión, ABCDE y una sección con historial. Confirmar que los registros guardados reaparecen desde MySQL después de cerrar y volver a abrir el navegador.
4. Calcular IMC para 70 kg y 175 cm: 22,86. Probar 22,9 (válido por redondeo) y 27,0 (alerta), y probar campo Glasgow vacío y lleno.
5. Crear tres solicitudes distintas de Exámenes, elegir únicamente la segunda, subir JPG/PDF, guardar, recargar y verificar que solo la segunda muestra un archivo. Probar dos clics rápidos en Guardar.
6. Cerrar sesión y volver a ingresar: el adjunto anterior debe poder abrirse desde la URL protegida. Un estudiante ajeno no debe poder descargarlo.
7. Revisar ficha de 3.º desde el panel docente: los campos deben verse como información clínica legible, no JSON.
8. Crear una rúbrica de nueve criterios, asignarla a un caso no iniciado, evaluar al estudiante, generar PDF y verificar que la pauta y nota estén correctas.
9. Verificar notificaciones por asignación, entrega y evaluación; comprobar cronómetro y prohibición de edición después del vencimiento.
10. Comprobar que los enlaces horizontales y laterales de 4.º permiten navegar entre todas las secciones conservando el caso.
11. Confirmar que el historial administrativo muestra las operaciones y paginación, y verificar que ninguna tabla mezcle V3ro/V4to con diurnos.

## Límites y pendientes antes de producción

- Configurar SMTP institucional real y validar la recuperación de contraseña extremo a extremo.
- El liceo debe proporcionar y confirmar los rangos y alertas clínicas actualizados. El intervalo de control de signos vitales de 1 a 4 horas no se implementa como regla universal hasta su validación.
- Confirmar las reglas pedagógicas de renovación, temporizador y posibles notificaciones adicionales.
- Inventariar y migrar posibles adjuntos **anteriores** guardados en carpetas públicas; no se trasladan de forma automática.
- Ejecutar pruebas reales con MySQL y navegador, pruebas de permisos y concurrencia (~40 estudiantes), y restauración de respaldos.
- Confirmar GCP o VPS, presupuesto, dominio, HTTPS, almacenamiento persistente y responsables de soporte con el liceo. La copia no constituye un despliegue final validado.

## Comprobaciones estáticas realizadas

El análisis incluye la validación de sintaxis PHP y JavaScript, compilación estática de Blade y listado de rutas Laravel. Las comprobaciones no equivalen a pruebas de integración ni sustituyen las pruebas de aceptación con Alexis y el liceo.
