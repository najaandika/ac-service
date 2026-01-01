<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Technician;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'confirmed_orders' => Order::where('status', 'confirmed')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'total_customers' => Customer::count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total_price'),
        ];

        $recentOrders = Order::with(['customer', 'service', 'technician'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $technicians = Technician::active()->withCount('orders')->get();

        // Chart data
        $chartData = $this->getChartData();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'technicians', 'chartData'));
    }

    private function getChartData()
    {
        // Revenue last 7 days
        $revenueLabels = [];
        $revenueData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $revenueLabels[] = $date->format('d M');
            $revenueData[] = Order::where('status', 'completed')
                ->whereDate('created_at', $date)
                ->sum('total_price');
        }

        // Order status distribution
        $statusData = [
            Order::where('status', 'pending')->count(),
            Order::where('status', 'confirmed')->count(),
            Order::where('status', 'in_progress')->count(),
            Order::where('status', 'completed')->count(),
            Order::where('status', 'cancelled')->count(),
        ];

        return [
            'revenue' => [
                'labels' => $revenueLabels,
                'data' => $revenueData,
            ],
            'status' => $statusData,
        ];
    }
}
