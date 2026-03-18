<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('email');
            $table->decimal('latitude', 10, 7)
                ->nullable()
                ->after('phone');
            $table->decimal('longitude', 10, 7)
                ->nullable()
                ->after('latitude');
            $table->enum('role', ['buyer', 'seller', 'admin'])->default('buyer')->after('longitude');
            $table->string('avatar_url')->nullable()->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'latitude', 'longitude', 'role', 'avatar_url']);
        });
    }
};