<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Technician;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display list of orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'service', 'technician']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->whereDate('scheduled_date', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('scheduled_date', '<=', $request->date_to);
        }

        // Search by order code or customer name/phone
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);
        $technicians = Technician::active()->get();

        return view('admin.orders.index', compact('orders', 'technicians'));
    }

    /**
     * Show single order
     */
    public function show(Order $order)
    {
        $order->load(['customer', 'service', 'technician']);
        $technicians = Technician::active()->get();

        return view('admin.orders.show', compact('order', 'technicians'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        return back()->with('success', 'Status order berhasil diupdate.');
    }

    /**
     * Assign technician to order
     */
    public function assignTechnician(Request $request, Order $order)
    {
        $validated = $request->validate([
            'technician_id' => 'required|exists:technicians,id',
        ]);

        $order->update([
            'technician_id' => $validated['technician_id'],
            'status' => $order->status === 'pending' ? 'confirmed' : $order->status,
        ]);

        // Increment technician's order count
        Technician::find($validated['technician_id'])->increment('total_orders');

        return back()->with('success', 'Teknisi berhasil ditugaskan.');
    }
}
