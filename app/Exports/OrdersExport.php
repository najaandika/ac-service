<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrdersExport
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Get orders collection.
     */
    protected function getOrders()
    {
        $query = Order::with(['customer', 'service', 'technician'])
            ->where('status', 'completed');

        if ($this->startDate) {
            $query->whereDate('scheduled_date', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('scheduled_date', '<=', $this->endDate);
        }

        return $query->orderBy('scheduled_date', 'desc')->get();
    }

    /**
     * Export to CSV (Excel compatible).
     */
    public function toExcel(): StreamedResponse
    {
        $orders = $this->getOrders();
        $filename = 'laporan-order-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, [
                'Kode Order',
                'Nama Customer',
                'Telepon',
                'Alamat',
                'Layanan',
                'Kapasitas',
                'Jumlah Unit',
                'Status',
                'Teknisi',
                'Tanggal',
                'Harga Layanan',
                'Promo',
                'Diskon',
                'Total (Rp)',
            ]);

            // Data
            $totalRevenue = 0;
            $totalDiscount = 0;
            foreach ($orders as $order) {
                $totalRevenue += $order->total_price;
                $totalDiscount += $order->discount ?? 0;
                
                fputcsv($file, [
                    $order->order_code,
                    $order->customer->name,
                    $order->customer->phone,
                    $order->customer->address ?? '-',
                    $order->service->name,
                    strtoupper($order->ac_capacity),
                    $order->ac_quantity,
                    $order->status_label,
                    $order->technician?->name ?? '-',
                    $order->scheduled_date->format('d/m/Y'),
                    $order->service_price,
                    $order->promo_code ?? '-',
                    $order->discount ?? 0,
                    $order->total_price,
                ]);
            }

            // Empty row as separator
            fputcsv($file, []);
            
            // Summary row
            $orderCount = $orders->count();
            $avgOrderValue = $orderCount > 0 ? $totalRevenue / $orderCount : 0;
            
            fputcsv($file, ['RINGKASAN']);
            fputcsv($file, ['Total Order:', $orderCount]);
            fputcsv($file, ['Total Pendapatan:', 'Rp ' . number_format($totalRevenue, 0, ',', '.')]);
            fputcsv($file, ['Total Diskon:', 'Rp ' . number_format($totalDiscount, 0, ',', '.')]);
            fputcsv($file, ['Rata-rata per Order:', 'Rp ' . number_format($avgOrderValue, 0, ',', '.')]);

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    /**
     * Export to PDF using dompdf.
     */
    public function toPdf(): Response
    {
        $orders = $this->getOrders();
        $startDate = $this->startDate ? \Carbon\Carbon::parse($this->startDate)->format('d/m/Y') : 'Awal';
        $endDate = $this->endDate ? \Carbon\Carbon::parse($this->endDate)->format('d/m/Y') : 'Akhir';
        
        $html = view('exports.orders-pdf', compact('orders', 'startDate', 'endDate'))->render();
        
        // Generate PDF using dompdf
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        $filename = 'laporan-order-' . now()->format('Y-m-d') . '.pdf';
        
        return response($dompdf->output())
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}
