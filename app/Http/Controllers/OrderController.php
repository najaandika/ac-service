<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
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
        
        return view('order.create', compact('services', 'selectedService', 'selectedCapacity', 'selectedQty'));
    }

    /**
     * Store a new order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|regex:/^[0-9]+$/',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'city' => 'nullable|string|max:100',
            'ac_type' => 'required|in:split,cassette,standing,central,window',
            'ac_capacity' => 'required|in:0.5pk,0.75pk,1pk,1.5pk,2pk,2.5pk,3pk,5pk',
            'ac_quantity' => 'required|integer|min:1|max:10',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'scheduled_time' => 'required|in:pagi,siang,sore',
            'notes' => 'nullable|string|max:1000',
        ], [
            'service_id.required' => 'Silakan pilih layanan terlebih dahulu.',
            'service_id.exists' => 'Layanan yang dipilih tidak valid.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'phone.regex' => 'Nomor WhatsApp hanya boleh berisi angka.',
            'address.required' => 'Alamat wajib diisi.',
            'ac_type.required' => 'Tipe AC wajib dipilih.',
            'ac_capacity.required' => 'Kapasitas AC wajib dipilih.',
            'scheduled_date.required' => 'Tanggal layanan wajib diisi.',
            'scheduled_date.after_or_equal' => 'Tanggal layanan tidak boleh di masa lalu.',
            'scheduled_time.required' => 'Waktu layanan wajib dipilih.',
        ]);

        try {
            DB::beginTransaction();

            // Create or find customer
            $customer = Customer::firstOrCreate(
                ['phone' => $validated['phone']],
                [
                    'name' => $validated['name'],
                    'email' => $validated['email'] ?? null,
                    'address' => $validated['address'],
                    'city' => $validated['city'] ?? null,
                ]
            );

            // Update customer data if exists
            $customer->update([
                'name' => $validated['name'],
                'address' => $validated['address'],
                'email' => $validated['email'] ?? $customer->email,
                'city' => $validated['city'] ?? $customer->city,
            ]);

            // Get service price based on capacity
            $service = Service::with('prices')->findOrFail($validated['service_id']);
            $priceRecord = $service->prices()->where('capacity', $validated['ac_capacity'])->first();
            $unitPrice = $priceRecord ? $priceRecord->price : $service->price;
            $servicePrice = $unitPrice * $validated['ac_quantity'];

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
                'total_price' => $servicePrice,
                'status' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('order.success', ['code' => $order->order_code]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan. Silakan coba lagi.']);
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

        return view('order.success', compact('order'));
    }

    /**
     * Show track order form
     */
    public function trackForm()
    {
        return view('order.track');
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
