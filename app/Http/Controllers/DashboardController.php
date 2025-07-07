<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        // --- 1. FILTROS DE PERÍODO ---
        $days = $request->input('period', 30);
        $startDate = Carbon::now()->subDays($days)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // --- 2. DATOS PARA LAS GRÁFICAS (ahora usan el filtro de período) ---
        
        // Gráfica 1: Órdenes por Estado en el período
        $ordersByStatus = DB::table('orders')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
        
        // Gráfica 2: Carga de trabajo (órdenes abiertas por usuario)
        $ordersByUser = DB::table('orders')
            ->join('users', 'orders.users_id', '=', 'users.id')
            ->whereNotIn('orders.status', ['Completado', 'Cancelado'])
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select('users.name', DB::raw('count(orders.id) as count'))
            ->groupBy('users.name')
            ->get();

        // --- 3. DATOS PARA TARJETAS DE INDICADORES (KPIs) ---
        $kpis = [
            'new_orders' => DB::table('orders')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'completed_orders' => DB::table('orders')->where('status', 'Completado')->whereBetween('updated_at', [$startDate, $endDate])->count(),
            'new_customers' => DB::table('customers')->whereBetween('created_at', [$startDate, $endDate])->count(),
        ];
        
        // --- 4. DATOS PARA LAS LISTAS ---
        
        // Lista de empleados con órdenes abiertas
        $activeEmployees = User::whereHas('orders', function ($query) {
                $query->whereNotIn('status', ['Completado', 'Cancelado']);
            })
            ->withCount(['orders' => function ($query) {
                $query->whereNotIn('status', ['Completado', 'Cancelado']);
            }])
            ->get();

        $listOrdersInProgress = Order::whereNotIn('status', ['Completado', 'Cancelado'])
            ->latest()->take(5)->get(['id', 'name_equip', 'status']);
        
        // --- 5. ENVIAR TODO A LA VISTA ---
        return Inertia::render('Dashboard', [
            'charts' => [
                'orders_by_status' => [
                    'labels' => $ordersByStatus->pluck('status')->map(fn ($status) => str_replace('_', ' ', $status)),
                    'data' => $ordersByStatus->pluck('count'),
                ],
                'orders_by_user' => [
                    'labels' => $ordersByUser->pluck('name'),
                    'data' => $ordersByUser->pluck('count'),
                ],
            ],
            'kpis' => $kpis,
            'lists' => [
                'orders_in_progress' => $listOrdersInProgress,
                'active_employees'   => $activeEmployees,
            ],
            'filters' => ['period' => intval($days)],
        ]);
    }
}