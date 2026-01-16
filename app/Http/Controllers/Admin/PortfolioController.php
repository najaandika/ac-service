<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $portfolios = Portfolio::with('service')->ordered()->paginate(12);
        return view('admin.portfolios.index', compact('portfolios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::orderBy('name')->get();
        return view('admin.portfolios.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'before_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'after_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'service_id' => 'nullable|exists:services,id',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ]);

        // Upload before image
        if ($request->hasFile('before_image')) {
            $validated['before_image'] = $request->file('before_image')->store('portfolios', 'public');
        }

        // Upload after image
        if ($request->hasFile('after_image')) {
            $validated['after_image'] = $request->file('after_image')->store('portfolios', 'public');
        }

        $validated['is_published'] = $request->has('is_published');

        Portfolio::create($validated);

        return redirect()->route('admin.portfolios.index')
            ->with('success', 'Portfolio berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Portfolio $portfolio)
    {
        $services = Service::orderBy('name')->get();
        return view('admin.portfolios.edit', compact('portfolio', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Portfolio $portfolio)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'before_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'after_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'service_id' => 'nullable|exists:services,id',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ]);

        // Upload before image if new one provided
        if ($request->hasFile('before_image')) {
            // Delete old image
            if ($portfolio->before_image) {
                Storage::disk('public')->delete($portfolio->before_image);
            }
            $validated['before_image'] = $request->file('before_image')->store('portfolios', 'public');
        }

        // Upload after image if new one provided
        if ($request->hasFile('after_image')) {
            // Delete old image
            if ($portfolio->after_image) {
                Storage::disk('public')->delete($portfolio->after_image);
            }
            $validated['after_image'] = $request->file('after_image')->store('portfolios', 'public');
        }

        $validated['is_published'] = $request->has('is_published');

        $portfolio->update($validated);

        return redirect()->route('admin.portfolios.index')
            ->with('success', 'Portfolio berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Portfolio $portfolio)
    {
        // Delete images
        if ($portfolio->before_image) {
            Storage::disk('public')->delete($portfolio->before_image);
        }
        if ($portfolio->after_image) {
            Storage::disk('public')->delete($portfolio->after_image);
        }

        $portfolio->delete();

        return redirect()->route('admin.portfolios.index')
            ->with('success', 'Portfolio berhasil dihapus!');
    }
}
