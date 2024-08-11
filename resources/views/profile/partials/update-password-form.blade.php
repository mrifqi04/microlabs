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

        <div class="mb-3">
            <label for="update_password_current_password">Current Password</label>
            <input class="form-control" type="password" name="current_password" id="update_password_current_password" autocomplete="current-password">
        </div>

        <div class="mb-3">
            <label for="update_password_password">New Password</label>
            <input class="form-control" type="password" name="password" id="update_password_password" autocomplete="current-password">
        </div>

        <div>
            <label for="update_password_password_confirmation">Confirm Password</label>
            <input class="form-control" type="password" name="password_confirmation" id="update_password_password_confirmation" autocomplete="new-password">
        </div>

        <div class="flex items-center gap-4 mt-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
        </div>
    </form>
</section>
