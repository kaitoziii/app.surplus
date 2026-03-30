<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Store;
use Illuminate\Support\Facades\Hash;

class MerchantSeeder extends Seeder
{
    public function run(): void
    {
        $merchants = [
            ['name' => 'Warung Bu Sari',  'email' => 'busari@mail.com',    'address' => 'Jl. Merdeka No. 12, Surabaya'],
            ['name' => 'Bakery Lezat',    'email' => 'bakery@mail.com',    'address' => 'Jl. Kenanga No. 5, Surabaya'],
            ['name' => 'Kafe Hijau',      'email' => 'kafehijau@mail.com', 'address' => 'Jl. Pahlawan No. 8, Surabaya'],
        ];

        foreach ($merchants as $data) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make('password'),
                'role'     => 'seller',
            ]);

            Store::create([
                'user_id'   => $user->id,
                'name'      => $data['name'],
                'address'   => $data['address'],
                'latitude'  => -7.25 + rand(-10, 10) / 100,
                'longitude' => 112.75 + rand(-10, 10) / 100,
                'status'    => 'pending',
            ]);
        }

        // Buat 2 user buyer (consumer)
        User::create([
            'name'     => 'Budi Santoso',
            'email'    => 'budi@mail.com',
            'password' => Hash::make('password'),
            'role'     => 'buyer',
        ]);

        User::create([
            'name'     => 'Siti Rahayu',
            'email'    => 'siti@mail.com',
            'password' => Hash::make('password'),
            'role'     => 'buyer',
        ]);
    }
}