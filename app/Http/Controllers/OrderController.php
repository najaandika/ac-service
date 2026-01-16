<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Promo;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Show the order form
     */
    public function create(Request $request)
    {
        $services = Service::active()->with('prices')->orderBy('sort_order')->get();
        $selectedService = null;
        $selectedCapacity = $request->get('capacity', '1pk');
        $selectedQty = (int) $request->get('qty', 1);
        
        if ($request->has('service')) {
            $selectedService = Service::where('slug', $request->service)->with('prices')->first();
        }
        
        // Calculate total price for display
        $totalPrice = null;
        if ($selectedService) {
            $priceRecord = $selectedService->prices->where('capacity', $selectedCapacity)->first();
            $unitPrice = $priceRecord ? $priceRecord->price : $selectedService->price;
            $totalPrice = $unitPrice * $selectedQty;
        }
        
        // Get active promos
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
            ->get();

        // Get settings for time slots
        $settings = \App\Models\Setting::getAllAsArray();
        
        // Unit price for promo calculation
        $unitPrice = 0;
        if ($selectedService) {
            $priceRecord = $selectedService->prices->where('capacity', $selectedCapacity)->first();
            $unitPrice = $priceRecord ? $priceRecord->price : $selectedService->price;
        }

        return view('order.create', compact('services', 'selectedService', 'selectedCapacity', 'selectedQty', 'totalPrice', 'unitPrice', 'activePromos', 'settings'));
    }

    /**
     * Store a new order
     */
    public function store(\App\Http\Requests\StoreOrderRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            // Create or find customer (update if exists)
            $customer = Customer::updateOrCreate(
                ['phone' => $validated['phone']],
                [
                    'name' => $validated['name'],
                    'email' => $validated['email'] ?? null,
                    'address' => $validated['address'],
                    'city' => $validated['city'] ?? null,
                ]
            );

            // Get service and calculate price
            $service = Service::with('prices')->findOrFail($validated['service_id']);
            $unitPrice = $service->getPriceForCapacity($validated['ac_capacity']);
            $servicePrice = $unitPrice * $validated['ac_quantity'];

            // Handle promo code if provided
            $discount = 0;
            $promoId = null;
            $promoCode = $request->input('promo_code');
            
            if ($promoCode) {
                // Determine promo regardless of validation (validation already done in request, but logical check needed for calculation)
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
                'status' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('order.success', ['code' => $order->order_code]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat memproses order: ' . $e->getMessage()]);
        }
    }

    /**
     * Show order success page
     */
    public function success(Request $request)
    {
        $order = Order::where('order_code', $request->code)
            ->with(['service', 'customer'])
            ->firstOrFail();

        // Generate WhatsApp URL
        $waUrl = null;
        $settings = \App\Models\Setting::getAllAsArray();
        if (!empty($settings['whatsapp'])) {
            $waMessage = "Halo, saya ingin konfirmasi order:\n\n";
            $waMessage .= "📋 *Kode Order:* " . $order->order_code . "\n";
            $waMessage .= "🔧 *Layanan:* " . $order->service->name . "\n";
            $waMessage .= "📅 *Jadwal:* " . $order->scheduled_date->format('d/m/Y') . " (" . $order->scheduled_time_slot . ")\n";
            $waMessage .= "📍 *Alamat:* " . $order->customer->address . "\n";
            $waMessage .= "👤 *Nama:* " . $order->customer->name . "\n";
            $waMessage .= "📱 *HP:* " . $order->customer->phone . "\n\n";
            $waMessage .= "Mohon konfirmasi order saya. Terima kasih! 🙏";
            $waNumber = preg_replace('/[^0-9]/', '', $settings['whatsapp']);
            $waUrl = "https://wa.me/{$waNumber}?text=" . urlencode($waMessage);
        }

        return view('order.success', compact('order', 'waUrl'));
    }

    /**
     * Show track order form
     */
    public function trackForm()
    {
        return view('order.track');
    }

    /**
     * Validate promo code (API endpoint)
     */
    public function validatePromo(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'service_id' => 'nullable|integer',
            'subtotal' => 'nullable|numeric|min:0',
        ]);

        $promo = Promo::where('code', strtoupper($request->code))->first();

        if (!$promo) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode promo tidak ditemukan',
            ]);
        }

        $serviceId = $request->input('service_id');
        $subtotal = $request->input('subtotal', 0);

        $validation = $promo->isValid($serviceId, $subtotal);

        if (!$validation['valid']) {
            return response()->json([
                'valid' => false,
                'message' => $validation['message'],
            ]);
        }

        $discount = $promo->calculateDiscount($subtotal);

        return response()->json([
            'valid' => true,
            'message' => 'Kode promo berhasil diterapkan: ' . $promo->name,
            'discount' => $discount,
            'promo' => [
                'code' => $promo->code,
                'name' => $promo->name,
                'type' => $promo->type,
                'value' => $promo->value,
            ],
        ]);
    }

    /**
     * Track order by code or phone
     */
    public function track(Request $request)
    {
        $query = $request->get('query');
        
        // If no query provided, just show the form
        if (empty($query)) {
            return view('order.track');
        }

        // Search by order code or phone
        $orders = Order::with(['service', 'customer', 'technician'])
            ->where('order_code', 'like', "%{$query}%")
            ->orWhereHas('customer', function ($q) use ($query) {
                $q->where('phone', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('order.track', compact('orders', 'query'));
    }
}
