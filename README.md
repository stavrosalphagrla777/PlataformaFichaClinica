# Plataforma Digital Ficha Clínica TP

Entorno de simulación clínica digital para la especialidad de Enfermería del Liceo Bicentenario Hermanos Sotomayor Baeza (3.º y 4.º medio).

## 🛠️ Stack Tecnológico
- **Backend:** Laravel 11 (API REST) + Sanctum + Spatie/Laravel-Permission + MySQL 8
- **Frontend:** Blade + Tailwind CSS + Vite (Integración en curso)
- **Entorno:** Docker + Docker Compose
- **Docs:** `docs/` (contrato de la API en `docs/API.md`)

## 🚀 Levantar el entorno (Docker)

```bash
# 1. Copiar el archivo de entorno
cp backend/.env.example backend/.env

# 2. Construir y levantar los contenedores
docker compose up -d --build

# 3. Generar la clave de la aplicación
docker compose exec app php artisan key:generate

# 4. Ejecutar migraciones y seeders
docker compose exec app php artisan migrate:fresh --seed

# 5. Compilar assets del frontend
docker compose exec app npm install
docker compose exec app npm run build



