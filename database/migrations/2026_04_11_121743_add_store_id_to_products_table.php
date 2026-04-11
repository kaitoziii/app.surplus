<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            // FIX: hanya tambah kalau belum ada
            if (!Schema::hasColumn('products', 'store_id')) {
                $table->foreignId('store_id')
                    ->constrained()
                    ->cascadeOnDelete();
            }

            // tambahan field lain (kalau belum ada)
            if (!Schema::hasColumn('products', 'is_available')) {
                $table->boolean('is_available')->default(true);
            }

            if (!Schema::hasColumn('products', 'pickup_deadline')) {
                $table->timestamp('pickup_deadline')->nullable();
            }

            if (!Schema::hasColumn('products', 'stock')) {
                $table->integer('stock')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {

            if (Schema::hasColumn('products', 'store_id')) {
                $table->dropForeign(['store_id']);
                $table->dropColumn('store_id');
            }

            $table->dropColumn([
                'is_available',
                'pickup_deadline',
                'stock'
            ]);
        });
    }
};