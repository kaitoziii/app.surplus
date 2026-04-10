<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Change Password
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-xl p-4 sm:p-6">

                @include('profile.partials.update-password-form')

            </div>

        </div>
    </div>
</x-app-layout>