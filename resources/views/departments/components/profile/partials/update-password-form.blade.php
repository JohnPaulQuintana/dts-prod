<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

       <div class="row">
        <div class="mb-2 col-sm-6">
            <label for="current_password" class="form-label">{{ __('Current Password') }}</label>
            <input id="current_password" name="current_password" type="password" class="form-control border rounded" autocomplete="current-password">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-danger" />
        </div>

        <div class="mb-2 col-sm-6">
            <label for="password" class="form-label">{{ __('New Password') }}</label>
            <input id="password" name="password" type="password" class="form-control border rounded" autocomplete="new-password">
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-danger" />
        </div>
       </div>

        <div class="mb-2">
            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control border rounded" autocomplete="new-password">
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="d-flex align-items-center gap-4">
            <button type="submit" class="btn btn-dark">{{ __('Update Password') }}</button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Updated Password.') }}</p>
            @endif
        </div>
    </form>
</section>
