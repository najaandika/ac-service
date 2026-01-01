<?php

namespace App\Services;

use App\Models\Order;

class WhatsAppService
{
    /**
     * Generate WhatsApp URL with pre-filled message for order status.
     */
    public function generateOrderStatusUrl(Order $order, ?string $status = null): string
    {
        $status = $status ?? $order->status;
        $phone = \App\Helpers\FormatHelper::whatsappPhone($order->customer->phone);
        $message = $this->getMessageTemplate($order, $status);
        
        return "https://wa.me/{$phone}?text=" . urlencode($message);
    }

    /**
     * Get message template based on order status.
     */
    protected function getMessageTemplate(Order $order, string $status): string
    {
        $customerName = $order->customer->name;
        $orderCode = $order->order_code;
        $serviceName = $order->service->name;
        $scheduledDate = $order->scheduled_date->translatedFormat('l, d F Y');
        $total = \App\Helpers\FormatHelper::rupiah($order->total_price);
        $ratingUrl = url("/order/{$orderCode}/rate");
        
        return match($status) {
            'pending' => "Halo {$customerName},\n\nTerima kasih telah memesan layanan kami.\n\n📋 Order: #{$orderCode}\n🔧 Layanan: {$serviceName}\n📅 Jadwal: {$scheduledDate}\n\nOrder Anda sedang kami proses. Mohon tunggu konfirmasi selanjutnya.\n\nTerima kasih 🙏",
            
            'confirmed' => "Halo {$customerName},\n\nOrder Anda sudah dikonfirmasi! ✅\n\n📋 Order: #{$orderCode}\n🔧 Layanan: {$serviceName}\n📅 Jadwal: {$scheduledDate}\n\nTeknisi kami akan datang sesuai jadwal. Pastikan ada yang di rumah saat teknisi datang.\n\nTerima kasih 🙏",
            
            'in_progress' => "Halo {$customerName},\n\nTeknisi kami sedang mengerjakan order #{$orderCode}.\n\n🔧 Layanan: {$serviceName}\n\nMohon tunggu hingga pekerjaan selesai.\n\nTerima kasih 🙏",
            
            'completed' => "Halo {$customerName},\n\nOrder #{$orderCode} sudah selesai! ✅\n\n🔧 Layanan: {$serviceName}\n💰 Total: {$total}\n\nTerima kasih telah menggunakan layanan kami. Semoga AC Anda dingin maksimal! ❄️\n\n⭐ Berikan rating untuk layanan kami:\n{$ratingUrl}",
            
            'cancelled' => "Halo {$customerName},\n\nMohon maaf, order #{$orderCode} telah dibatalkan.\n\nJika ada pertanyaan, silakan hubungi kami.\n\nTerima kasih 🙏",
            
            default => "Halo {$customerName},\n\nIni adalah update untuk order #{$orderCode}.\n\nStatus: {$status}\n\nTerima kasih 🙏",
        };
    }
}
