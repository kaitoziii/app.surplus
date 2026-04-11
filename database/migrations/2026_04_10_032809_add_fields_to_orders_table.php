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
        Schema::table('orders', function (Blueprint $table) {

            // ❌ HAPUS user_id (SUDAH ADA DI create_orders_table)

            // ✅ tambahin field lain saja
            if (!Schema::hasColumn('orders', 'merchant_id')) {
                $table->foreignId('merchant_id')
                    ->after('user_id')
                    ->constrained('users')
                    ->cascadeOnDelete();
            }

            if (!Schema::hasColumn('orders', 'total')) {
                $table->decimal('total', 15, 2)
                    ->after('merchant_id');
            }

            if (!Schema::hasColumn('orders', 'status')) {
                $table->string('status')
                    ->default('pending')
                    ->after('total');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            if (Schema::hasColumn('orders', 'merchant_id')) {
                $table->dropForeign(['merchant_id']);
            }

            $table->dropColumn([
                'merchant_id',
                'total',
                'status'
            ]);
        });
    }
};