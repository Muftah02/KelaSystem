<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">تصنيف العملاء حسب التراخيص</h1>
            <a href="{{ route('licenses.index') }}" 
               class="flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                <svg class="w-5 h-5 ml-2 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span class="text-gray-600 dark:text-gray-300">العودة للقائمة</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- بطاقة التراخيص السارية -->
            <a href="{{ route('licenses.active') }}" class="group flex flex-row-reverse items-center justify-between rounded-xl border dark:border-gray-700 bg-white dark:bg-gray-800 p-6 transition-all hover:scale-[1.02] hover:shadow-lg shadow-sm dark:shadow-gray-900/30">
                <div class="space-y-4 text-right">
                    <h3 class="text-lg font-semibold text-green-600 dark:text-green-400">
                        تراخيص سارية
                    </h3>
                    <p class="text-3xl font-bold text-green-700 dark:text-green-300">
                        {{ $clientsWithActiveLicenses->count() }}
                    </p>
                    <div class="flex items-center justify-end space-x-2 text-green-600 dark:text-green-400">
                        <span class="text-sm">عرض التفاصيل</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 p-3 bg-green-100 dark:bg-green-900/20 rounded-lg">
                    <svg class="w-12 h-12 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </a>

            <!-- بطاقة التراخيص المنتهية -->
            <a href="{{ route('licenses.expired') }}" class="group flex flex-row-reverse items-center justify-between rounded-xl border dark:border-gray-700 bg-white dark:bg-gray-800 p-6 transition-all hover:scale-[1.02] hover:shadow-lg shadow-sm dark:shadow-gray-900/30">
                <div class="space-y-4 text-right">
                    <h3 class="text-lg font-semibold text-red-600 dark:text-red-400">
                        تراخيص منتهية
                    </h3>
                    <p class="text-3xl font-bold text-red-700 dark:text-red-300">
                        {{ $clientsWithExpiredLicenses->count() }}
                    </p>
                    <div class="flex items-center justify-end space-x-2 text-red-600 dark:text-red-400">
                        <span class="text-sm">عرض التفاصيل</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 p-3 bg-red-100 dark:bg-red-900/20 rounded-lg">
                    <svg class="w-12 h-12 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </a>

            <!-- بطاقة التراخيص المنتهية قريبًا -->
            <a href="{{ route('licenses.expiring') }}" class="group flex flex-row-reverse items-center justify-between rounded-xl border dark:border-gray-700 bg-white dark:bg-gray-800 p-6 transition-all hover:scale-[1.02] hover:shadow-lg shadow-sm dark:shadow-gray-900/30">
                <div class="space-y-4 text-right">
                    <h3 class="text-lg font-semibold text-yellow-600 dark:text-yellow-400">
                        تنتهي قريبًا
                    </h3>
                    <p class="text-3xl font-bold text-yellow-700 dark:text-yellow-300">
                        {{ $clientsWithExpiringLicenses->count() }}
                    </p>
                    <div class="flex items-center justify-end space-x-2 text-yellow-600 dark:text-yellow-400">
                        <span class="text-sm">عرض التفاصيل</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 p-3 bg-yellow-100 dark:bg-yellow-900/20 rounded-lg">
                    <svg class="w-12 h-12 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </a>

            <!-- بطاقة بدون تراخيص -->
            <a href="{{ route('licenses.no-licenses') }}" class="group flex flex-row-reverse items-center justify-between rounded-xl border dark:border-gray-700 bg-white dark:bg-gray-800 p-6 transition-all hover:scale-[1.02] hover:shadow-lg shadow-sm dark:shadow-gray-900/30">
                <div class="space-y-4 text-right">
                    <h3 class="text-lg font-semibold text-blue-600 dark:text-blue-400">
                        بدون تراخيص
                    </h3>
                    <p class="text-3xl font-bold text-blue-700 dark:text-blue-300">
                        {{ $clientsWithoutLicenses->count() }}
                    </p>
                    <div class="flex items-center justify-end space-x-2 text-blue-600 dark:text-blue-400">
                        <span class="text-sm">عرض التفاصيل</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 p-3 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                    <svg class="w-12 h-12 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>