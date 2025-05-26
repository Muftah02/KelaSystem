<x-guest-layout dir="rtl">
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- الاسم -->
        <div>
            <x-input-label for="name" class="text-right" :value="__('الاسم')" />
            <x-text-input id="name" class="block mt-1 w-full text-right" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-right" />
        </div>

        <!-- البريد الإلكتروني -->
        <div class="mt-4">
            <x-input-label for="email" class="text-right" :value="__('البريد الإلكتروني')" />
            <x-text-input id="email" class="block mt-1 w-full text-right" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-right" />
        </div>

        <!-- كلمة المرور -->
        <div class="mt-4">
            <x-input-label for="password" class="text-right" :value="__('كلمة المرور')" />
            <x-text-input id="password" class="block mt-1 w-full text-right"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-right" />
        </div>

        <!-- تأكيد كلمة المرور -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" class="text-right" :value="__('تأكيد كلمة المرور')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full text-right"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-right" />
        </div>

        <!-- الدور -->
        <div class="mt-4">
            <x-input-label for="role" class="text-right" :value="__('الدور')" />
            <select id="role" name="role" class="block mt-1 w-full text-right rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900" required>
                <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>مدير</option>
                <option value="accountant" {{ old('role') == 'accountant' ? 'selected' : '' }}>محاسب</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2 text-right" />
        </div>

        <div class="flex items-center justify-start mt-4">
            <x-primary-button class="me-4">
                {{ __('تسجيل') }}
            </x-primary-button>

            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('لديك حساب بالفعل؟') }}
            </a>
        </div>
    </form>
</x-guest-layout>