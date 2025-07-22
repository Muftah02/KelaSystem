<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-900 dark:text-white leading-tight">
                {{ __('التوريدات') }}
            </h2>
            <a href="{{ route('supplies.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                إضافة توريد جديد
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="mb-4">
                        <input type="text" id="searchInput" placeholder="بحث..." class="rounded-lg border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 w-full">
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        #
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        المورد
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        تاريخ التوريد
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        المنتجات
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        الكمية الإجمالية
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        الإجراءات
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($supplies as $supply)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            {{ $supply->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            {{ $supply->supplier->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            {{ $supply->supply_date->format('Y-m-d') }}
                                            <div class="text-sm text-gray-500">
                                                {{ \App\Helpers\DateHelper::toHijri($supply->supply_date) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="relative">
                                                <button onclick="toggleProducts({{ $supply->id }})" class="inline-flex items-center px-3 py-1 text-sm text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 rounded-md transition-colors duration-200">
                                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                    عرض المنتجات ({{ $supply->items->count() }})
                                                </button>
                                                <div id="products-{{ $supply->id }}" class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 hidden z-10">
                                                    <div class="p-3">
                                                        <div class="border-b border-gray-200 pb-2 mb-2">
                                                            <h4 class="text-sm font-semibold text-gray-700">تفاصيل المنتجات</h4>
                                                        </div>
                                                        <div class="space-y-2 max-h-48 overflow-y-auto">
                                                            @foreach($supply->items as $item)
                                                                <div class="flex justify-between items-center text-sm p-1 hover:bg-gray-50 rounded">
                                                                    <span class="text-gray-600">{{ $item->product->name }}</span>
                                                                    <span class="text-gray-900 font-medium">{{ number_format($item->quantity, 2) }}</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            {{ number_format($supply->items->sum('quantity'), 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('supplies.edit', $supply) }}" class="text-blue-600 hover:text-blue-900 ml-3">تعديل</a>
                                            <a href="{{ route('supplies.print', $supply) }}" class="text-green-600 hover:text-green-900 ml-3" target="_blank">طباعة</a>
                                            <form action="{{ route('supplies.destroy', $supply) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('هل أنت متأكد من حذف هذا التوريد؟')">حذف</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleProducts(id) {
            const dropdown = document.getElementById('products-' + id);
            dropdown.classList.toggle('hidden');
        }

        // إغلاق القائمة عند النقر خارجها
        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('[id^="products-"]');
            dropdowns.forEach(dropdown => {
                if (!dropdown.contains(event.target) && !event.target.matches('button')) {
                    dropdown.classList.add('hidden');
                }
            });
        });

        // البحث
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchText = this.value.toLowerCase();
            const table = document.querySelector('table');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let found = false;

                for (let j = 0; j < cells.length; j++) {
                    const cell = cells[j];
                    if (cell.textContent.toLowerCase().indexOf(searchText) > -1) {
                        found = true;
                        break;
                    }
                }

                row.style.display = found ? '' : 'none';
            }
        });
    </script>
    @endpush
</x-app-layout> 