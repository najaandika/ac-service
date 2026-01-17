<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\TechnicianController;
use App\Http\Controllers\Admin\PromoController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/robots.txt', [\App\Http\Controllers\SitemapController::class, 'robots']);
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

// Service routes
Route::get('/layanan', [ServiceController::class, 'index'])->name('services.index');
Route::get('/layanan/{slug}', [ServiceController::class, 'show'])->name('services.show');

// Testimoni route
Route::get('/testimoni', [\App\Http\Controllers\TestimoniController::class, 'index'])->name('testimoni.index');

// Gallery route
Route::get('/gallery', \App\Http\Controllers\GalleryController::class)->name('gallery');

// FAQ route
Route::get('/faq', \App\Http\Controllers\FaqController::class)->name('faq');

// Order routes
Route::get('/order', [OrderController::class, 'create'])->name('order.create');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::get('/order/success', [OrderController::class, 'success'])->name('order.success');
Route::get('/track', [OrderController::class, 'track'])->name('order.track');

// Promo validation API (public)
Route::post('/api/promo/validate', [OrderController::class, 'validatePromo'])->name('promo.validate');

// Invoice routes (public with order code access)
Route::get('/invoice/{order}/download', [\App\Http\Controllers\InvoiceController::class, 'download'])->name('invoice.download');
Route::get('/invoice/{order}/view', [\App\Http\Controllers\InvoiceController::class, 'stream'])->name('invoice.view');

// Rating routes
Route::get('/order/{code}/rate', [\App\Http\Controllers\RatingController::class, 'show'])->name('order.rate.show');
Route::post('/order/{code}/rate', [\App\Http\Controllers\RatingController::class, 'store'])->name('order.rate.store');

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes (protected)
Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Order management
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [AdminOrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [AdminOrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::patch('/orders/{order}/technician', [AdminOrderController::class, 'assignTechnician'])->name('orders.technician');
    Route::patch('/orders/{order}/departed', [AdminOrderController::class, 'markDeparted'])->name('orders.departed');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
    Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');

    // Service management
    Route::resource('services', AdminServiceController::class);
    Route::patch('/services/{service}/toggle', [AdminServiceController::class, 'toggleStatus'])->name('services.toggle');

    // Technician management
    Route::resource('technicians', TechnicianController::class);
    Route::patch('/technicians/{technician}/toggle', [TechnicianController::class, 'toggleStatus'])->name('technicians.toggle');

    // Promo management
    Route::resource('promos', PromoController::class);
    Route::patch('/promos/{promo}/toggle', [PromoController::class, 'toggleStatus'])->name('promos.toggle');

    // Customer management (view-only)
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    // Portfolio management
    Route::resource('portfolios', \App\Http\Controllers\Admin\PortfolioController::class)->except(['show']);
    // Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    
    // Notification API endpoints (for AJAX polling)
    Route::get('/api/pending-orders-count', function () {
        return response()->json([
            'count' => \App\Models\Order::where('status', 'pending')->count()
        ]);
    })->name('api.pending-orders-count');
    
    Route::get('/api/notification-settings', function () {
        return response()->json([
            'enabled' => \App\Models\Setting::get('notification_enabled', '1') === '1',
            'interval' => (int) \App\Models\Setting::get('notification_interval', 30),
            'audioUrl' => \App\Models\Setting::get('notification_audio') 
                ? asset('storage/' . \App\Models\Setting::get('notification_audio')) 
                : null
        ]);
    })->name('api.notification-settings');
});

