<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::active()->with('prices')->orderBy('sort_order')->get();
        
        return view('home', compact('services'));
    }
}
