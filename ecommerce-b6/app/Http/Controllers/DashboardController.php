<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Generate last 7 dates
        $dates = collect(range(6, 0))->map(function ($i) {
            return Carbon::now()->subDays($i)->format('Y-m-d');
        });

        // Orders grouped by date
        $orders = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total) as total_revenue') // ✅ FIXED HERE
            )
            ->where('created_at', '>=', Carbon::now()->subDays(6))
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $labels = [];
        $transactions = [];
        $nominal = [];

        foreach ($dates as $date) {
            $labels[] = Carbon::parse($date)->format('D');
            $transactions[] = $orders[$date]->total_orders ?? 0;
            $nominal[] = $orders[$date]->total_revenue ?? 0;
        }

        return view('dashboard', [

            // summary cards
            'totalProducts'   => Product::count(),
            'totalCategories' => Category::count(),
            'totalUsers'      => User::count(),
            'totalClicks'     => Product::sum('click_count'),

            // chart variables (MUST MATCH BLADE)
            'chartLabels'        => $labels,
            'chartTransactions' => $transactions,
            'chartRevenue'      => $nominal,
        ]);

    }
}
