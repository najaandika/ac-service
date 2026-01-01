<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * Show rating form for an order.
     */
    public function show(string $code)
    {
        $order = Order::where('order_code', $code)
            ->where('status', 'completed')
            ->with(['service', 'technician', 'review'])
            ->firstOrFail();

        // Check if already reviewed
        if ($order->hasReview()) {
            return view('order.rate-already', compact('order'));
        }

        return view('order.rate', compact('order'));
    }

    /**
     * Store rating for an order.
     */
    public function store(Request $request, string $code)
    {
        $order = Order::where('order_code', $code)
            ->where('status', 'completed')
            ->firstOrFail();

        // Prevent double review
        if ($order->hasReview()) {
            return redirect()->route('order.rate.show', $code)
                ->with('error', 'Order ini sudah pernah direview.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'order_id' => $order->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        // Update technician rating average if assigned
        if ($order->technician) {
            $this->updateTechnicianRating($order->technician);
        }

        return view('order.rate-success', compact('order'));
    }

    /**
     * Update technician's average rating.
     */
    protected function updateTechnicianRating($technician)
    {
        $avgRating = Review::whereHas('order', function ($query) use ($technician) {
            $query->where('technician_id', $technician->id);
        })->avg('rating');

        $technician->update(['rating' => round($avgRating, 1)]);
    }
}
