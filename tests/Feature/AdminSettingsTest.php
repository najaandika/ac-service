<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->admin = User::factory()->create([
            'email' => 'admin@test.com',
        ]);
    }

    public function test_settings_page_requires_authentication()
    {
        $response = $this->get('/admin/settings');
        
        $response->assertRedirect('/login');
    }

    public function test_settings_page_loads_for_authenticated_user()
    {
        $response = $this->actingAs($this->admin)->get('/admin/settings');
        
        $response->assertStatus(200);
        $response->assertSee('Pengaturan');
    }

    public function test_time_slots_can_be_saved()
    {
        $response = $this->actingAs($this->admin)->put('/admin/settings', [
            'site_name' => 'Test Site',
            'time_slots' => '08:00, 09:00, 10:00, 11:00, 12:00',
            'active_tab' => 'contact',
        ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('settings', [
            'key' => 'time_slots',
            'value' => '08:00, 09:00, 10:00, 11:00, 12:00',
        ]);
    }

    public function test_settings_redirect_to_same_tab_after_save()
    {
        $response = $this->actingAs($this->admin)->put('/admin/settings', [
            'site_name' => 'Test Site',
            'active_tab' => 'contact',
        ]);
        
        $response->assertRedirect(route('admin.settings.index', ['tab' => 'contact']));
    }

    public function test_settings_saves_service_areas()
    {
        $response = $this->actingAs($this->admin)->put('/admin/settings', [
            'site_name' => 'Test Site',
            'service_areas' => 'Bandung, Cimahi, Sumedang',
            'active_tab' => 'contact',
        ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('settings', [
            'key' => 'service_areas',
            'value' => 'Bandung, Cimahi, Sumedang',
        ]);
    }
}
