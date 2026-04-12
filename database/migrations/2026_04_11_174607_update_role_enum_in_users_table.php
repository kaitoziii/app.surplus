<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // SQLite: skip full migration karena CHECK constraint lama akan menolak
        // perubahan buyer/seller -> consumer/merchant.
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        // MySQL / MariaDB
        DB::statement("
            ALTER TABLE users
            MODIFY COLUMN role VARCHAR(20) NOT NULL DEFAULT 'consumer'
        ");

        DB::statement("
            UPDATE users SET role = 'consumer' WHERE role = 'buyer'
        ");

        DB::statement("
            UPDATE users SET role = 'merchant' WHERE role = 'seller'
        ");

        DB::statement("
            ALTER TABLE users
            MODIFY COLUMN role ENUM('admin', 'merchant', 'consumer') NOT NULL DEFAULT 'consumer'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // SQLite: skip full rollback juga
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        // MySQL / MariaDB
        DB::statement("
            ALTER TABLE users
            MODIFY COLUMN role VARCHAR(20) NOT NULL DEFAULT 'buyer'
        ");

        DB::statement("
            UPDATE users SET role = 'buyer' WHERE role = 'consumer'
        ");

        DB::statement("
            UPDATE users SET role = 'seller' WHERE role = 'merchant'
        ");

        DB::statement("
            ALTER TABLE users
            MODIFY COLUMN role ENUM('buyer', 'seller', 'admin') NOT NULL DEFAULT 'buyer'
        ");
    }
};