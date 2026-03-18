<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')
                ->constrained('stores')
                ->onDelete('cascade');

            $table->string('name');
            $table->text('description')->nullable();

            $table->decimal('original_price', 12, 2);

            $table->integer('stock')->default(0);
            $table->string('unit', 20)->default('pcs');

            $table->string('category', 50)->default('general');

            $table->date('expiry_date')->nullable();
            $table->dateTime('pickup_deadline');

            $table->string('image_url')->nullable();
            $table->boolean('is_available')->default(true);

            $table->timestamps();

            $table->index('store_id');
            $table->index('pickup_deadline');
            $table->index('is_available');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};