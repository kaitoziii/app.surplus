<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SocialiteController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::where('email', $googleUser->email)->first();

if (!$user) {
    $user = User::create([
        'name' => $googleUser->name,
        'email' => $googleUser->email,
        'password' => bcrypt('12345678'),
        'role' => 'buyer', // default dulu
    ]);

    Auth::login($user);

    return redirect('/choose-role'); // tetap ke sini
}

        // USER LAMA
        Auth::login($user);

        return redirect('/dashboard');
    }
}