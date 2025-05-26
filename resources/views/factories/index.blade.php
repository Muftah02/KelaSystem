<x-app-layout>
    <div class="max-w-6xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold dark:text-white">المصانع</h2>
            <a href="{{ route('factories.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white px-4 py-2 rounded transition-colors">
                <i class="fas fa-plus ml-2"></i> إضافة مصنع جديد
            </a>
        </div>

        @if ($factories->isEmpty())
            <div class="alert alert-warning dark:bg-yellow-900 dark:text-yellow-200">
                لا يوجد مصانع حالياً.
            </div>
        @else
            <div class="overflow-x-auto shadow-lg rounded-lg">
                <table class="min-w-full table-fixed">
                    <thead class="bg-gray-200 dark:bg-gray-700">
                        <tr>
                            <th class="w-32 px-4 py-2 text-right text-gray-700 dark:text-gray-200">اسم المصنع</th>
                            <th class="w-32 px-4 py-2 text-right text-gray-700 dark:text-gray-200">نوع الماكينة</th>
                            <th class="w-24 px-4 py-2 text-right text-gray-700 dark:text-gray-200">المساحة</th>
                            <th class="w-40 px-4 py-2 text-right text-gray-700 dark:text-gray-200">الكمية المحددة بالطن</th>
                            <th class="w-48 px-4 py-2 text-right text-gray-700 dark:text-gray-200">الموقع على الخريطة</th>
                            <th class="w-40 px-4 py-2 text-right text-gray-700 dark:text-gray-200">اسم العميل</th>
                            <th class="w-48 px-4 py-2 text-right text-gray-700 dark:text-gray-200">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800">
                        @foreach ($factories as $factory)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-2 dark:text-gray-200 w-32">{{ $factory->name }}</td>
                                <td class="px-4 py-2 dark:text-gray-200 w-32">{{ $factory->machine_type }}</td>
                                <td class="px-4 py-2 dark:text-gray-200 w-24">{{ $factory->factory_area }}</td>
                                <td class="px-4 py-2 dark:text-gray-200 w-40">{{ $factory->specified_ton_quantity }}</td>
                                <td class="px-4 py-2 dark:text-gray-200 w-48">{{ $factory->map_location }}</td>
                                <td class="px-4 py-2 dark:text-gray-200 w-40">{{ $factory->client->full_name ?? 'غير معروف' }}</td>
                                <td class="px-4 py-2 w-48">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('factories.edit', $factory->id) }}"
                                            class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300 flex items-center"
                                            title="تعديل">
                                            <i class="fas fa-edit mr-1"></i>
                                            <span class="hidden md:inline">تعديل</span>
                                        </a>
                                        
                                        <a href="{{ route('factories.show', $factory->id) }}"
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 flex items-center"
                                            title="عرض">
                                            <i class="fas fa-eye mr-1"></i>
                                            <span class="hidden md:inline">عرض</span>
                                        </a>
                                        
                                        <form action="{{ route('factories.destroy', $factory->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 flex items-center"
                                                title="حذف">
                                                <i class="fas fa-trash mr-1"></i>
                                                <span class="hidden md:inline">حذف</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4 dark:text-gray-200">
                    {{ $factories->links() }}
                </div>
            </div>
        @endif
    </div>
</x-app-layout>