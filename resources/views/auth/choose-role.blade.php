<x-guest-layout>
    <div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow text-center">
        <h2 class="text-xl font-semibold mb-6">
            Pilih Peran Kamu
        </h2>

        <form method="POST" action="/save-role">
            @csrf

            <div class="space-y-4">
                <button type="submit" name="role" value="buyer"
                    class="w-full border p-3 rounded hover:bg-gray-100">
                    🛒 Saya Pembeli
                </button>

                <button type="submit" name="role" value="seller"
                    class="w-full border p-3 rounded hover:bg-gray-100">
                    🏪 Saya Penjual
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>