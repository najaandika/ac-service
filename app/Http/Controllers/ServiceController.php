<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display list of services
     */
    public function index()
    {
        $services = Service::active()->with('prices')->orderBy('sort_order')->get();
        
        return view('services.index', compact('services'));
    }

    /**
     * Display single service detail
     */
    public function show(string $slug)
    {
        $service = Service::where('slug', $slug)
            ->with('prices')
            ->firstOrFail();
        
        $otherServices = Service::active()
            ->where('id', '!=', $service->id)
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        return view('services.show', compact('service', 'otherServices'));
    }
}
