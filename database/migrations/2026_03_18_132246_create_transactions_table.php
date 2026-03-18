<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('restrict');
            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('restrict');

            $table->integer('quantity')->default(1);

            $table->decimal('original_price_snapshot', 12, 2);
            $table->decimal('price_paid', 12, 2);
            $table->decimal('discount_applied', 5, 2)->default(0);
            $table->decimal('savings_amount', 12, 2)->default(0);

            $table->enum('status', ['pending', 'confirmed', 'picked_up', 'cancelled'])
                ->default('pending');

            $table->string('pickup_code', 10)->nullable();
            $table->dateTime('picked_up_at')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('user_id');
            $table->index('product_id');
            $table->index('status');
            $table->unique('pickup_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};