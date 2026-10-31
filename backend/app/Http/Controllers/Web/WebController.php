<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FichaClinica; // Importamos el modelo para luego usarlo

class WebController extends Controller
{
    // Esta es la pantalla principal: "Mis Casos Clínicos"
    public function misCasos()
    {
        // Por ahora, solo mostraremos una vista de prueba.
        // Más adelante, aquí consultaremos la BD para traer los casos del estudiante.
        return view('estudiante.mis-casos');
    }

    // Aquí iremos agregando las demás vistas (Admisión, Triage, etc.)
    public function admision($id)
    {
        return view('estudiante.admision', compact('id'));
    }
}
