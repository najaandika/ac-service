<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Promo;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // Filter for tomorrow's orders (H-1 reminder)
        if ($request->has('tomorrow') && $request->tomorrow) {
            $query->whereDate('scheduled_date', now()->addDay()->format('Y-m-d'))
                  ->whereNotIn('status', ['completed', 'cancelled']);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);
        $technicians = Technician::active()->get();
        
        // Count tomorrow's orders for H-1 reminder badge
        $tomorrowCount = Order::whereDate('scheduled_date', now()->addDay()->format('Y-m-d'))
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();

        return view('admin.orders.index', compact('orders', 'technicians', 'tomorrowCount'));
    }

    /**
     * Show single order
     */
    public function show(Order $order)
    {
        $order->load(['customer', 'service', 'technician', 'review']);
        $technicians = Technician::active()->get();
        
        // Generate WhatsApp URL for status notification
        $whatsappService = new \App\Services\WhatsAppService();
        $whatsappUrl = $whatsappService->generateOrderStatusUrl($order);
        
        // Generate H-1 Reminder URL
        $reminderMessage = "Halo {$order->customer->name}, ini reminder untuk jadwal service AC Anda:\n\n"
            . "📅 *Besok, " . $order->scheduled_date->translatedFormat('d F Y') . "*\n"
            . "⏰ Pukul: *" . $order->scheduled_time_slot . "*\n"
            . "🔧 Layanan: *" . $order->service->name . "*\n"
            . "📍 Alamat: " . $order->customer->address . "\n\n"
            . "Mohon pastikan ada yang menerima teknisi kami ya. Terima kasih! 🙏";
        $reminderUrl = "https://wa.me/" . preg_replace('/^0/', '62', $order->customer->phone) . "?text=" . urlencode($reminderMessage);

        return view('admin.orders.show', compact('order', 'technicians', 'whatsappUrl', 'reminderUrl'));
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

    /**
     * Mark technician as departed (on the way)
     */
    public function markDeparted(Order $order)
    {
        $order->update([
            'departed_at' => now(),
            'status' => 'in_progress',
        ]);

        return back()->with('success', 'Teknisi ditandai sudah berangkat.');
    }

    /**
     * Show form to create a new order manually
     */
    public function create()
    {
        $services = Service::active()->with('prices')->orderBy('name')->get();
        $technicians = Technician::active()->get();
        $customers = Customer::orderBy('name')->get();
        $settings = Setting::getAllAsArray();

        return view('admin.orders.create', compact('services', 'technicians', 'customers', 'settings'));
    }

    /**
     * Store a new order created by admin
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_type' => 'required|in:existing,new',
            'customer_id' => 'required_if:customer_type,existing|nullable|exists:customers,id',
            'name' => 'required_if:customer_type,new|nullable|string|max:255',
            'phone' => 'required_if:customer_type,new|nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required_if:customer_type,new|nullable|string',
            'city' => 'nullable|string|max:100',
            'service_id' => 'required|exists:services,id',
            'ac_type' => 'required|in:split,cassette,standing,central',
            'ac_capacity' => 'required|string',
            'ac_quantity' => 'required|integer|min:1|max:10',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'scheduled_time' => 'required|string',
            'technician_id' => 'nullable|exists:technicians,id',
            'notes' => 'nullable|string',
            'promo_code' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Get or create customer
            if ($validated['customer_type'] === 'existing') {
                $customer = Customer::findOrFail($validated['customer_id']);
            } else {
                $customer = Customer::updateOrCreate(
                    ['phone' => $validated['phone']],
                    [
                        'name' => $validated['name'],
                        'email' => $validated['email'] ?? null,
                        'address' => $validated['address'],
                        'city' => $validated['city'] ?? null,
                    ]
                );
            }

            // Calculate price
            $service = Service::with('prices')->findOrFail($validated['service_id']);
            $unitPrice = $service->getPriceForCapacity($validated['ac_capacity']);
            $servicePrice = $unitPrice * $validated['ac_quantity'];

            // Handle promo
            $discount = 0;
            $promoId = null;
            $promoCode = $validated['promo_code'] ?? null;

            if ($promoCode) {
                $promo = Promo::where('code', strtoupper($promoCode))->first();
                if ($promo) {
                    $validation = $promo->isValid($validated['service_id'], $servicePrice);
                    if ($validation['valid']) {
                        $discount = $promo->calculateDiscount($servicePrice);
                        $promoId = $promo->id;
                        $promo->incrementUsage();
                    }
                }
            }

            $totalPrice = $servicePrice - $discount;

            // Create order
            $order = Order::create([
                'customer_id' => $customer->id,
                'service_id' => $validated['service_id'],
                'technician_id' => $validated['technician_id'] ?? null,
                'ac_type' => $validated['ac_type'],
                'ac_capacity' => $validated['ac_capacity'],
                'ac_quantity' => $validated['ac_quantity'],
                'scheduled_date' => $validated['scheduled_date'],
                'scheduled_time' => $validated['scheduled_time'],
                'notes' => $validated['notes'] ?? null,
                'service_price' => $servicePrice,
                'discount' => $discount,
                'promo_id' => $promoId,
                'promo_code' => $promoCode ? strtoupper($promoCode) : null,
                'total_price' => $totalPrice,
                'status' => $validated['technician_id'] ? 'confirmed' : 'pending',
            ]);

            // Increment technician order count if assigned
            if ($validated['technician_id']) {
                Technician::find($validated['technician_id'])->increment('total_orders');
            }

            DB::commit();

            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Order berhasil dibuat! Kode: ' . $order->order_code);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal membuat order: ' . $e->getMessage());
        }
    }
}

