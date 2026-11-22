# Ficha Clínica TP - Registro de errores y correcciones

**Base revisada:** `PlataformaFichaClinica-main(2).zip`, 19/11/2026.

**Alcance de la verificación:** lectura de código, sintaxis de PHP y JavaScript, compilación de 51 plantillas Blade, comprobación de rutas Laravel y siete pruebas de aislamiento de cursos. No se ejecutaron pruebas integrales con la base de datos real ni navegador. Un fallo informado por el usuario puede quedar mitigado a nivel de código sin que podamos afirmar que fue reproducido en un entorno de producción.

## Errores y riesgos corregidos

| ID | Área | Error o riesgo encontrado | Corrección aplicada | Estado |
| - | - | - | - | - |
| E-01 | Exámenes, 4.º | El usuario informó que un material subido a una solicitud aparecía repetido cuando había varias solicitudes. No fue posible reproducirlo en el navegador de este entorno. | Cada carga opera únicamente sobre la solicitud seleccionada; se añadió exclusión de envíos simultáneos y control de duplicados por ID/ruta. | Mitigado en código; validar en navegador con tres solicitudes |
| E-02 | Exámenes, 4.º | El servidor exponía nuevas cargas por una carpeta pública. | Nuevas cargas al disco privado `local` y descarga por ruta protegida, validada para el estudiante dueño de la ficha. | Corregido para cargas nuevas |
| E-03 | Subida de archivos | PHP no configuraba los 10 MB admitidos por Laravel, por lo que las cargas podían fallar por límites menores del contenedor. | Se añadió `php-uploads.ini` a Dockerfile local y de producción, sin cambiar `docker-compose.yml`. | Corregido en configuración; requiere reconstruir imagen |
| E-04 | Signos vitales, 3.º | La vista intentaba guardar por una API diferente y contenía una credencial de acceso incrustada. | Guarda por la ruta de sección autorizada con sesión y CSRF; se elimina credencial. | Corregido en código |
| E-05 | ABCDE, 4.º | La vista guardaba por API con credencial incrustada y sobrescribía contexto de ficha; riesgo de no persistir en el caso real. | Guardado por el puente de secciones Laravel, sin credencial incrustada. | Corregido en código |
| E-06 | Antropometría, ambos niveles | El IMC se completaba automáticamente pese a la petición de que el estudiante lo calcule. | Fórmula visible, campo manual, mensaje de discrepancia y validación de servidor al guardar cuando peso y talla existen. | Corregido en código |
| E-07 | Antecedentes, 3.º | Permanecía el campo «Reacción» en alergias. | Solo se solicita nombre de alergia. | Corregido en formulario |
| E-08 | Signos vitales | Glasgow no tenía la misma consideración de campo opcional en ambos niveles. | Registro opcional con límites de puntaje cuando se informa. | Corregido en formularios |
| E-09 | Cursos y asignaciones | Los cursos anteriores no incluían 3.º F, 4.º G y las categorías V3ro/V4to. Las asignaciones «Todos los cursos» podían mezclarse si no se distinguía jornada. | Se añadieron cursos y separación de acceso por jornada en backend, más selectores en vistas administrativas y docentes. | Corregido en código |
| E-10 | Ficha clínica de 3.º | Existía una ruta de guardado que creaba registros secundarios con un `paciente_id` fijo, ajeno al caso simulado. | Eliminada esa escritura; se utiliza `StudentCase` y `CaseSection`, vinculados a estudiante y asignación. | Corregido en código |
| E-11 | Revisión y evaluación docente | Los listados no contaban con alternancia de jornada y podían mezclar diurnos y vespertinos. | Filtros explícitos de jornada y controles coherentes con los cursos. | Corregido en interfaz |
| E-12 | Validación de rúbricas CESFAM | La sugerencia automática consideraba obligatorios peso, talla e IMC, aunque esos datos son opcionales. | La comprobación distingue los signos obligatorios de la antropometría opcional. | Corregido en código |

## Funciones nuevas (no son errores previos)

- Rúbricas configurables de nueve criterios, asignables a casos no iniciados; la evaluación guarda una copia de los criterios para conservar el PDF histórico.
- Cronómetro de tiempo restante tomado del plazo y duración del servidor, con cierre de edición controlado en backend.
- Renovación mediante una nueva asignación independiente; no modifica fichas ni calificaciones anteriores.
- Notificaciones internas al estudiante por nueva asignación y por evaluación finalizada; al docente por ficha entregada. No envía correos.
- Colores solicitados de signos vitales: frecuencia cardíaca en rojo y temperatura en azul para ambos niveles. Son colores de identificación, no nuevos rangos clínicos.

## Pendientes que no se deben improvisar

1. Confirmación del liceo sobre rangos clínicos actualizados y periodicidad de control (se mencionaron intervalos de 1 a 4 horas, pero no se definió regla universal). Se conservan los límites y alertas anteriores hasta validación.
2. Definición clínica de temperatura y presión arterial; no se introducen nuevos umbrales sin documentación.
3. Reglas exactas para pausar, prorrogar o reiniciar cronómetro y casos; se implementó renovación por nueva asignación.
4. Selección definitiva de eventos de notificación docente y posible envío de correo.
5. Posibilidad de rúbricas con más o menos de nueve criterios: la primera versión conserva exactamente nueve para mantener la escala y los PDF existentes.
6. Archivos antiguos guardados en carpeta pública: no se migran automáticamente; requieren inventario, movimiento seguro y limpieza de enlaces públicos. Revocar cualquier token API que hubiera quedado expuesto en vistas anteriores.
7. Pruebas funcionales de concurrencia y base de datos real, especialmente al cargar un solo archivo en una de tres solicitudes, entregar dos veces, asignaciones vespertinas y volver a abrir una ficha.

## Casos de prueba recomendados

- **CP-01 (regresión del defecto informado):** crear tres solicitudes de exámenes distintas; elegir la segunda; adjuntar un PDF; guardar y recargar. La primera y tercera deben indicar cero adjuntos y la segunda uno. Repetir con JPG/PNG y botón de guardar doble pulsación.
- **CP-02:** crear V3ro y 3.º D; asignar caso a «Todos los cursos», verificar que solo lo vea 3.º D; asignar otro a V3ro y comprobar aislamiento inverso. Igual para 4.º C/V4to.
- **CP-03:** guardar una ficha 3.º desde Signos vitales, recargar y verificar persistencia en MySQL; no debe crearse un paciente ficticio con ID fijo.
- **CP-04:** verificar IMC 70 kg / 1,75 m = 22,86. El valor 22,9 debe aceptarse y 27,0 debe mostrar alerta. Repetir ambos niveles.
- **CP-05:** dejar Glasgow vacío y guardar; repetir con 15 y un valor inválido.
- **CP-06:** publicar una asignación y revisar notificación del estudiante; entregar la ficha y revisar notificación docente; finalizar evaluación y comprobar aviso del estudiante.
- **CP-07:** crear una pauta con nueve criterios, asignar a caso sin iniciar, evaluar, generar PDF y comprobar que contiene la pauta seleccionada.
- **CP-08:** renovar una asignación terminada y comprobar que se mantienen intactas la ficha y nota previas.
- **CP-09:** comprobar que un estudiante no puede descargar archivos de otro caso o cuenta, ni acceder a archivos privados por URL pública.
- **CP-10:** controlar tiempo restante y que guardar cuando vence el plazo responda con rechazo del servidor.

**Atención:** no implementar este parche directamente en producción sin respaldo de MySQL, ejecución de las dos migraciones nuevas y pruebas de regresión en el entorno local.

## Revisión adicional de la entrega completa

| ID | Hallazgo adicional | Solución aplicada | Comprobación pendiente |
| - | - | - | - |
| E-13 | En Exámenes se afirmaba que el archivo adjunto solo duraba durante la sesión, pese a que la carga nueva ya se guardaba en disco privado. | Se corrigió el texto, se recupera la URL privada del archivo al reabrir y se entrega un visor autorizado por sesión. | Subir, cerrar sesión, volver a ingresar y abrir JPG/PDF desde la solicitud. |
| E-14 | En el gráfico de 4.º medio los colores de frecuencia cardíaca y temperatura seguían invertidos respecto del acuerdo del 17/11. | FC pasó a rojo y temperatura a azul en la leyenda y serie del gráfico; también se conservan las etiquetas de ambos niveles. | Revisar la gráfica con valores de ejemplo. |
| E-15 | Una revisión alternativa de fichas de 3.º medio aún mostraba un bloque JSON completo. | Se reemplazó por la presentación legible compartida, sin inyectar contenido de estudiantes en HTML. | Abrir todas las secciones desde `/docente/revision-3ro/{record}`. |
| E-16 | Diversas pantallas incluían mensajes heredados de prototipo o de mecanismos internos de guardado. | Se cambiaron por mensajes de usuario y se dejaron las explicaciones técnicas como comentarios dentro del código. | Recorrer formularios y revisión en navegador. |
| E-17 | README y archivos de configuración de ejemplo estaban desactualizados o incluían una dirección de correo personal como remitente de muestra. | Se actualizaron guías y se usan remitentes de ejemplo; se mantienen los archivos de configuración real fuera del ZIP. | Configurar SMTP autorizado antes de producción. |

**Persistencia validada estáticamente:** la base entregada por Alexis ya contenía `4to-backend-bridge.js`, `tercero-bridge.js`, controladores `StudentCaseController` y `ThirdYearController`, modelo `CaseSection` y migraciones. Se verificó que los formularios restauran desde `CaseSection` y guardan por rutas autenticadas de Laravel. La existencia del código no reemplaza la prueba en MySQL y navegador.

**Nota sobre archivos adjuntos:** el almacenamiento privado es persistente solo si el volumen `storage` de Docker permanece montado, respaldado y no se elimina. Los metadatos del archivo se guardan en la sección de MySQL; su contenido binario queda en el disco privado. Las cargas anteriores deben revisarse por separado.
