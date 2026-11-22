# Contrato de la API (v1) — Paso 1: Autenticación y usuarios

**Base URL (desarrollo):** `http://localhost:8000/api`
**Formato:** JSON. Enviar siempre `Accept: application/json` y `Content-Type: application/json`.
**Autenticación:** token Bearer → `Authorization: Bearer <access_token>`

## Convenciones de respuesta

| Código | Significado | Cuerpo |
|---|---|---|
| 200/201 | OK / creado | recurso o `{ "message": ... }` |
| 401 | Sin token o token inválido | `{ "message": "Unauthenticated." }` → ir al login |
| 403 | Sin permiso de rol / cuenta desactivada | `{ "message": "..." }` |
| 422 | Error de validación | `{ "message": "...", "errors": { "campo": ["mensaje"] } }` |
| 429 | Demasiados intentos | esperar 1 minuto |

Los recursos de usuario vienen dentro de `data` (excepto el login).

**Objeto usuario**
```json
{
  "id": 3, "name": "Ana Pérez", "email": "ana@liceo.cl",
  "rol": "estudiante",            // administrador | docente | estudiante
  "nivel_educativo": "4medio",    // "3medio" | "4medio" | null (solo estudiantes)
  "curso": "4° A",                // null si no es estudiante
  "activo": true,
  "created_at": "2026-10-26T12:00:00-03:00"
}
```

## Autenticación

### POST `/auth/login`
Body: `{ "email": "...", "password": "..." }`
200: `{ "access_token": "1|abc...", "token_type": "Bearer", "user": { ...usuario } }`
422: credenciales incorrectas (`errors.email`) · 403: cuenta desactivada o sin rol.

**Redirección sugerida según `user.rol`:** administrador → panel de usuarios;
docente → panel docente; estudiante → dashboard (versión según `nivel_educativo`).

### POST `/auth/logout`  🔒
200: `{ "message": "Sesión cerrada correctamente." }`

### GET `/auth/me`  🔒
200: `{ "data": { ...usuario } }` (útil para restaurar sesión al recargar la página)

### POST `/auth/forgot-password`
Body: `{ "email": "..." }`
200 siempre (no revela si el correo existe): `{ "message": "Si el correo está registrado, ..." }`
El correo trae un enlace a: `{FRONTEND_URL}/restablecer-contrasena?token=XXXX&email=correo%40liceo.cl`

### POST `/auth/reset-password`
Body: `{ "token", "email", "password", "password_confirmation" }`
Contraseña: mínimo 8 caracteres, con letras y números.
200: `{ "message": "Contraseña actualizada correctamente." }` · 422: token inválido/expirado (60 min) o contraseña débil.

## Administración (solo rol `administrador`) 🔒

### GET `/admin/users`
Query opcional: `search` (nombre o correo), `rol`, `nivel_educativo` (`3medio|4medio`),
`curso`, `activo` (**`1` o `0`**), `page`, `per_page` (máx. 100).
200: `{ "data": [usuario...], "links": {...}, "meta": { "current_page", "last_page", "total", ... } }`

### POST `/admin/users`
Body: `{ "name", "email", "password", "rol", "nivel_educativo", "curso" }`
`nivel_educativo` y `curso` son **obligatorios si `rol` = estudiante** y se ignoran en otros roles.
201: `{ "data": usuario }`

### GET `/admin/users/{id}` · PUT `/admin/users/{id}`
PUT acepta cualquier subconjunto de los campos de creación (`password` vacío = no cambiar).

### PATCH `/admin/users/{id}/rol-nivel`   (HU-12)
Body: `{ "rol", "nivel_educativo", "curso" }`

### PATCH `/admin/users/{id}/desactivar` · `/activar`   (HU-11)
Desactivar cierra sus sesiones y bloquea el login. No se puede desactivar la propia cuenta.

## Rutas clínicas existentes (se rediseñan en el Paso 2)
`/fichas/{id}/triage`, `/fichas/{id}/signos-vitales`, `/fichas/{id}/evaluacion-abcde`
→ ahora exigen token y rol `estudiante` o `docente`. **No construir pantallas sobre ellas todavía.**

## Pantallas que necesita el frontend en este paso
1. `/login` — 2. `/recuperar-contrasena` — 3. `/restablecer-contrasena?token&email`
4. Admin: listado de usuarios (filtros), crear, editar, asignar rol/nivel, activar/desactivar.
