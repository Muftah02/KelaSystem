<section class="space-y-6" dir="rtl">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 text-right">
            حذف الحساب
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 text-right">
            عند حذف حسابك، سيتم حذف جميع البيانات نهائيًا. يرجى تنزيل أي معلومات مهمة قبل المتابعة.
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="float-right"
    >حذف الحساب</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 text-right">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                هل أنت متأكد أنك تريد حذف حسابك؟
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                بعد الحذف، سيتم فقدان جميع البيانات نهائيًا. أدخل كلمة المرور لتأكيد الحذف.
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="كلمة المرور" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4 text-right"
                    placeholder="كلمة المرور"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-right" />
            </div>

            <div class="mt-6 flex justify-start">
                <x-secondary-button x-on:click="$dispatch('close')">
                    إلغاء
                </x-secondary-button>

                <x-danger-button class="me-3">
                    حذف الحساب
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
