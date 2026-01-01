<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\Models\Technician;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Date range filter
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        // Summary stats
        $stats = [
            'total_orders' => Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])->count(),
            'completed_orders' => Order::where('status', 'completed')->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])->count(),
            'total_revenue' => Order::where('status', 'completed')->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])->sum('total_price'),
            'average_order' => Order::where('status', 'completed')->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])->avg('total_price') ?? 0,
        ];

        // Revenue by day chart
        $revenueChart = $this->getRevenueChart($startDate, $endDate);

        // Orders by service chart
        $serviceChart = $this->getServiceChart($startDate, $endDate);

        // Technician performance
        $technicianPerformance = Technician::active()
            ->withCount(['orders' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
            }])
            ->withSum(['orders' => function ($query) use ($startDate, $endDate) {
                $query->where('status', 'completed')
                    ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
            }], 'total_price')
            ->orderByDesc('orders_count')
            ->get();

        return view('admin.reports.index', compact('stats', 'revenueChart', 'serviceChart', 'technicianPerformance', 'startDate', 'endDate'));
    }

    private function getRevenueChart($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $diffInDays = $start->diffInDays($end);

        $labels = [];
        $data = [];

        // If range is more than 31 days, group by week
        if ($diffInDays > 31) {
            $current = $start->copy()->startOfWeek();
            while ($current <= $end) {
                $weekEnd = $current->copy()->endOfWeek();
                $labels[] = $current->format('d M') . ' - ' . $weekEnd->format('d M');
                $data[] = Order::where('status', 'completed')
                    ->whereBetween('created_at', [$current, $weekEnd->endOfDay()])
                    ->sum('total_price');
                $current->addWeek();
            }
        } else {
            $current = $start->copy();
            while ($current <= $end) {
                $labels[] = $current->format('d M');
                $data[] = Order::where('status', 'completed')
                    ->whereDate('created_at', $current)
                    ->sum('total_price');
                $current->addDay();
            }
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function getServiceChart($startDate, $endDate)
    {
        $services = Service::withCount(['orders' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
        }])->orderByDesc('orders_count')->get();

        return [
            'labels' => $services->pluck('name')->toArray(),
            'data' => $services->pluck('orders_count')->toArray(),
        ];
    }
}
