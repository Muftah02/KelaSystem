{{-- resources/views/clients/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white bg-gray-800 dark:bg-gray-900 py-4 px-6 rounded-t-lg">
            تفاصيل العميل: {{ $client->full_name }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- بطاقة بيانات العميل -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200">
                    <i class="fas fa-user-circle mr-2 text-blue-500"></i>
                    المعلومات الأساسية
                </h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-600 dark:text-gray-300">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">رقم العضوية</label>
                    <p class="mt-1 text-lg dark:text-gray-200">{{ $client->membership_number }}</p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">الاسم الكامل</label>
                    <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-white">{{ $client->full_name }}</p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">الرقم الوطني</label>
                    <p class="mt-1 text-lg dark:text-gray-200">{{ $client->national_id }}</p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">رقم الهاتف</label>
                    <p class="mt-1 text-lg dark:text-gray-200">{{ $client->phone }}</p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">المدينة</label>
                    <p class="mt-1 text-lg dark:text-gray-200">{{ $client->city }}</p>
                </div>
                <div class="md:col-span-2 mb-4">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">العنوان التفصيلي</label>
                    <p class="mt-1 text-lg dark:text-gray-200">{{ $client->address }}</p>
                </div>
            </div>
        </div>

        <!-- بطاقة المصانع التابعة -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200">
                    <i class="fas fa-industry mr-2 text-green-500"></i>
                    المصانع التابعة ({{ $factories->count() }})
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">اسم المصنع</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">نوع الآلة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">المساحة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">السعة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">نوع الحجز</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($factories as $factory)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200">{{ $factory->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $factory->machine_type }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ number_format($factory->factory_area) }} م²</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ number_format($factory->specified_ton_quantity) }} طن</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-xs">
                                    {{ $factory->reservation_type }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- بطاقة بيانات الموكل -->
        @if($client->proxy)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200">
                    <i class="fas fa-user-tie mr-2 text-purple-500"></i>
                    بيانات الموكل
                </h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-600 dark:text-gray-300">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">الاسم الكامل</label>
                    <p class="mt-1 text-lg dark:text-gray-200">{{ $client->proxy->full_name }}</p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">الرقم الوطني</label>
                    <p class="mt-1 text-lg dark:text-gray-200">{{ $client->proxy->national_id }}</p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">رقم الهاتف</label>
                    <p class="mt-1 text-lg dark:text-gray-200">{{ $client->proxy->phone }}</p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">المدينة</label>
                    <p class="mt-1 text-lg dark:text-gray-200">{{ $client->proxy->city }}</p>
                </div>
                <div class="md:col-span-2 mb-4">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">العنوان</label>
                    <p class="mt-1 text-lg dark:text-gray-200">{{ $client->proxy->address }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- أزرار التحكم -->
        <div class="mt-8 flex justify-end space-x-3">
            <a href="{{ route('clients.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600 text-white px-6 py-2 rounded-lg flex items-center">
               <i class="fas fa-arrow-right ml-2"></i> رجوع للقائمة
            </a>
            <a href="{{ route('clients.edit', $client) }}" 
               class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white px-6 py-2 rounded-lg flex items-center">
               <i class="fas fa-edit ml-2"></i> تعديل البيانات
            </a>
        </div>
    </div>
</x-app-layout>