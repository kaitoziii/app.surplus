<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <!-- GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- CURRENT PASSWORD -->
            <div>
                <x-input-label for="update_password_current_password">
                    Current Password <span class="text-red-500">*</span>
                </x-input-label>

                <x-text-input id="update_password_current_password"
                    name="current_password"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="current-password"
                    required />

                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            <!-- NEW PASSWORD -->
            <div>
                <x-input-label for="update_password_password">
                    New Password <span class="text-red-500">*</span>
                </x-input-label>

                <x-text-input id="update_password_password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                    required />

                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

        </div>

        <!-- CONFIRM PASSWORD (FULL WIDTH) -->
        <div>
            <x-input-label for="update_password_password_confirmation">
                Confirm Password <span class="text-red-500">*</span>
            </x-input-label>

            <x-text-input id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="mt-1 block w-full"
                autocomplete="new-password"
                required />

            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- BUTTON -->
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>

    </form>
</section>