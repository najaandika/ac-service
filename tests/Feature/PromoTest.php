<?php

namespace Tests\Feature;

use App\Models\Promo;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->service = Service::create([
            'name' => 'Cuci AC',
            'slug' => 'cuci-ac',
            'description' => 'Layanan cuci AC',
            'price' => 100000,
            'is_active' => true,
        ]);
    }

    public function test_promo_validation_returns_valid_for_active_promo()
    {
        $promo = Promo::create([
            'name' => 'Test Promo',
            'code' => 'VALID10',
            'type' => 'percentage',
            'value' => 10,
            'is_active' => true,
        ]);
        
        $response = $this->postJson('/api/promo/validate', [
            'code' => 'VALID10',
            'subtotal' => 100000,
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['valid' => true]);
    }

    public function test_promo_validation_fails_for_inactive_promo()
    {
        Promo::create([
            'name' => 'Inactive Promo',
            'code' => 'INACTIVE',
            'type' => 'percentage',
            'value' => 10,
            'is_active' => false,
        ]);
        
        $response = $this->postJson('/api/promo/validate', [
            'code' => 'INACTIVE',
            'subtotal' => 100000,
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['valid' => false]);
    }

    public function test_promo_validation_fails_for_expired_promo()
    {
        Promo::create([
            'name' => 'Expired Promo',
            'code' => 'EXPIRED',
            'type' => 'percentage',
            'value' => 10,
            'is_active' => true,
            'end_date' => now()->subDay(),
        ]);
        
        $response = $this->postJson('/api/promo/validate', [
            'code' => 'EXPIRED',
            'subtotal' => 100000,
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['valid' => false]);
    }

    public function test_promo_validation_fails_for_min_order_not_met()
    {
        Promo::create([
            'name' => 'Min Order Promo',
            'code' => 'MINORDER',
            'type' => 'percentage',
            'value' => 10,
            'min_order' => 200000,
            'is_active' => true,
        ]);
        
        $response = $this->postJson('/api/promo/validate', [
            'code' => 'MINORDER',
            'subtotal' => 100000, // Less than min_order
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['valid' => false]);
    }

    public function test_promo_validation_passes_when_min_order_met()
    {
        Promo::create([
            'name' => 'Min Order Promo',
            'code' => 'MINORDER2',
            'type' => 'percentage',
            'value' => 10,
            'min_order' => 100000,
            'is_active' => true,
        ]);
        
        $response = $this->postJson('/api/promo/validate', [
            'code' => 'MINORDER2',
            'subtotal' => 150000, // Greater than min_order
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['valid' => true]);
    }

    public function test_promo_validation_fails_when_usage_limit_reached()
    {
        Promo::create([
            'name' => 'Limited Promo',
            'code' => 'LIMITED',
            'type' => 'percentage',
            'value' => 10,
            'usage_limit' => 5,
            'usage_count' => 5, // Already at limit
            'is_active' => true,
        ]);
        
        $response = $this->postJson('/api/promo/validate', [
            'code' => 'LIMITED',
            'subtotal' => 100000,
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['valid' => false]);
    }

    public function test_promo_percentage_discount_calculation()
    {
        Promo::create([
            'name' => 'Percentage Promo',
            'code' => 'PERCENT20',
            'type' => 'percentage',
            'value' => 20,
            'is_active' => true,
        ]);
        
        $response = $this->postJson('/api/promo/validate', [
            'code' => 'PERCENT20',
            'subtotal' => 100000,
        ]);
        
        $response->assertStatus(200);
        $response->assertJson([
            'valid' => true,
            'discount' => 20000, // 20% of 100000
        ]);
    }

    public function test_promo_fixed_discount_calculation()
    {
        Promo::create([
            'name' => 'Fixed Promo',
            'code' => 'FIXED15K',
            'type' => 'fixed',
            'value' => 15000,
            'is_active' => true,
        ]);
        
        $response = $this->postJson('/api/promo/validate', [
            'code' => 'FIXED15K',
            'subtotal' => 100000,
        ]);
        
        $response->assertStatus(200);
        $response->assertJson([
            'valid' => true,
            'discount' => 15000,
        ]);
    }
}
