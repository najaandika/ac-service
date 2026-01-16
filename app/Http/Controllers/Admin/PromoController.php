<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePromoRequest;
use App\Http\Requests\UpdatePromoRequest;
use App\Models\Promo;
use App\Models\Service;
use App\Traits\Toggleable;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    use Toggleable;
    /**
     * Display a listing of promos.
     */
    public function index(Request $request)
    {
        $query = Promo::with('service')->withCount('orders');
        
        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true)
                      ->where(function ($q) {
                          $q->whereNull('end_date')
                            ->orWhere('end_date', '>=', now());
                      });
            } elseif ($request->status === 'expired') {
                $query->where('end_date', '<', now());
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        // Search by code or name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }
        
        $promos = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.promos.index', compact('promos'));
    }

    /**
     * Show the form for creating a new promo.
     */
    public function create()
    {
        $services = Service::where('is_active', true)->orderBy('name')->get();
        return view('admin.promos.create', compact('services'));
    }

    /**
     * Store a newly created promo.
     */
    public function store(StorePromoRequest $request)
    {
        Promo::create($request->validated());
        
        return redirect()
            ->route('admin.promos.index')
            ->with('success', 'Promo berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified promo.
     */
    public function edit(Promo $promo)
    {
        $services = Service::where('is_active', true)->orderBy('name')->get();
        return view('admin.promos.edit', compact('promo', 'services'));
    }

    /**
     * Update the specified promo.
     */
    public function update(UpdatePromoRequest $request, Promo $promo)
    {
        $promo->update($request->validated());
        
        return redirect()
            ->route('admin.promos.index')
            ->with('success', 'Promo berhasil diperbarui!');
    }

    /**
     * Remove the specified promo.
     */
    public function destroy(Promo $promo)
    {
        // Check if promo has been used
        if ($promo->usage_count > 0) {
            return back()->with('error', 'Promo tidak bisa dihapus karena sudah pernah digunakan. Nonaktifkan saja.');
        }
        
        $promo->delete();
        
        return redirect()
            ->route('admin.promos.index')
            ->with('success', 'Promo berhasil dihapus!');
    }

    /**
     * Toggle promo active status.
     */
    public function toggleStatus(Request $request, Promo $promo)
    {
        return $this->handleToggleStatus($request, $promo, 'Promo');
    }
}
