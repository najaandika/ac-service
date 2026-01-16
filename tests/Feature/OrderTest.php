<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Promo;
use App\Models\Service;
use App\Models\ServicePrice;
use App\Models\Setting;
use App\Models\Technician;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a service with price
        $this->service = Service::create([
            'name' => 'Cuci AC',
            'slug' => 'cuci-ac',
            'description' => 'Layanan cuci AC',
            'price' => 50000,
            'is_active' => true,
        ]);
        
        ServicePrice::create([
            'service_id' => $this->service->id,
            'capacity' => '1pk',
            'price' => 50000,
        ]);
        
        // Create a technician
        $this->technician = Technician::create([
            'name' => 'Teknisi Test',
            'phone' => '08123456789',
            'specialty' => 'AC Split',
            'is_active' => true,
        ]);
        
        // Set time slots
        Setting::set('time_slots', '08:00, 09:00, 10:00, 11:00, 13:00, 14:00');
    }

    public function test_order_page_loads_successfully()
    {
        $response = $this->get('/order');
        
        $response->assertStatus(200);
        $response->assertSee('Form Order Service');
    }

    public function test_order_page_shows_time_slots_from_settings()
    {
        $response = $this->get('/order');
        
        $response->assertStatus(200);
        $response->assertSee('08:00');
        $response->assertSee('14:00');
    }

    public function test_order_can_be_created_with_valid_data()
    {
        $orderData = [
            'service_id' => $this->service->id,
            'name' => 'John Doe',
            'phone' => '08123456789',
            'address' => 'Jl. Test No. 123',
            'ac_type' => 'split',
            'ac_capacity' => '1pk',
            'ac_quantity' => 1,
            'scheduled_date' => now()->addDay()->format('Y-m-d'),
            'scheduled_time' => '09:00',
        ];
        
        $response = $this->post('/order', $orderData);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'scheduled_time' => '09:00',
        ]);
        $this->assertDatabaseHas('customers', [
            'name' => 'John Doe',
            'phone' => '08123456789',
        ]);
    }

    public function test_order_fails_with_missing_required_fields()
    {
        $response = $this->post('/order', []);
        
        $response->assertSessionHasErrors([
            'service_id', 'name', 'phone', 'address', 
            'ac_type', 'ac_capacity', 'ac_quantity',
            'scheduled_date', 'scheduled_time'
        ]);
    }

    public function test_order_with_valid_promo_code()
    {
        // Create active promo
        $promo = Promo::create([
            'name' => 'Test Promo',
            'code' => 'TEST10',
            'type' => 'percentage',
            'value' => 10,
            'is_active' => true,
        ]);
        
        $orderData = [
            'service_id' => $this->service->id,
            'name' => 'John Doe',
            'phone' => '08123456789',
            'address' => 'Jl. Test No. 123',
            'ac_type' => 'split',
            'ac_capacity' => '1pk',
            'ac_quantity' => 1,
            'scheduled_date' => now()->addDay()->format('Y-m-d'),
            'scheduled_time' => '09:00',
            'promo_code' => 'TEST10',
        ];
        
        $response = $this->post('/order', $orderData);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'promo_id' => $promo->id,
        ]);
    }

    public function test_order_tracking_page_works()
    {
        // Create order with customer
        $customer = Customer::create([
            'name' => 'Test Customer',
            'phone' => '08123456789',
            'address' => 'Jl. Test Address No. 1',
        ]);
        
        $order = Order::create([
            'order_code' => 'AC-TEST001',
            'customer_id' => $customer->id,
            'service_id' => $this->service->id,
            'technician_id' => $this->technician->id,
            'ac_type' => 'split',
            'ac_capacity' => '1pk',
            'ac_quantity' => 1,
            'address' => 'Test Address',
            'scheduled_date' => now()->addDay(),
            'scheduled_time' => '09:00',
            'service_price' => 50000,
            'base_price' => 50000,
            'total_price' => 50000,
            'status' => 'pending',
        ]);
        
        $response = $this->get('/track?query=AC-TEST001');
        
        $response->assertStatus(200);
        $response->assertSee('AC-TEST001');
    }
}
