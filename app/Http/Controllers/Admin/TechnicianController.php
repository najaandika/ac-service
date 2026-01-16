<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTechnicianRequest;
use App\Http\Requests\UpdateTechnicianRequest;
use App\Models\Technician;
use App\Traits\Toggleable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TechnicianController extends Controller
{
    use Toggleable;
    /**
     * Display a listing of technicians.
     */
    public function index(Request $request)
    {
        $query = Technician::withCount('orders');
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        // Search by name or phone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }
        
        $technicians = $query->orderBy('name')->paginate(10);
        
        return view('admin.technicians.index', compact('technicians'));
    }

    /**
     * Show the form for creating a new technician.
     */
    public function create()
    {
        return view('admin.technicians.create');
    }

    /**
     * Store a newly created technician.
     */
    public function store(StoreTechnicianRequest $request)
    {
        $validated = $request->validated();
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('technicians', 'public');
        }
        
        $validated['is_active'] = $request->boolean('is_active', true);
        
        Technician::create($validated);
        
        return redirect()
            ->route('admin.technicians.index')
            ->with('success', 'Teknisi berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified technician.
     */
    public function edit(Technician $technician)
    {
        return view('admin.technicians.edit', compact('technician'));
    }

    /**
     * Update the specified technician.
     */
    public function update(UpdateTechnicianRequest $request, Technician $technician)
    {
        $validated = $request->validated();
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($technician->photo) {
                Storage::disk('public')->delete($technician->photo);
            }
            $validated['photo'] = $request->file('photo')->store('technicians', 'public');
        }
        
        $validated['is_active'] = $request->boolean('is_active', true);
        
        $technician->update($validated);
        
        return redirect()
            ->route('admin.technicians.index')
            ->with('success', 'Teknisi berhasil diperbarui!');
    }

    /**
     * Remove the specified technician.
     */
    public function destroy(Technician $technician)
    {
        // Check if technician has orders
        if ($technician->orders()->exists()) {
            return back()->with('error', 'Teknisi tidak bisa dihapus karena sudah ada order terkait. Nonaktifkan saja.');
        }
        
        // Delete photo
        if ($technician->photo) {
            Storage::disk('public')->delete($technician->photo);
        }
        
        $technician->delete();
        
        return redirect()
            ->route('admin.technicians.index')
            ->with('success', 'Teknisi berhasil dihapus!');
    }

    /**
     * Toggle technician active status.
     */
    public function toggleStatus(Request $request, Technician $technician)
    {
        return $this->handleToggleStatus($request, $technician, 'Teknisi');
    }
}
