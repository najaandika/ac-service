<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->enum('capacity', ['0.5pk', '0.75pk', '1pk', '1.5pk', '2pk', '2.5pk', '3pk', '5pk']);
            $table->decimal('price', 12, 2);
            $table->timestamps();
            
            $table->unique(['service_id', 'capacity']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_prices');
    }
};
