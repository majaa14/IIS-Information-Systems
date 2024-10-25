<section>
        <div class="form">
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Upravit heslo') }}
        </h2>
        </div>

        <div class="form">
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Vaše heslo by mělo být dostatečně složité') }}
        </p>
        </div>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div class="form">
            <x-input-label for="current_password" :value="__('Staré heslo')" />
            <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="form">
            <x-input-label for="password" :value="__('Nové heslo')" />
            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div class="form">
            <x-input-label for="password_confirmation" :value="__('Potvrďte heslo')" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="form">
            <x-primary-button class="button">{{ __('Uložit') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Uloženo.') }}</p>
            @endif
        </div>
    </form>
</section>
