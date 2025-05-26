{{-- resources/views/dashboard/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- البطاقات الإحصائية -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- عدد العملاء -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                                <i class="fas fa-users text-blue-600 dark:text-blue-400 text-xl"></i>
                            </div>
                            <div class="mr-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">العملاء</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $clientsCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- عدد المصانع -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                                <i class="fas fa-industry text-purple-600 dark:text-purple-400 text-xl"></i>
                            </div>
                            <div class="mr-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">المصانع</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $factoriesCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- التراخيص السارية -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                                <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                            </div>
                            <div class="mr-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">التراخيص السارية</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $activeLicensesCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- التراخيص المنتهية -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-red-100 dark:bg-red-900">
                                <i class="fas fa-times-circle text-red-600 dark:text-red-400 text-xl"></i>
                            </div>
                            <div class="mr-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">التراخيص المنتهية</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $expiredLicensesCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- قسم التنبيهات -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                            <i class="fas fa-bell ml-2 text-blue-500 dark:text-blue-400"></i>
                            التنبيهات
                        </h3>
                        
                        <!-- التراخيص القريبة الانتهاء -->
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">التراخيص القريبة الانتهاء</h4>
                            @if($expiringLicenses->count() > 0)
                                <div class="space-y-3">
                                    @foreach($expiringLicenses as $license)
                                        <div class="flex items-center justify-between p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $license->client->full_name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">ينتهي في: {{ $license->expiry_date ? $license->expiry_date->format('Y-m-d') : 'غير محدد' }}</p>
                                            </div>
                                            <a href="{{ route('licenses.show', $license) }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">عرض</a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400">لا توجد تراخيص قريبة الانتهاء</p>
                            @endif
                        </div>

                        <!-- العملاء بدون ترخيص -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">العملاء بدون ترخيص</h4>
                            @if($clientsWithoutLicense->count() > 0)
                                <div class="space-y-3">
                                    @foreach($clientsWithoutLicense as $client)
                                        <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $client->full_name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">رقم العضوية: {{ $client->membership_number }}</p>
                                            </div>
                                            <a href="{{ route('clients.show', $client) }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">عرض</a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400">جميع العملاء لديهم تراخيص سارية</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- قسم الوصول السريع -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                            <i class="fas fa-bolt ml-2 text-purple-500 dark:text-purple-400"></i>
                            وصول سريع
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <a href="{{ route('clients.index') }}" class="flex items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                                    <i class="fas fa-users text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div class="mr-4">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">العملاء</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">إدارة العملاء</p>
                                </div>
                            </a>
                            <a href="{{ route('factories.index') }}" class="flex items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                                    <i class="fas fa-industry text-purple-600 dark:text-purple-400"></i>
                                </div>
                                <div class="mr-4">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">المصانع</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">إدارة المصانع</p>
                                </div>
                            </a>
                            <a href="{{ route('licenses.index') }}" class="flex items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                                    <i class="fas fa-file-alt text-green-600 dark:text-green-400"></i>
                                </div>
                                <div class="mr-4">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">التراخيص</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">إدارة التراخيص</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
