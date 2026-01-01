<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class TestimoniController extends Controller
{
    /**
     * Display all testimonials with pagination.
     */
    public function index(Request $request)
    {
        $reviews = Review::with(['order.customer', 'order.service', 'order.technician'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Calculate stats
        $stats = [
            'total' => Review::count(),
            'average' => round(Review::avg('rating'), 1) ?: 0,
            'five_star' => Review::where('rating', 5)->count(),
        ];

        return view('testimoni.index', compact('reviews', 'stats'));
    }
}
