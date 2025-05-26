<x-guest-layout dir="rtl">
    <!-- حالة الجلسة -->
    <x-auth-session-status class="mb-4 text-right" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- البريد الإلكتروني -->
        <div>
            <x-input-label for="email" class="text-right" :value="__('البريد الإلكتروني')" />
            <x-text-input id="email" class="block mt-1 w-full text-right" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-right" />
        </div>

        <!-- كلمة المرور -->
        <div class="mt-4">
            <x-input-label for="password" class="text-right" :value="__('كلمة المرور')" />

            <x-text-input id="password" class="block mt-1 w-full text-right"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-right" />
        </div>

        <!-- تذكرني -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center flex-row-reverse">
                <span class="me-2 text-sm text-gray-600 dark:text-gray-400">{{ __('تذكرني') }}</span>
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
            </label>
        </div>

        <div class="flex items-center justify-start mt-4">
            <x-primary-button class="me-3">
                {{ __('تسجيل الدخول') }}
            </x-primary-button>

            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('هل نسيت كلمة المرور؟') }}
                </a>
            @endif
        </div>
    </form>
</x-guest-layout>