<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-4xl sm:max-w-5xl lg:max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-xl p-4 sm:p-6">

                <!-- ROLE -->
                <div>
                    @if(auth()->user()->role == 'seller')
                        @include('profile.seller')
                    @else
                        @include('profile.buyer')
                    @endif
                </div>

                <hr class="my-8">

                <!-- PASSWORD -->
                <div>
                    @include('profile.partials.update-password-form')
                </div>

                <hr class="my-8">

                <!-- DELETE -->
                <div>
                    @include('profile.partials.delete-user-form')
                </div>

            </div>
        </div>
    </div>
</x-app-layout>