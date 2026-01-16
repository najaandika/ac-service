<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\AcCapacity;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use App\Models\ServicePrice;
use App\Traits\Toggleable;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    use Toggleable;
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
    public function store(StoreServiceRequest $request)
    {
        $validated = $request->validated();
        
        // Create service
        $service = Service::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'duration_minutes' => $validated['duration_minutes'],
            'icon' => $validated['icon'],
            'features' => $validated['features'],
            'is_active' => $validated['is_active'],
        ]);
        
        // Create prices for each capacity
        $this->syncPrices($service, $validated['prices']);
        
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
    public function update(UpdateServiceRequest $request, Service $service)
    {
        $validated = $request->validated();
        
        // Update service
        $service->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'duration_minutes' => $validated['duration_minutes'],
            'icon' => $validated['icon'],
            'features' => $validated['features'],
            'is_active' => $validated['is_active'],
        ]);
        
        // Update prices
        $service->prices()->delete();
        $this->syncPrices($service, $validated['prices']);
        
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
        return $this->handleToggleStatus($request, $service, 'Layanan');
    }

    /**
     * Sync service prices for each capacity.
     */
    private function syncPrices(Service $service, array $prices): void
    {
        foreach ($prices as $capacity => $price) {
            if ($price > 0) {
                $capacityKey = str_ends_with($capacity, 'pk') ? $capacity : $capacity . 'pk';
                ServicePrice::create([
                    'service_id' => $service->id,
                    'capacity' => $capacityKey,
                    'price' => $price,
                ]);
            }
        }
    }
}
