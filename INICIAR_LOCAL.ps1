# Ejecutar en PowerShell, desde la carpeta que contiene docker-compose.yml.
# No modifica docker-compose.yml ni elimina volúmenes/MySQL existentes.
$ErrorActionPreference = 'Stop'
Set-Location $PSScriptRoot

if (-not (Get-Command docker -ErrorAction SilentlyContinue)) {
    throw 'Docker no está instalado o no está en PATH. Inicia Docker Desktop.'
}
if (-not (Test-Path './backend/.env')) {
    Copy-Item './backend/.env.example' './backend/.env'
    Write-Host 'Se creó backend/.env desde .env.example (configuración LOCAL).'
}

function DockerCheck {
    & docker @args
    if ($LASTEXITCODE -ne 0) { throw "Error de Docker (código $LASTEXITCODE). Revisa la salida anterior." }
}

Write-Host '1/5 Construyendo la imagen existente...'
DockerCheck compose build app
Write-Host '2/5 Instalando Composer DENTRO de la imagen (sin servicio adicional)...'
# Comprueba e instala dependencias Composer dentro del contenedor, aunque el ZIP ya contenga vendor.
DockerCheck compose run --rm --no-deps app composer install --no-interaction --prefer-dist
Write-Host '3/5 Iniciando los servicios del docker-compose.yml original...'
DockerCheck compose up -d
Write-Host '4/5 Generando APP_KEY solo si aún no existe...'
$envFile = Get-Content './backend/.env' -Raw
if ($envFile -match '(?m)^APP_KEY=\s*$') {
    DockerCheck compose exec -T app php artisan key:generate
}
Write-Host '5/5 Ejecutando migraciones y roles (sin borrar datos)...'
DockerCheck compose exec -T app php artisan migrate --seed --force
Write-Host ''
Write-Host 'Instalación local preparada. Para crear cuentas QA EJECUTA EXPLICITAMENTE:'
Write-Host 'docker compose exec app php artisan db:seed --class=CredencialesPruebaSeeder'
Write-Host ''
Write-Host ('App: http://localhost:' + $(if ($env:FICHA_APP_PORT) { $env:FICHA_APP_PORT } else { '8002' }) + '/login')
Write-Host 'Puertos por defecto: MySQL 3309 | phpMyAdmin 8083 | Mailpit 8027 (personalizables en .env de la raíz)'
