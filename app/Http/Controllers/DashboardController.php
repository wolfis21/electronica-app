<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Muestra el dashboard principal de la aplicación.
     */
    public function __invoke(): Response
    {
        // Órdenes: Obtenemos las últimas 5 que no estén completadas o canceladas
        $ordenesEnCurso = Order::whereNotIn('status', ['Completado', 'Cancelado'])
            ->latest() // Ordena por fecha de creación, las más nuevas primero
            ->take(5)   // Limita a 5 resultados
            ->get(['id', 'name_equip', 'status']); // Selecciona solo los campos que necesitamos

        // Revisiones: Obtenemos las últimas 5 (ajusta según tu lógica)
        // Si no tienes un modelo Review, puedes comentar o eliminar esta parte
        $revisionesEnCurso = Review::latest()
            ->take(5)
            ->get(['id', 'title', 'team_name']); // Ajusta los campos según tu modelo

        // Empleados: Obtenemos los últimos 5 usuarios registrados
        $listaEmpleados = User::latest()
            ->take(5)
            ->get(['id', 'name']);

        // Renderizamos la vista de Inertia 'Dashboard' y le pasamos los datos
        return Inertia::render('Dashboard', [
            'ordenesEnCurso' => $ordenesEnCurso,
            'revisionesEnCurso' => $revisionesEnCurso,
            'listaEmpleados' => $listaEmpleados,
        ]);
    }
}