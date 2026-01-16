<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('value', 10, 2); // 10 for 10% or 25000 for Rp 25.000
            $table->decimal('min_order', 10, 2)->nullable(); // minimum order amount
            $table->decimal('max_discount', 10, 2)->nullable(); // max discount cap for percentage
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete(); // optional service restriction
            $table->integer('usage_limit')->nullable(); // max number of uses
            $table->integer('usage_count')->default(0); // current usage
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
