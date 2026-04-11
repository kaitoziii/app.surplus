<x-guest-layout>
<body class="bg-[#202808] min-h-screen flex items-center justify-center">

<div class="bg-[#fff7ea] p-8 rounded-2xl shadow-xl w-full max-w-md">

    <!-- BRAND -->
    <h1 class="text-2xl font-bold text-center text-[#202808] mb-1">
        Surplus
    </h1>
    <p class="text-center text-sm text-[#6A784D] mb-6">
        Kurangi limbah, maksimalkan nilai 🌱
    </p>

    <!-- STATUS -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- EMAIL -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input 
                id="email"
                class="block mt-1 w-full rounded-lg border-gray-300 focus:ring-[#33432B] focus:border-[#33432B]"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- PASSWORD -->
        <div class="mt-4 relative">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input 
                id="password"
                class="block mt-1 w-full pr-10 rounded-lg border-gray-300 focus:ring-[#33432B] focus:border-[#33432B]"
                type="password"
                name="password"
                required
            />

            <!-- ICON -->
            <span onclick="togglePassword('password')" 
                  class="absolute right-3 top-10 cursor-pointer text-[#6A784D]">
                👁️
            </span>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- REMEMBER -->
        <div class="block mt-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="remember"
                    class="rounded border-gray-300 text-[#33432B] focus:ring-[#33432B]">
                <span class="ml-2 text-sm text-[#6A784D]">Remember me</span>
            </label>
        </div>

        <!-- ACTION -->
        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('password.request') }}" 
               class="text-sm text-[#33432B] hover:text-[#202808]">
                Forgot password?
            </a>

            <button class="bg-[#33432B] text-white px-5 py-2 rounded-lg hover:bg-[#202808] transition">
                Log in
            </button>
        </div>
    </form>

    <!-- GOOGLE LOGIN -->
    <a href="/auth/google" 
       class="block text-center mt-4 p-3 bg-white border border-[#6A784D] rounded-lg hover:bg-[#6A784D] hover:text-white transition">
        Login dengan Google
    </a>

</div>

<!-- JS -->
<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === "password" ? "text" : "password";
}
</script>

</body>
</x-guest-layout>