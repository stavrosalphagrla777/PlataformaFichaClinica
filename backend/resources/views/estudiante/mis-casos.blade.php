<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Casos Clínicos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-blue-900 mb-4">Mis Casos Clínicos (Vista de Prueba)</h1>
        <p class="text-gray-600">Aquí irá la tabla de casos que hizo Pablo.</p>
        <a href="{{ route('estudiante.admision', ['id' => 1]) }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded">
            Ir a Admisión de Prueba (ID: 1)
        </a>
    </div>
</body>
</html>