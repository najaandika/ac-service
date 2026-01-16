<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate XML sitemap for SEO.
     */
    public function index(): Response
    {
        $services = Service::where('is_active', true)->get();
        
        $content = view('sitemap', compact('services'))->render();
        
        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Generate robots.txt dynamically.
     */
    public function robots(): Response
    {
        $content = view('robots')->render();
        
        return response($content, 200)
            ->header('Content-Type', 'text/plain');
    }
}
