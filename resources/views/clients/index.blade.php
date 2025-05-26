<x-app-layout>
    <div class="container mx-auto py-6">
        <h2 class="text-2xl font-semibold mb-4 dark:text-white">العملاء</h2>

        <a href="{{ route('clients.create') }}" class="mb-4 inline-flex items-center p-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800">
            <i class="fas fa-user-plus mr-2"></i> إضافة عميل جديد
        </a>

        @if ($clients->isEmpty())
            <div class="alert alert-warning dark:bg-yellow-900 dark:text-yellow-200">
                لا يوجد عملاء حالياً.
            </div>
        @else
            <div class="overflow-x-auto shadow-lg rounded-lg dark:bg-gray-800">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-200 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-right dark:text-gray-200">الاسم الكامل</th>
                            <th class="px-4 py-2 text-right dark:text-gray-200">الرقم القومي</th>
                            <th class="px-4 py-2 text-right dark:text-gray-200">الهاتف</th>
                            <th class="px-4 py-2 text-right dark:text-gray-200">المدينة</th>
                            <th class="px-4 py-2 text-right dark:text-gray-200">العنوان</th>
                            <th class="px-4 py-2 text-right dark:text-gray-200">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800">
                        @foreach ($clients as $client)
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-4 py-2 dark:text-gray-200">{{ $client->full_name }}</td>
                            <td class="px-4 py-2 dark:text-gray-200">{{ $client->national_id }}</td>
                            @php
    $phone = ltrim($client->phone); // إزالة الفراغات
    if (str_starts_with($phone, '0')) {
        $waNumber = '218' . substr($phone, 1);
    } elseif (str_starts_with($phone, '218')) {
        $waNumber = $phone;
    } else {
        $waNumber = '218' . $phone; // احتياطي
    }
@endphp
<td class="px-4 py-2 dark:text-gray-200">
    <div class="flex items-center justify-end gap-2">
        {{ $client->phone }}
        <a 
            href="https://wa.me/{{ $waNumber }}" 
            target="_blank"
            class="p-1 bg-green-600 text-white rounded-full hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800"
            title="فتح محادثة واتساب"
        >
            <i class="fab fa-whatsapp w-4 h-4"></i>
        </a>
    </div>
</td>

                            <td class="px-4 py-2 dark:text-gray-200">{{ $client->city }}</td>
                            <td class="px-4 py-2 dark:text-gray-200">{{ $client->address }}</td>
                            <td class="px-4 py-2">
                                <a href="{{ route('clients.show', $client->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    <i class="fas fa-eye"></i> عرض
                                </a>
                                <a href="{{ route('clients.edit', $client->id) }}" class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300 mx-2">
                                    <i class="fas fa-edit"></i> تعديل
                                </a>
                                <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                        <i class="fas fa-trash"></i> حذف
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
