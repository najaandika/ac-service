<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Review;
use App\Models\Service;
use Illuminate\Database\Seeder;

class TestimoniSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Creates 3 real testimonials from Google Maps reviews.
     */
    public function run(): void
    {
        // Get first service (or create a default one)
        $service = Service::first();
        
        if (!$service) {
            $this->command->warn('No services found. Please seed services first.');
            return;
        }

        // Real testimonials from Google Maps (with actual dates)
        $testimonials = [
            [
                'name' => 'M Frantio',
                'phone' => '081234567001',
                'rating' => 5,
                'comment' => 'Teknisi baik dan ramah. Kerjanya rapi dan harganya bersahabat. Thanks!',
                'months_ago' => 3, // 3 bulan lalu
            ],
            [
                'name' => 'KP Parung',
                'phone' => '081234567002',
                'rating' => 5,
                'comment' => 'Thanks untuk teknisinya. Ramah dan bertanggung jawab.',
                'months_ago' => 4, // 4 bulan lalu
            ],
            [
                'name' => 'Ahlun Nazar',
                'phone' => '081234567003',
                'rating' => 5,
                'comment' => 'Pelayanan bagus dan harga terjangkau.',
                'months_ago' => 24, // 2 tahun lalu
            ],
        ];

        foreach ($testimonials as $index => $testimonial) {
            // Create customer
            $customer = Customer::create([
                'name' => $testimonial['name'],
                'phone' => $testimonial['phone'],
                'address' => 'Alamat Customer ' . ($index + 1),
            ]);

            // Create completed order
            $order = Order::create([
                'customer_id' => $customer->id,
                'service_id' => $service->id,
                'ac_type' => 'split',
                'ac_capacity' => '1pk',
                'ac_quantity' => 1,
                'scheduled_date' => now()->subMonths($testimonial['months_ago']),
                'scheduled_time' => 'pagi',
                'service_price' => $service->price ?? 75000,
                'total_price' => $service->price ?? 75000,
                'status' => 'completed',
                'completed_at' => now()->subMonths($testimonial['months_ago'])->addHours(2),
            ]);

            // Create review with correct date
            $reviewDate = now()->subMonths($testimonial['months_ago']);
            $review = new Review([
                'order_id' => $order->id,
                'rating' => $testimonial['rating'],
                'comment' => $testimonial['comment'],
            ]);
            $review->created_at = $reviewDate;
            $review->updated_at = $reviewDate;
            $review->save();
        }

        $this->command->info('✓ 3 testimonials from Google Maps seeded successfully!');
    }
}
