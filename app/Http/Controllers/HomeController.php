<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Portfolio;
use App\Models\Promo;
use App\Models\Review;
use App\Models\Service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::active()->with('prices')->orderBy('sort_order')->get();
        
        // Get latest 3 reviews with good ratings (4-5 stars)
        $latestReviews = Review::with(['order.customer', 'order.service'])
            ->where('rating', '>=', 4)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        // Stats for social proof
        $stats = [
            'completed_orders' => Order::where('status', 'completed')->count(),
            'average_rating' => round(Review::avg('rating') ?? 0, 1),
            'total_reviews' => Review::count(),
        ];
        
        // Get active promos for banner (max 3, public only)
        $activePromos = Promo::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('usage_limit')->orWhereColumn('usage_count', '<', 'usage_limit');
            })
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        // Get latest 4 published portfolios for gallery preview
        $portfolios = Portfolio::with('service')
            ->published()
            ->ordered()
            ->limit(4)
            ->get();
        
        return view('home', compact('services', 'latestReviews', 'stats', 'activePromos', 'portfolios'));
    }
}
