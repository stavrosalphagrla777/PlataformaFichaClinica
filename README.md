# Plataforma Digital Ficha Clínica TP

Entorno de simulación clínica digital para la especialidad de Enfermería del
Liceo Bicentenario Hermanos Sotomayor Baeza (3.º y 4.º medio).

- **Backend:** Laravel 12 (API REST) + Sanctum + spatie/laravel-permission + MySQL 8
- **Frontend:** carpeta `frontend/` (a cargo de Pablo)
- **Docs:** `docs/` (contrato de la API en `docs/API.md`)

## Levantar el backend (Docker)

```bash
cp backend/.env.example backend/.env
docker compose up -d --build
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate:fresh --seed
```

| Servicio | URL |
|---|---|
| API | http://localhost:8000/api |
| phpMyAdmin | http://localhost:8080 (usuario `ficha` / `secret`) |
| Correos de prueba (Mailpit) | http://localhost:8025 |

### Usuarios de prueba (contraseña `password123`)

| Correo | Rol | Nivel |
|---|---|---|
| admin@liceo.cl | administrador | — |
| docente@liceo.cl | docente | — |
| estudiante@liceo.cl | estudiante | 4.º medio |
| estudiante3@liceo.cl | estudiante | 3.º medio |

### Ejecutar los tests

```bash
docker compose exec app php artisan test
```

## Flujo de trabajo (Git)

- `main`: solo código que funciona y pasa los tests.
- Una rama por historia: `feature/HU-01-login`, `feature/HU-03-admision`...
- Pull Request con revisión del compañero antes de mezclar a `main`.
