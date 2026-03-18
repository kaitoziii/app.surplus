<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $merchant1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@surplus.id',
            'password' => Hash::make('password'),
            'phone' => '08111234567',
            'role' => 'seller',
            'latitude' => -6.2088,
            'longitude' => 106.8456,
        ]);

        $merchant2 = User::create([
            'name' => 'Sari Dewi',
            'email' => 'sari@surplus.id',
            'password' => Hash::make('password'),
            'phone' => '08222345678',
            'role' => 'seller',
            'latitude' => -6.1751,
            'longitude' => 106.8272,
        ]);

        User::create([
            'name' => 'Andi Pembeli',
            'email' => 'andi@surplus.id',
            'password' => Hash::make('password'),
            'phone' => '08333456789',
            'role' => 'buyer',
            'latitude' => -6.2100,
            'longitude' => 106.8400,
        ]);

        $store1 = Store::create([
            'user_id' => $merchant1->id,
            'name' => 'RM Padang Barokah',
            'address' => 'Jl. Medan Merdeka Barat No. 12, Jakarta Pusat',
            'latitude' => -6.1754,
            'longitude' => 106.8272,
            'category' => 'restaurant',
            'phone' => '02112345678',
            'description' => 'Restoran Masakan Padang autentik sejak 1995',
            'is_open' => true,
            'open_time' => '08:00:00',
            'close_time' => '22:00:00',
        ]);

        $store2 = Store::create([
            'user_id' => $merchant1->id,
            'name' => 'Sunrise Bakery',
            'address' => 'Jl. Jend. Sudirman No. 45, Jakarta Selatan',
            'latitude' => -6.2088,
            'longitude' => 106.8456,
            'category' => 'bakery',
            'phone' => '02123456789',
            'description' => 'Roti dan kue segar dipanggang setiap hari',
            'is_open' => true,
            'open_time' => '06:00:00',
            'close_time' => '20:00:00',
        ]);

        $store3 = Store::create([
            'user_id' => $merchant2->id,
            'name' => 'FreshMart Kemang',
            'address' => 'Jl. Kemang Raya No. 8, Jakarta Selatan',
            'latitude' => -6.2607,
            'longitude' => 106.8187,
            'category' => 'supermarket',
            'phone' => '02134567890',
            'description' => 'Supermarket segar dengan produk lokal pilihan',
            'is_open' => true,
            'open_time' => '07:00:00',
            'close_time' => '23:00:00',
        ]);

        $store4 = Store::create([
            'user_id' => $merchant2->id,
            'name' => 'Kopi & Roti Blok M',
            'address' => 'Blok M Square Lt. 2, Jakarta Selatan',
            'latitude' => -6.2444,
            'longitude' => 106.7996,
            'category' => 'cafe',
            'phone' => '02145678901',
            'description' => 'Kopi specialty dan roti artisan',
            'is_open' => true,
            'open_time' => '07:00:00',
            'close_time' => '21:00:00',
        ]);

        Product::create([
            'store_id' => $store1->id,
            'name' => 'Nasi Padang Komplit',
            'description' => 'Nasi dengan 5 lauk pilihan, sudah termasuk rendang dan gulai',
            'original_price' => 45000,
            'stock' => 15,
            'unit' => 'porsi',
            'category' => 'general',
            'pickup_deadline' => now()->addHours(6),
            'expiry_date' => now()->toDateString(),
            'is_available' => true,
        ]);

        Product::create([
            'store_id' => $store1->id,
            'name' => 'Gulai Ayam Porsi Besar',
            'description' => 'Gulai ayam khas Padang, bumbu rempah pilihan',
            'original_price' => 25000,
            'stock' => 35,
            'unit' => 'porsi',
            'category' => 'meat',
            'pickup_deadline' => now()->addHours(3),
            'expiry_date' => now()->toDateString(),
            'is_available' => true,
        ]);

        Product::create([
            'store_id' => $store3->id,
            'name' => 'Susu Segar Full Cream 1L',
            'description' => 'Susu segar pasteurisasi, mendekati tanggal expired',
            'original_price' => 22000,
            'stock' => 60,
            'unit' => 'botol',
            'category' => 'dairy',
            'pickup_deadline' => now()->addHours(2),
            'expiry_date' => now()->toDateString(),
            'is_available' => true,
        ]);

        Product::create([
            'store_id' => $store3->id,
            'name' => 'Ikan Salmon Fillet 500gr',
            'description' => 'Salmon segar impor, siap masak',
            'original_price' => 85000,
            'stock' => 8,
            'unit' => 'pack',
            'category' => 'seafood',
            'pickup_deadline' => now()->addMinutes(90),
            'expiry_date' => now()->toDateString(),
            'is_available' => true,
        ]);

        Product::create([
            'store_id' => $store2->id,
            'name' => 'Roti Tawar Gandum',
            'description' => 'Roti tawar gandum utuh, dibuat fresh pagi ini',
            'original_price' => 18000,
            'stock' => 80,
            'unit' => 'loaf',
            'category' => 'bakery',
            'pickup_deadline' => now()->addHours(4),
            'expiry_date' => now()->addDay()->toDateString(),
            'is_available' => true,
        ]);

        Product::create([
            'store_id' => $store2->id,
            'name' => 'Croissant Mentega Original',
            'description' => 'Croissant berlapis mentega Prancis, crispy di luar lembut di dalam',
            'original_price' => 32000,
            'stock' => 5,
            'unit' => 'pcs',
            'category' => 'bakery',
            'pickup_deadline' => now()->addMinutes(45),
            'expiry_date' => now()->toDateString(),
            'is_available' => true,
        ]);

        Product::create([
            'store_id' => $store4->id,
            'name' => 'Cold Brew Coffee 500ml',
            'description' => 'Cold brew 18 jam, kopi Gayo Aceh premium',
            'original_price' => 55000,
            'stock' => 12,
            'unit' => 'botol',
            'category' => 'beverage',
            'pickup_deadline' => now()->addHours(5),
            'expiry_date' => now()->addDays(2)->toDateString(),
            'is_available' => true,
        ]);

        Product::create([
            'store_id' => $store3->id,
            'name' => 'Paket Sayur Segar Mix 1kg',
            'description' => 'Bayam, kangkung, wortel, buncis, segar dari kebun',
            'original_price' => 15000,
            'stock' => 25,
            'unit' => 'paket',
            'category' => 'produce',
            'pickup_deadline' => now()->addHours(3)->addMinutes(30),
            'expiry_date' => now()->addDay()->toDateString(),
            'is_available' => true,
        ]);
    }
}