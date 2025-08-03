<?php

namespace App\Http\Controllers;

use App\Models\CommissionPayout;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;

class CommissionController extends Controller
{
    // ... el método index() y payout() se mantienen igual ...

    public function index(Request $request)
    {
        // --- 1. Calcular el Período Actual ---
        $period = $request->query('period', 'first_half');
        $now = Carbon::now();
        list($startDate, $endDate) = $this->getPeriodDates($period, $now);

        // --- 2. Calcular Comisiones Pendientes ---
        $allCommissions = $this->calculateCommissionsForPeriod($startDate, $endDate);
        
        $paidUserIds = CommissionPayout::where('period_start', $startDate->toDateString())
            ->where('period_end', $endDate->toDateString())
            ->pluck('user_id')
            ->all();

        $pendingCommissions = array_filter($allCommissions, function ($commission) use ($paidUserIds) {
            return !in_array($commission['user_id'], $paidUserIds);
        });

        // --- 3. Obtener Historial Filtrable y Paginado ---
        $historyQuery = CommissionPayout::with(['user:id,name', 'payer:id,name'])
            ->orderBy('paid_at', 'desc');

        if ($request->filled('filter_user_id')) {
            $historyQuery->where('user_id', $request->filter_user_id);
        }

        $history = $historyQuery->paginate(15)->withQueryString();

        return Inertia::render('Commissions/Index', [
            'pendingCommissions' => array_values($pendingCommissions),
            'history' => $history,
            'users' => User::select('id', 'name')->get(),
            'filters' => $request->only(['filter_user_id']),
            'current_period' => $period,
            'start_date_formatted' => $startDate->format('d/m/Y'),
            'end_date_formatted' => $endDate->format('d/m/Y'),
        ]);
    }

    public function payout(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'period_start' => 'required|date_format:d/m/Y',
            'period_end' => 'required|date_format:d/m/Y',
        ]);

        CommissionPayout::create([
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'period_start' => Carbon::createFromFormat('d/m/Y', $request->period_start)->toDateString(),
            'period_end' => Carbon::createFromFormat('d/m/Y', $request->period_end)->toDateString(),
            'paid_by_user_id' => Auth::id(),
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Pago registrado exitosamente.');
    }

    public function getDetails(Request $request)
    {
        // --- LA CORRECCIÓN ESTÁ AQUÍ ---
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'period_start' => 'required|date_format:Y-m-d', // Se especifica el formato exacto
            'period_end' => 'required|date_format:Y-m-d',   // Se especifica el formato exacto
        ]);

        // El resto del método funciona como estaba
        $startDate = Carbon::parse($request->period_start)->startOfDay();
        $endDate = Carbon::parse($request->period_end)->endOfDay();
        
        $allCommissions = $this->calculateCommissionsForPeriod($startDate, $endDate);

        $userDetails = [];
        foreach ($allCommissions as $commission) {
            if ($commission['user_id'] == $request->user_id) {
                $userDetails = $commission['details'];
                break;
            }
        }

        return response()->json($userDetails);
    }

    private function getPeriodDates(string $period, Carbon $now): array
    {
        if ($period === 'second_half') {
            $startDate = $now->copy()->setDay(16)->startOfDay();
            $endDate = $now->copy()->endOfMonth()->endOfDay();
        } else {
            $startDate = $now->copy()->startOfMonth()->startOfDay();
            $endDate = $now->copy()->setDay(15)->endOfDay();
        }
        return [$startDate, $endDate];
    }
    
    private function calculateCommissionsForPeriod(Carbon $startDate, Carbon $endDate): array
    {
        $reviews = Review::with(['user', 'products'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $commissionsByUser = [];
        $commissionRate = 0.30;

        foreach ($reviews as $review) {
            if (!$review->user) continue;
            $userId = $review->user->id;

            if (!isset($commissionsByUser[$userId])) {
                $commissionsByUser[$userId] = [
                    'user_id' => $userId,
                    'user_name' => $review->user->name,
                    'total_commission' => 0,
                    'details' => [],
                ];
            }

            foreach ($review->products as $item) {
                if ($item->is_service) {
                    $servicePrice = $item->pivot->price_at_time_of_review * $item->pivot->quantity;
                    $commissionAmount = $servicePrice * $commissionRate;
                    $commissionsByUser[$userId]['total_commission'] += $commissionAmount;
                    $commissionsByUser[$userId]['details'][] = [
                        'order_id' => $review->orders_id,
                        'review_id' => $review->id,
                        'service_name' => $item->name,
                        'service_price' => $servicePrice,
                        'commission_earned' => $commissionAmount,
                    ];
                }
            }
        }
        return array_values($commissionsByUser);
    }
}
