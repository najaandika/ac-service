<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Generate and download invoice PDF
     */
    public function download(Order $order)
    {
        $order->load(['customer', 'service', 'technician', 'promo']);
        $settings = Setting::getAllAsArray();
        
        $pdf = Pdf::loadView('invoices.template', [
            'order' => $order,
            'settings' => $settings,
        ]);
        
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download("invoice-{$order->order_code}.pdf");
    }
    
    /**
     * Stream invoice PDF (view in browser)
     */
    public function stream(Order $order)
    {
        $order->load(['customer', 'service', 'technician', 'promo']);
        $settings = Setting::getAllAsArray();
        
        $pdf = Pdf::loadView('invoices.template', [
            'order' => $order,
            'settings' => $settings,
        ]);
        
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->stream("invoice-{$order->order_code}.pdf");
    }
}
