<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->string('name');
            $table->text('address');

            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);

            $table->string('category', 50)->default('general');
            $table->string('phone', 20)->nullable();
            $table->string('logo_url')->nullable();
            $table->boolean('is_open')->default(true);
            $table->text('description')->nullable();
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();

            $table->timestamps();

            $table->index(['latitude', 'longitude'], 'stores_geospatial_idx');
            $table->index('is_open');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};