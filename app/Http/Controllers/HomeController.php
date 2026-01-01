<?php

namespace App\Http\Controllers;

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
        
        return view('home', compact('services', 'latestReviews'));
    }
}
