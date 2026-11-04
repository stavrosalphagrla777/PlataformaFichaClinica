<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class WebController extends Controller
{
    // --- AUTENTICACIÓN ---
    public function login()
    {
        return view('auth.login');
    }

    public function recuperar()
    {
        return view('auth.recuperar');
    }

    public function confirmacion()
    {
        return view('auth.confirmacion');
    }

    public function restablecer()
    {
        return view('auth.restablecer');
    }

    // --- ESTUDIANTE ---
        public function dashboard()
    {
        return view('estudiante.casos'); 
    }

    public function misCasos()
    {
        return view('estudiante.casos'); 
    }

    public function casos()
    {
        return view('estudiante.casos');
    }

    public function ficha($id)
    {
        return view('estudiante.detalle-caso', compact('id'));
    }

    public function admision($id = null)
    {
        return view('estudiante.admision', compact('id'));
    }

    public function triage($id = null)
    {
        return view('estudiante.triage', compact('id'));
    }

    // --- ADMIN ---
    public function adminDashboard()
    {
        return view('admin.usuarios.index'); // Temporal: usa la vista de usuarios
    }

    public function adminUsuarios()
    {
        return view('admin.usuarios.index');
    }

    public function adminUsuariosCrear()
    {
        return view('admin.usuarios.crear');
    }

    public function adminUsuariosEditar($id)
    {
        return view('admin.usuarios.editar', compact('id'));
    }

    public function adminRolesNiveles()
    {
        return view('admin.roles-niveles');
    }
}