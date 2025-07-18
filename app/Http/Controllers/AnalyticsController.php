<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User; // <-- Importa el modelo User

class AnalyticsController extends Controller
{
    public function index(Request $request): Response
    {
        // --- FILTROS DE PERÍODO ---
        $days = $request->input('period', 30);
        $startDate = Carbon::now()->subDays($days)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        
 // --- ANÁLISIS DE VENTAS Y GANANCIAS (NUEVO) ---

        // Top 5 Productos por Ingresos, ahora usando la tabla 'product_review'
        $topProductsByRevenue = DB::table('product_review')
            ->join('products', 'product_review.product_id', '=', 'products.id')
            ->where('products.is_service', false)
            ->whereBetween('product_review.created_at', [$startDate, $endDate])
            ->select('products.name', DB::raw('SUM(product_review.price_at_time_of_review * product_review.quantity) as total_revenue'))
            ->groupBy('products.name')
            ->orderBy('total_revenue', 'desc')
            ->take(5)
            ->get();

        // Top 5 Servicios por Ganancia, usando la tabla 'product_review'
        $topServicesByProfit = DB::table('product_review')
            ->join('products', 'product_review.product_id', '=', 'products.id')
            ->where('products.is_service', true)
            ->whereBetween('product_review.created_at', [$startDate, $endDate])
            ->select('products.name', DB::raw('SUM((product_review.price_at_time_of_review - products.price) * product_review.quantity) as total_profit'))
            ->groupBy('products.name')
            ->orderBy('total_profit', 'desc')
            ->take(5)
            ->get();


        // --- ANÁLISIS DE ÓRDENES Y PAGOS (sin cambios por ahora) ---
        $totalOrders = DB::table('orders')->whereBetween('created_at', [$startDate, $endDate])->count();
        $ordersByStatus = DB::table('orders')->whereBetween('created_at', [$startDate, $endDate])->select('status', DB::raw('count(*) as count'))->groupBy('status')->get();

        // --- ANÁLISIS DE RENDIMIENTO DE EMPLEADOS (NUEVA LÓGICA MÁS DETALLADA) ---
        
        // Obtenemos el rendimiento solo de los usuarios que son 'Técnicos'
        $employeePerformance = User::role('Técnico') // Asume que el rol se llama 'Técnico'
            ->select('users.id', 'users.name')
            ->withCount(['orders' => function ($query) use ($startDate, $endDate) {
                $query->where('status', 'Completado')
                      ->whereBetween('updated_at', [$startDate, $endDate]);
            }])
            ->withAvg(['orders' => function ($query) use ($startDate, $endDate) {
                $query->where('status', 'Completado')
                      ->whereBetween('updated_at', [$startDate, $endDate]);
            }], DB::raw('EXTRACT(EPOCH FROM (updated_at - created_at))'))
            ->get()
            ->map(function ($employee) {
                // Formateamos el tiempo promedio de segundos a un formato legible
                $avg_seconds = $employee->orders_avg_extract_epoch_from_updated_at_created_at;
                $avg_days = $avg_seconds ? $avg_seconds / 86400 : 0;
                $employee->avg_completion_time_formatted = round($avg_days, 1) . ' días';
                return $employee;
            });
            
        // --- ENVIAR DATOS A LA VISTA ---
        return Inertia::render('Analytics/Index', [
            'employeePerformance' => $employeePerformance,
            'ordersAnalysis' => [
                'total' => $totalOrders,
                'by_status' => [
                    'labels' => $ordersByStatus->pluck('status')->map(fn ($status) => str_replace('_', ' ', ucfirst($status))),
                    'data' => $ordersByStatus->pluck('count'),
                ],
            ],
              // AÑADIMOS EL NUEVO ANÁLISIS DE VENTAS
            'salesAnalysis' => [
                'top_products_by_revenue' => [
                    'labels' => $topProductsByRevenue->pluck('name'),
                    'data' => $topProductsByRevenue->pluck('total_revenue'),
                ],
                'top_services_by_profit' => [
                    'labels' => $topServicesByProfit->pluck('name'),
                    'data' => $topServicesByProfit->pluck('total_profit'),
                ],
            ],
            
            'filters' => ['period' => intval($days)],
        ]);
    }
}