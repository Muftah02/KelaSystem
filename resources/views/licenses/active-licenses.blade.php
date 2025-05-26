<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">عملاء لديهم تراخيص سارية</h1>
            <a href="{{ route('licenses.clients-status') }}" 
               class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-gray-100 transition-colors">
                <i class="fas fa-arrow-left ml-2"></i> العودة للتصنيف
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
            <div class="space-y-4">
                @forelse($clientsWithActiveLicenses as $client)
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">{{ $client->full_name }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    رقم العضوية: {{ $client->membership_number }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-green-600 dark:text-green-400">
                                    تاريخ انتهاء الترخيص: {{ $client->active_license->end_date->format('Y-m-d') }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    المدة المتبقية: {{ $client->active_license->end_date->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">لا يوجد عملاء لديهم تراخيص سارية</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout> 