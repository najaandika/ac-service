<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\AcCapacity;
use App\Models\Service;
use App\Models\ServicePrice;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    /**
     * Display a listing of services.
     */
    public function index(Request $request)
    {
        $query = Service::with('prices')->withCount('orders');
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $services = $query->orderBy('name')->paginate(10);
        
        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new service.
     */
    public function create()
    {
        $capacityLabels = AcCapacity::all();
        return view('admin.services.create', compact('capacityLabels'));
    }

    /**
     * Store a newly created service.
     */
    public function store(Request $request)
    {
        // Clean price format (remove dots used as thousand separator)
        if ($request->has('prices')) {
            $cleanedPrices = [];
            foreach ($request->input('prices') as $key => $value) {
                $cleanedPrices[$key] = (int) str_replace(['.', ','], '', $value);
            }
            $request->merge(['prices' => $cleanedPrices]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:services',
            'description' => 'required|string',
            'duration_minutes' => 'required|integer|min:15',
            'icon' => 'required|string|max:50',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'prices' => 'required|array',
            'prices.*' => 'required|numeric|min:0',
        ]);
        
        // Create service
        $service = Service::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'duration_minutes' => $validated['duration_minutes'],
            'icon' => $validated['icon'],
            'features' => array_filter($validated['features'] ?? []),
            'is_active' => $request->boolean('is_active', true),
        ]);
        
        // Create prices for each capacity
        foreach ($validated['prices'] as $capacity => $price) {
            if ($price > 0) {
                // Ensure capacity has 'pk' suffix
                $capacityKey = str_ends_with($capacity, 'pk') ? $capacity : $capacity . 'pk';
                ServicePrice::create([
                    'service_id' => $service->id,
                    'capacity' => $capacityKey,
                    'price' => $price,
                ]);
            }
        }
        
        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit(Service $service)
    {
        $service->load('prices');
        $capacityLabels = AcCapacity::all();
        
        // Map prices by capacity for easy access
        $priceMap = $service->prices->pluck('price', 'capacity')->toArray();
        
        return view('admin.services.edit', compact('service', 'capacityLabels', 'priceMap'));
    }

    /**
     * Update the specified service.
     */
    public function update(Request $request, Service $service)
    {
        // Clean price format (remove dots used as thousand separator)
        if ($request->has('prices')) {
            $cleanedPrices = [];
            foreach ($request->input('prices') as $key => $value) {
                $cleanedPrices[$key] = (int) str_replace(['.', ','], '', $value);
            }
            $request->merge(['prices' => $cleanedPrices]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:services,name,' . $service->id,
            'description' => 'required|string',
            'duration_minutes' => 'required|integer|min:15',
            'icon' => 'required|string|max:50',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'prices' => 'required|array',
            'prices.*' => 'required|numeric|min:0',
        ]);
        
        // Update service
        $service->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'duration_minutes' => $validated['duration_minutes'],
            'icon' => $validated['icon'],
            'features' => array_filter($validated['features'] ?? []),
            'is_active' => $request->boolean('is_active', true),
        ]);
        
        // Update prices - delete old and create new
        $service->prices()->delete();
        
        foreach ($validated['prices'] as $capacity => $price) {
            if ($price > 0) {
                // Ensure capacity has 'pk' suffix
                $capacityKey = str_ends_with($capacity, 'pk') ? $capacity : $capacity . 'pk';
                ServicePrice::create([
                    'service_id' => $service->id,
                    'capacity' => $capacityKey,
                    'price' => $price,
                ]);
            }
        }
        
        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil diperbarui!');
    }

    /**
     * Remove the specified service.
     */
    public function destroy(Service $service)
    {
        // Check if service has orders
        if ($service->orders()->exists()) {
            return back()->with('error', 'Layanan tidak bisa dihapus karena sudah ada order terkait. Nonaktifkan saja.');
        }
        
        $service->prices()->delete();
        $service->delete();
        
        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil dihapus!');
    }

    /**
     * Toggle service active status.
     */
    public function toggleStatus(Request $request, Service $service)
    {
        $service->update(['is_active' => !$service->is_active]);
        
        // Return JSON for AJAX requests
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_active' => $service->is_active,
                'message' => $service->is_active ? 'Layanan diaktifkan!' : 'Layanan dinonaktifkan!'
            ]);
        }
        
        $status = $service->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return back()->with('success', "Layanan berhasil {$status}!");
    }
}
