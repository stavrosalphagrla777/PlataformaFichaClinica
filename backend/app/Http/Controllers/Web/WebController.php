<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

/**
 * Controlador de vistas y compatibilidad de URLs antiguas del frontend.
 * No concentra la persistencia principal: esta vive en controladores Delivery.
 */
class WebController extends Controller
{
    // --- AUTENTICACIÓN ---
    /** Muestra la pantalla de acceso web. */
    public function login()
    {
        return view('auth.login');
    }

    /** Muestra la solicitud de recuperación. */
    public function recuperar()
    {
        return view('auth.recuperar');
    }

    /** Muestra confirmación del proceso de recuperación. */
    public function confirmacion()
    {
        return view('auth.confirmacion');
    }

    /** Muestra flujo visual de cambio de clave. */
    public function restablecer()
    {
        return view('auth.restablecer');
    }

    // --- ESTUDIANTE ---
        /** Abre un panel de compatibilidad. */
        public function dashboard()
    {
        return view('estudiante.casos'); 
    }

    /** Muestra listado anterior de casos. */
    public function misCasos()
    {
        return view('estudiante.casos'); 
    }

    /** Muestra el catálogo antiguo. */
    public function casos()
    {
        return view('estudiante.casos');
    }

    /** Muestra ficha clínica por ruta anterior. */
    public function ficha($id)
    {
        return view('estudiante.detalle-caso', compact('id'));
    }

    /** Muestra vista de admisión de compatibilidad. */
    public function admision($id = null)
    {
        return view('estudiante.admision', compact('id'));
    }

    /** Muestra vista de triage de compatibilidad. */
    public function triage($id = null)
    {
        return view('estudiante.triage', compact('id'));
    }

    // --- ADMIN ---
    /** Muestra panel administrativo legado. */
    public function adminDashboard()
    {
        return view('admin.dashboard');
    }

    /** Muestra lista de usuarios legado. */
    public function adminUsuarios()
    {
        return view('admin.usuarios.index');
    }

    /** Muestra alta de cuenta legado. */
    public function adminUsuariosCrear()
    {
        return view('admin.usuarios.crear');
    }

    /** Muestra edición de cuenta legado. */
    public function adminUsuariosEditar($id = null)
    {
        return view('admin.usuarios.editar', compact('id'));
    }

    /** Muestra asignación de rol/nivel legado. */
    public function adminRolesNiveles()
    {
        return view('admin.roles-niveles');
    }
}