<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index(Request $request): Response
    {
        // --- FILTROS DE PERÍODO ---
        $days = $request->input('period', 30);
        $startDate = Carbon::now()->subDays($days)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // --- ANÁLISIS DE ÓRDENES Y PAGOS ---
        $totalOrders = DB::table('orders')->whereBetween('created_at', [$startDate, $endDate])->count();
        $ordersByStatus = DB::table('orders')->whereBetween('created_at', [$startDate, $endDate])->select('status', DB::raw('count(*) as count'))->groupBy('status')->get();
        $paymentsInPeriod = DB::table('payments')->whereBetween('created_at', [$startDate, $endDate]);
        $totalRevenue = (clone $paymentsInPeriod)->sum('amount');
        $totalTransactions = (clone $paymentsInPeriod)->count();
        $averageTicket = ($totalTransactions > 0) ? $totalRevenue / $totalTransactions : 0;
        $revenueByMethod = (clone $paymentsInPeriod)
            ->select('payment_method', DB::raw('sum(amount) as total'))
            ->groupBy('payment_method')
            ->get();

        // --- ANÁLISIS DE RENDIMIENTO DE EMPLEADOS ---
        $employeePerformance = DB::table('users')
            ->join('orders', 'users.id', '=', 'orders.users_id')
            ->where('orders.status', 'Completado')
            ->whereBetween('orders.updated_at', [$startDate, $endDate])
            ->select(
                'users.name',
                DB::raw('COUNT(orders.id) as completed_orders_count'),
                DB::raw('AVG(EXTRACT(EPOCH FROM (orders.updated_at - orders.created_at))) as avg_completion_seconds')
            )
            ->groupBy('users.id', 'users.name')
            ->orderBy('completed_orders_count', 'desc')
            ->get()
            ->map(function ($employee) {
                $avg_days = $employee->avg_completion_seconds ? $employee->avg_completion_seconds / 86400 : 0;
                $employee->avg_completion_time_formatted = round($avg_days, 1) . ' días';
                return $employee;
            });

        // --- ENVIAR DATOS A LA VISTA ---
        return Inertia::render('Analytics/Index', [
            'ordersAnalysis' => [
                'total' => $totalOrders,
                'by_status' => [
                    'labels' => $ordersByStatus->pluck('status')->map(fn ($status) => str_replace('_', ' ', ucfirst($status))),
                    'data' => $ordersByStatus->pluck('count'),
                ],
            ],
            'paymentsAnalysis' => [
                'total_revenue' => $totalRevenue,
                'average_ticket' => $averageTicket,
                'by_method' => [
                    'labels' => $revenueByMethod->pluck('payment_method'),
                    'data' => $revenueByMethod->pluck('total'),
                ],
            ],
            'employeePerformance' => $employeePerformance,
            'filters' => ['period' => intval($days)],
        ]);
    }
}