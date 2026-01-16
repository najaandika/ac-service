<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL to change ENUM to VARCHAR
        DB::statement("ALTER TABLE orders MODIFY COLUMN scheduled_time VARCHAR(10) DEFAULT '09:00'");
        
        // Update existing data to new format
        DB::table('orders')->where('scheduled_time', 'pagi')->update(['scheduled_time' => '09:00']);
        DB::table('orders')->where('scheduled_time', 'siang')->update(['scheduled_time' => '13:00']);
        DB::table('orders')->where('scheduled_time', 'sore')->update(['scheduled_time' => '16:00']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert back if needed
        DB::statement("ALTER TABLE orders MODIFY COLUMN scheduled_time ENUM('pagi', 'siang', 'sore') DEFAULT 'pagi'");
    }
};
