<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('siup_nib')->nullable()->after('status');
            $table->string('siup_nib_file')->nullable()->after('siup_nib');
            $table->text('rejection_reason')->nullable()->after('siup_nib_file');
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['siup_nib', 'siup_nib_file', 'rejection_reason']);
        });
    }
};