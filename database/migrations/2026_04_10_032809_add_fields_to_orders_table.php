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
        $table->foreignId('user_id')->after('id')->constrained()->cascadeOnDelete();
        $table->foreignId('merchant_id')->after('user_id')->constrained('users')->cascadeOnDelete();
        $table->decimal('total', 15, 2)->after('merchant_id');
        $table->string('status')->default('pending')->after('total');
    });
}

public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropForeign(['user_id']);
        $table->dropForeign(['merchant_id']);

        $table->dropColumn(['user_id', 'merchant_id', 'total', 'status']);
    });
}
};
