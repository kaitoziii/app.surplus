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
<<<<<<<< HEAD:database/migrations/2026_04_06_071412_add_discount_fields_to_products.php
        Schema::table('products', function (Blueprint $table) {
            //
========
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
>>>>>>>> origin/main:database/migrations/2026_04_09_200253_create_orders_table.php
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
<<<<<<<< HEAD:database/migrations/2026_04_06_071412_add_discount_fields_to_products.php
        Schema::table('products', function (Blueprint $table) {
            //
        });
========
        Schema::dropIfExists('orders');
>>>>>>>> origin/main:database/migrations/2026_04_09_200253_create_orders_table.php
    }
};
