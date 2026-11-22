# Diagnóstico reproducible una vez levantada la versión LOCAL en Docker.
# No cambia ni elimina datos; la prueba de login usa solo cuentas QA si fueron creadas.
$ErrorActionPreference = 'Stop'
Set-Location $PSScriptRoot
function CheckDocker {
    & docker @args
    if ($LASTEXITCODE -ne 0) { throw "Fallo: docker $($args -join ' ')" }
}
Write-Host '[1/5] Contenedores:'
CheckDocker compose ps
Write-Host '[2/5] Versiones PHP y Composer en la imagen de Alexis:'
CheckDocker compose exec -T app php -v
CheckDocker compose exec -T app composer --version
Write-Host '[3/5] Estado de las migraciones:'
CheckDocker compose exec -T app php artisan migrate:status
Write-Host '[4/5] Salud de Laravel y página de login:'
$base = 'http://127.0.0.1:8000'
$health = Invoke-WebRequest -UseBasicParsing "$base/up" -TimeoutSec 15
if ($health.StatusCode -ne 200) { throw "Laravel /up devolvió $($health.StatusCode)" }
$login = Invoke-WebRequest -UseBasicParsing "$base/login" -TimeoutSec 20
if ($login.StatusCode -ne 200) { throw "Laravel /login devolvió $($login.StatusCode)" }
Write-Host 'GET /up y GET /login: 200 OK'
Write-Host '[5/5] Comprobación básica de cuentas QA (si se ejecutó el seeder):'
foreach ($account in @('admin','docente','estudiante','estudiante3')) {
    $body = @{ login = $account; password = $account } | ConvertTo-Json
    try {
        $auth = Invoke-RestMethod -Method Post -Uri "$base/api/auth/login" -ContentType 'application/json' -Body $body -TimeoutSec 20
        if (-not $auth.access_token) { throw "No se devolvió access_token ($account)" }
        $headers = @{ Authorization = "Bearer $($auth.access_token)" }
        $identity = Invoke-RestMethod -Method Get -Uri "$base/api/auth/me" -Headers $headers -TimeoutSec 20
        if ($identity.data.rol -notin @('administrador','docente','estudiante')) { throw "Rol no válido ($account)" }
        if ($account -in @('estudiante','estudiante3')) {
            $cases = Invoke-RestMethod -Method Get -Uri "$base/api/estudiante/casos" -Headers $headers -TimeoutSec 20
            if ($null -eq $cases.data) { throw "No se entregó listado de casos ($account)" }
        }
        Invoke-RestMethod -Method Post -Uri "$base/api/auth/logout" -Headers $headers -TimeoutSec 20 | Out-Null
        Write-Host "  $account : API login, token, rol y logout OK"
    } catch {
        Write-Warning "  $account : no comprobado o error: $($_.Exception.Message)"
    }
}
Write-Host 'Diagnóstico básico terminado. Completar pruebas manuales de CRUD, evaluaciones, guardado y cambios de navegador.'
