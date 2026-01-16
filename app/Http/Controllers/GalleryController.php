<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Display the public gallery page.
     */
    public function __invoke()
    {
        $portfolios = Portfolio::with('service')
            ->published()
            ->ordered()
            ->paginate(12);

        return view('gallery', compact('portfolios'));
    }
}
