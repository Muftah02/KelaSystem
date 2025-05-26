{{-- resources/views/clients/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight dark:bg-gray-900">
            تفاصيل المصنع
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow mt-6">
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $factory->name }}</h3>
                <div class="flex space-x-4">
                    <a href="{{ route('factories.edit', $factory) }}" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        تعديل
                    </a>
                    <form action="{{ route('factories.destroy', $factory) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                            onclick="return confirm('هل أنت متأكد من حذف هذا المصنع؟')">
                            حذف
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- معلومات المصنع -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">معلومات المصنع</h4>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">نوع الآلة</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $factory->machine_type }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">نوع الحجز</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $factory->reservation_type }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">مساحة المصنع</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $factory->factory_area }} م²</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">كمية الطن المحددة</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $factory->specified_ton_quantity }}</dd>
                    </div>
                </dl>
            </div>

            <!-- معلومات العميل -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">معلومات العميل</h4>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">الاسم</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $factory->client->full_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">رقم العضوية</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $factory->client->membership_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">رقم الهاتف</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $factory->client->phone }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- الموقع الجغرافي -->
        <div class="mt-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
            <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">الموقع الجغرافي</h4>
            <div class="flex items-center space-x-4">
                <a href="{{ $factory->map_location }}" target="_blank" 
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    فتح في خرائط جوجل
                </a>
                <button onclick="copyMapLink()" 
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                    </svg>
                    نسخ رابط الموقع
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function copyMapLink() {
            const mapLink = "{{ $factory->map_location }}";
            navigator.clipboard.writeText(mapLink).then(() => {
                alert('تم نسخ رابط الموقع بنجاح');
            }).catch(err => {
                console.error('فشل نسخ الرابط:', err);
            });
        }
    </script>
    @endpush
</x-app-layout>
