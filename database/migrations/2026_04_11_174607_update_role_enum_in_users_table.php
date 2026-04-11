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
        // Step 1: Ubah ke VARCHAR dulu (bebas nilai)
        \Illuminate\Support\Facades\DB::statement("
            ALTER TABLE users 
            MODIFY COLUMN role VARCHAR(20) NOT NULL DEFAULT 'consumer'
        ");

        // Step 2: Update nilai lama ke nilai baru
        \Illuminate\Support\Facades\DB::statement("
            UPDATE users SET role = 'consumer' WHERE role = 'buyer'
        ");
        \Illuminate\Support\Facades\DB::statement("
            UPDATE users SET role = 'merchant' WHERE role = 'seller'
        ");

        // Step 3: Ubah ke ENUM baru
        \Illuminate\Support\Facades\DB::statement("
            ALTER TABLE users 
            MODIFY COLUMN role ENUM('admin', 'merchant', 'consumer') NOT NULL DEFAULT 'consumer'
        ");
    }

    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("
            ALTER TABLE users 
            MODIFY COLUMN role VARCHAR(20) NOT NULL DEFAULT 'buyer'
        ");
        \Illuminate\Support\Facades\DB::statement("
            UPDATE users SET role = 'buyer' WHERE role = 'consumer'
        ");
        \Illuminate\Support\Facades\DB::statement("
            UPDATE users SET role = 'seller' WHERE role = 'merchant'
        ");
        \Illuminate\Support\Facades\DB::statement("
            ALTER TABLE users 
            MODIFY COLUMN role ENUM('buyer', 'seller', 'admin') NOT NULL DEFAULT 'buyer'
        ");
    }
};
