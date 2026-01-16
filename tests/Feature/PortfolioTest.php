<?php

namespace Tests\Feature;

use App\Models\Portfolio;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PortfolioTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function public_gallery_page_loads_successfully()
    {
        $response = $this->get('/gallery');
        
        $response->assertStatus(200);
        $response->assertSee('Hasil Kerja Kami');
    }

    /** @test */
    public function public_gallery_shows_published_portfolios()
    {
        $portfolio = Portfolio::factory()->create([
            'title' => 'Test Portfolio',
            'is_published' => true,
        ]);

        $response = $this->get('/gallery');
        
        $response->assertStatus(200);
        $response->assertSee('Test Portfolio');
    }

    /** @test */
    public function public_gallery_hides_unpublished_portfolios()
    {
        $portfolio = Portfolio::factory()->create([
            'title' => 'Hidden Portfolio',
            'is_published' => false,
        ]);

        $response = $this->get('/gallery');
        
        $response->assertStatus(200);
        $response->assertDontSee('Hidden Portfolio');
    }

    /** @test */
    public function admin_can_view_portfolio_index()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/admin/portfolios');
        
        $response->assertStatus(200);
        $response->assertSee('Kelola Portfolio');
    }

    /** @test */
    public function admin_can_view_create_portfolio_form()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/admin/portfolios/create');
        
        $response->assertStatus(200);
        $response->assertSee('Tambah Portfolio');
    }

    /** @test */
    public function admin_can_create_portfolio()
    {
        $user = User::factory()->create();
        $service = Service::factory()->create();
        
        $response = $this->actingAs($user)->post('/admin/portfolios', [
            'title' => 'New Portfolio',
            'description' => 'Test description',
            'service_id' => $service->id,
            'before_image' => UploadedFile::fake()->create('before.jpg', 100, 'image/jpeg'),
            'after_image' => UploadedFile::fake()->create('after.jpg', 100, 'image/jpeg'),
            'is_published' => true,
            'sort_order' => 0,
        ]);
        
        $response->assertRedirect('/admin/portfolios');
        $this->assertDatabaseHas('portfolios', [
            'title' => 'New Portfolio',
        ]);
    }

    /** @test */
    public function admin_can_delete_portfolio()
    {
        $user = User::factory()->create();
        $portfolio = Portfolio::factory()->create();
        
        $response = $this->actingAs($user)->delete("/admin/portfolios/{$portfolio->id}");
        
        $response->assertRedirect('/admin/portfolios');
        $this->assertDatabaseMissing('portfolios', [
            'id' => $portfolio->id,
        ]);
    }

    /** @test */
    public function homepage_shows_gallery_preview()
    {
        $portfolio = Portfolio::factory()->create([
            'title' => 'Homepage Preview',
            'is_published' => true,
        ]);

        $response = $this->get('/');
        
        $response->assertStatus(200);
        $response->assertSee('Hasil Kerja Kami');
        $response->assertSee('Homepage Preview');
    }
}
