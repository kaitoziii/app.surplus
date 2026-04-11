<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== ADMIN =====
        User::updateOrCreate(['email' => 'admin@appsurplus.com'], [
            'name'              => 'Admin App.Surplus',
            'password'          => Hash::make('password'),
            'role'              => 'admin',
            'email_verified_at' => now(),
        ]);

        // ===== CONSUMERS (Sidoarjo) =====
        $consumers = [
            ['name' => 'Budi Santoso',     'email' => 'budi@gmail.com',    'phone' => '081234567801', 'address' => 'Jl. Jenggolo No. 12, Sidoarjo'],
            ['name' => 'Siti Rahayu',      'email' => 'siti@gmail.com',    'phone' => '081234567802', 'address' => 'Jl. Pahlawan No. 45, Sidoarjo'],
            ['name' => 'Rizky Pratama',    'email' => 'rizky@gmail.com',   'phone' => '081234567803', 'address' => 'Jl. Ahmad Yani No. 7, Waru, Sidoarjo'],
            ['name' => 'Dewi Anggraini',   'email' => 'dewi@gmail.com',    'phone' => '081234567804', 'address' => 'Jl. Raya Gedangan No. 3, Sidoarjo'],
            ['name' => 'Fajar Nugroho',    'email' => 'fajar@gmail.com',   'phone' => '081234567805', 'address' => 'Perum Taman Pinang Indah, Sidoarjo'],
            ['name' => 'Anisa Putri',      'email' => 'anisa@gmail.com',   'phone' => '081234567806', 'address' => 'Jl. Raya Porong No. 12, Sidoarjo'],
            ['name' => 'Dimas Ardiansyah', 'email' => 'dimas@gmail.com',   'phone' => '081234567807', 'address' => 'Jl. Raya Tanggulangin No. 5, Sidoarjo'],
            ['name' => 'Mega Wulandari',   'email' => 'mega@gmail.com',    'phone' => '081234567808', 'address' => 'Perum Delta Sari Indah, Waru, Sidoarjo'],
            ['name' => 'Hendra Setiawan',  'email' => 'hendra@gmail.com',  'phone' => '081234567809', 'address' => 'Jl. Raya Krian No. 8, Sidoarjo'],
            ['name' => 'Rina Kusumawati',  'email' => 'rina@gmail.com',    'phone' => '081234567810', 'address' => 'Jl. Raya Candi No. 22, Sidoarjo'],
        ];

        foreach ($consumers as $c) {
            User::updateOrCreate(['email' => $c['email']], array_merge($c, [
                'password'          => Hash::make('password'),
                'role'              => 'consumer',
                'email_verified_at' => now(),
            ]));
        }

        // ===== MERCHANTS (Sidoarjo) =====
        $merchantUsers = [
            ['name' => 'Warung Pak Suro',   'email' => 'paksuro@gmail.com',      'phone' => '081234567811', 'address' => 'Jl. Diponegoro No. 88, Sidoarjo'],
            ['name' => 'Bakery Roti Manis', 'email' => 'rotimanis@gmail.com',    'phone' => '081234567812', 'address' => 'Jl. Monginsidi No. 21, Sidoarjo'],
            ['name' => 'Cafe Hijau Daun',   'email' => 'hijaudaun@gmail.com',    'phone' => '081234567813', 'address' => 'Jl. Raya Waru No. 15, Waru, Sidoarjo'],
            ['name' => 'RM Seafood Delta',  'email' => 'seafooddelta@gmail.com', 'phone' => '081234567814', 'address' => 'Jl. Raya Buduran No. 5, Sidoarjo'],
            ['name' => 'Toko Kue Bu Endah', 'email' => 'buendah@gmail.com',      'phone' => '081234567815', 'address' => 'Jl. Veteran No. 33, Sidoarjo'],
        ];

        $storeData = [
            ['name'=>'Warung Pak Suro',   'address'=>'Jl. Diponegoro No. 88, Sidoarjo',         'latitude'=>-7.4478, 'longitude'=>112.7183, 'status'=>'approved'],
            ['name'=>'Bakery Roti Manis', 'address'=>'Jl. Monginsidi No. 21, Sidoarjo',          'latitude'=>-7.4512, 'longitude'=>112.7142, 'status'=>'approved'],
            ['name'=>'Cafe Hijau Daun',   'address'=>'Jl. Raya Waru No. 15, Waru, Sidoarjo',    'latitude'=>-7.3895, 'longitude'=>112.7734, 'status'=>'approved'],
            ['name'=>'RM Seafood Delta',  'address'=>'Jl. Raya Buduran No. 5, Sidoarjo',         'latitude'=>-7.4621, 'longitude'=>112.7089, 'status'=>'approved'],
            ['name'=>'Toko Kue Bu Endah', 'address'=>'Jl. Veteran No. 33, Sidoarjo',             'latitude'=>-7.4456, 'longitude'=>112.7201, 'status'=>'approved'],
        ];

        $stores = [];
        foreach ($merchantUsers as $i => $m) {
            $user = User::updateOrCreate(['email' => $m['email']], array_merge($m, [
                'password'          => Hash::make('password'),
                'role'              => 'merchant',
                'email_verified_at' => now(),
            ]));
            $store = Store::updateOrCreate(
                ['user_id' => $user->id],
                array_merge($storeData[$i], ['user_id' => $user->id])
            );
            $stores[] = $store;
        }

        // ===== PRODUK SURPLUS =====
        // Hapus produk lama dulu supaya tidak double
        Product::whereIn('store_id', collect($stores)->pluck('id'))->delete();

        $products = [
            // Warung Pak Suro
            ['store_id'=>$stores[0]->id,'name'=>'Nasi Rawon Sisa Sore','description'=>'Rawon khas Sidoarjo, bumbu rempah lengkap, masih hangat.','original_price'=>25000,'discount_price'=>15000,'stock'=>8,'reserved_stock'=>0,'category'=>'general','unit'=>'porsi','pickup_deadline'=>Carbon::now()->addHours(2),'expiry_date'=>Carbon::now()->addDay(),'is_available'=>true],
            ['store_id'=>$stores[0]->id,'name'=>'Lontong Kupang','description'=>'Makanan khas Sidoarjo dengan kupang segar dan lento.','original_price'=>18000,'discount_price'=>10000,'stock'=>5,'reserved_stock'=>0,'category'=>'general','unit'=>'porsi','pickup_deadline'=>Carbon::now()->addHours(1)->addMinutes(30),'expiry_date'=>Carbon::now()->addDay(),'is_available'=>true],
            ['store_id'=>$stores[0]->id,'name'=>'Soto Ayam Lamongan','description'=>'Soto ayam dengan kuah bening gurih, plus lontong dan perkedel.','original_price'=>22000,'discount_price'=>13000,'stock'=>10,'reserved_stock'=>0,'category'=>'general','unit'=>'porsi','pickup_deadline'=>Carbon::now()->addHours(3),'expiry_date'=>Carbon::now()->addDay(),'is_available'=>true],
            // Bakery Roti Manis
            ['store_id'=>$stores[1]->id,'name'=>'Roti Tawar Spesial','description'=>'Roti tawar lembut, fresh from oven pagi ini.','original_price'=>20000,'discount_price'=>12000,'stock'=>15,'reserved_stock'=>0,'category'=>'bread','unit'=>'loaf','pickup_deadline'=>Carbon::now()->addHours(4),'expiry_date'=>Carbon::now()->addDays(2),'is_available'=>true],
            ['store_id'=>$stores[1]->id,'name'=>'Croissant Butter','description'=>'Croissant berlapis butter premium, renyah di luar lembut di dalam.','original_price'=>15000,'discount_price'=>8000,'stock'=>10,'reserved_stock'=>0,'category'=>'bakery','unit'=>'pcs','pickup_deadline'=>Carbon::now()->addHours(5),'expiry_date'=>Carbon::now()->addDay(),'is_available'=>true],
            ['store_id'=>$stores[1]->id,'name'=>'Donat Glazed Mix','description'=>'6 donat aneka rasa glazed, fresh dari dapur.','original_price'=>30000,'discount_price'=>18000,'stock'=>8,'reserved_stock'=>0,'category'=>'bakery','unit'=>'box (6 pcs)','pickup_deadline'=>Carbon::now()->addHours(3)->addMinutes(30),'expiry_date'=>Carbon::now()->addDay(),'is_available'=>true],
            // Cafe Hijau Daun
            ['store_id'=>$stores[2]->id,'name'=>'Smoothie Bowl Buah Segar','description'=>'Smoothie bowl dengan topping granola dan buah tropis.','original_price'=>35000,'discount_price'=>20000,'stock'=>6,'reserved_stock'=>0,'category'=>'beverage','unit'=>'bowl','pickup_deadline'=>Carbon::now()->addHours(2)->addMinutes(30),'expiry_date'=>Carbon::now()->addHours(8),'is_available'=>true],
            ['store_id'=>$stores[2]->id,'name'=>'Sandwich Ayam Panggang','description'=>'Sandwich dengan ayam panggang, selada, tomat, dan saus spesial.','original_price'=>28000,'discount_price'=>16000,'stock'=>4,'reserved_stock'=>0,'category'=>'general','unit'=>'pcs','pickup_deadline'=>Carbon::now()->addHours(5),'expiry_date'=>Carbon::now()->addDay(),'is_available'=>true],
            // RM Seafood Delta
            ['store_id'=>$stores[3]->id,'name'=>'Udang Goreng Tepung','description'=>'Udang segar delta Sidoarjo, goreng tepung renyah.','original_price'=>45000,'discount_price'=>28000,'stock'=>3,'reserved_stock'=>0,'category'=>'seafood','unit'=>'porsi','pickup_deadline'=>Carbon::now()->addHours(1)->addMinutes(45),'expiry_date'=>Carbon::now()->addHours(6),'is_available'=>true],
            ['store_id'=>$stores[3]->id,'name'=>'Cumi Bakar Bumbu Kecap','description'=>'Cumi segar ukuran jumbo, dibakar dengan bumbu kecap manis.','original_price'=>55000,'discount_price'=>35000,'stock'=>5,'reserved_stock'=>0,'category'=>'seafood','unit'=>'porsi','pickup_deadline'=>Carbon::now()->addHours(3)->addMinutes(30),'expiry_date'=>Carbon::now()->addDay(),'is_available'=>true],
            ['store_id'=>$stores[3]->id,'name'=>'Kepiting Saus Tiram','description'=>'Kepiting segar khas delta Sidoarjo, dimasak dengan saus tiram premium.','original_price'=>85000,'discount_price'=>55000,'stock'=>2,'reserved_stock'=>0,'category'=>'seafood','unit'=>'porsi','pickup_deadline'=>Carbon::now()->addHours(2),'expiry_date'=>Carbon::now()->addHours(8),'is_available'=>true],
            // Toko Kue Bu Endah
            ['store_id'=>$stores[4]->id,'name'=>'Klepon Isi Gula Merah','description'=>'Klepon tradisional khas Jawa, isi gula merah, balur kelapa parut.','original_price'=>12000,'discount_price'=>7000,'stock'=>20,'reserved_stock'=>0,'category'=>'bakery','unit'=>'pack (10 pcs)','pickup_deadline'=>Carbon::now()->addHours(6),'expiry_date'=>Carbon::now()->addDay(),'is_available'=>true],
            ['store_id'=>$stores[4]->id,'name'=>'Lapis Legit Spesial','description'=>'Lapis legit dengan 18 lapisan, harum rempah kayu manis.','original_price'=>65000,'discount_price'=>40000,'stock'=>2,'reserved_stock'=>0,'category'=>'bakery','unit'=>'loyang kecil','pickup_deadline'=>Carbon::now()->addHours(8),'expiry_date'=>Carbon::now()->addDays(3),'is_available'=>true],
            ['store_id'=>$stores[4]->id,'name'=>'Risoles Mayo Keju','description'=>'Risoles kulit tipis isi mayo keju, digoreng golden crispy.','original_price'=>15000,'discount_price'=>9000,'stock'=>12,'reserved_stock'=>0,'category'=>'bakery','unit'=>'pack (5 pcs)','pickup_deadline'=>Carbon::now()->addHours(4),'expiry_date'=>Carbon::now()->addDay(),'is_available'=>true],
        ];

        foreach ($products as $p) {
            Product::create($p);
        }

        $this->command->info('✅ Seeder berhasil! Data yang dibuat:');
        $this->command->info('   - 1 Admin');
        $this->command->info('   - 10 Consumer (area Sidoarjo)');
        $this->command->info('   - 5 Merchant (area Sidoarjo)');
        $this->command->info('   - 14 Produk surplus');
    }
}