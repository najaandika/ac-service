<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code', 10)->unique();
            
            // Relations
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('technician_id')->nullable()->constrained()->onDelete('set null');
            
            // AC Details
            $table->enum('ac_type', ['split', 'cassette', 'standing', 'central', 'window'])->default('split');
            $table->enum('ac_capacity', ['0.5pk', '0.75pk', '1pk', '1.5pk', '2pk', '2.5pk', '3pk', '5pk'])->default('1pk');
            $table->integer('ac_quantity')->default(1);
            
            // Scheduling
            $table->date('scheduled_date');
            $table->enum('scheduled_time', ['pagi', 'siang', 'sore'])->default('pagi');
            
            // Additional info
            $table->text('notes')->nullable();
            $table->string('photo')->nullable();
            
            // Pricing
            $table->decimal('service_price', 12, 2);
            $table->decimal('additional_fee', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total_price', 12, 2);
            
            // Status
            $table->enum('status', ['pending', 'confirmed', 'on_the_way', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamps();
            
            $table->index('order_code');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
