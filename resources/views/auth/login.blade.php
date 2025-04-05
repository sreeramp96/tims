<div class="w-full overflow-hidden bg-gray-500 py-4 text-center">
    <h1 class="text-3xl font-bold text-white m-0 p-0 leading-tight">Time Issue Management System</h1>
</div>

<x-guest-layout>
    <div class="max-w-screen-md mx-auto px-4 overflow-hidden">
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div>
                <x-text-input label="Email" id="email" class="block mt-1 w-full" type="email" name="email"
                    :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-text-input label="Password" id="password" class="block mt-1 w-full" type="password" name="password"
                    required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                        class="rounded border-gray-300 text-amber-600 shadow-sm focus:ring-amber-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        href="{{ route('password.request') }}">
                        Forgot your password?
                    </a>
                @endif

                <x-primary-button class="ms-3">
                    {{ 'Log in ' }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
