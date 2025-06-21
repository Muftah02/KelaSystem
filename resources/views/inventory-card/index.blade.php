<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('بطاقة جرد المنتجات') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <!-- نموذج الفلترة -->
            <form method="GET" action="{{ route('inventory-card.index') }}" class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- فلتر الكمية -->
                    <div>
                        <label for="quantity_filter" class="block text-sm font-medium text-gray-700 mb-1">الكمية</label>
                        <select name="quantity_filter" id="quantity_filter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">الكل</option>
                            <option value="positive" {{ request('quantity_filter') === 'positive' ? 'selected' : '' }}>أكبر من صفر</option>
                            <option value="zero" {{ request('quantity_filter') === 'zero' ? 'selected' : '' }}>أصغر من أو يساوي صفر</option>
                        </select>
                    </div>

                    <!-- فلتر التصنيف -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">التصنيف</label>
                        <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">الكل</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- فلتر الشركة -->
                    <div>
                        <label for="company_id" class="block text-sm font-medium text-gray-700 mb-1">الشركة</label>
                        <select name="company_id" id="company_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">الكل</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-end mt-4">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        تطبيق الفلتر
                    </button>
                    <a href="{{ route('inventory-card.print', request()->query()) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg mr-2" target="_blank">
                        طباعة
                    </a>
                </div>
            </form>

            <!-- جدول المنتجات -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-6 py-3 border-b text-right text-sm font-semibold text-gray-600">اسم المنتج</th>
                            <th class="px-6 py-3 border-b text-right text-sm font-semibold text-gray-600">الكمية الحالية</th>
                            <th class="px-6 py-3 border-b text-right text-sm font-semibold text-gray-600">التصنيف</th>
                            <th class="px-6 py-3 border-b text-right text-sm font-semibold text-gray-600">الشركة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 border-b text-sm text-gray-700">
                                    {{ $product->name }}
                                </td>
                                <td class="px-6 py-4 border-b text-sm text-gray-700">
                                    <span class="{{ $product->supply_quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($product->supply_quantity, 2) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 border-b text-sm text-gray-700">
                                    {{ $product->category->name }}
                                </td>
                                <td class="px-6 py-4 border-b text-sm text-gray-700">
                                    {{ $product->company->name }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    لا توجد منتجات تطابق معايير البحث
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- ملخص النتائج -->
            <div class="mt-4 text-sm text-gray-600">
                عدد المنتجات: {{ $products->count() }}
            </div>
        </div>
    </div>
</x-app-layout> 