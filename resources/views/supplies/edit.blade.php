<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('تعديل التوريد') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('supplies.update', $supply->id) }}" method="POST" id="supplyForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="supplier_id" class="block text-sm font-medium text-gray-700">المورد</label>
                            <select name="supplier_id" id="supplier_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                <option value="">اختر المورد</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id', $supply->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="supply_date" class="block text-sm font-medium text-gray-700">تاريخ التوريد</label>
                            <input type="date" name="supply_date" id="supply_date" value="{{ old('supply_date', $supply->supply_date->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('supply_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="text-lg font-medium text-gray-900">المنتجات</h3>
                                <button type="button" id="add-product" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                                    إضافة منتج
                                </button>
                            </div>
                            <div id="productsContainer">
                                @foreach($supplyItems as $index => $item)
                                    <div class="product-row border rounded-lg p-4 mb-4">
                                        <div class="flex justify-between items-center mb-2">
                                            <h4 class="text-md font-medium text-gray-700">منتج {{ $index + 1 }}</h4>
                                            <button type="button" onclick="removeProductRow(this)" class="text-red-600 hover:text-red-800">
                                                حذف
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">المنتج</label>
                                                <select name="products[{{ $index }}][product_id]" class="product-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                                    <option value="">اختر المنتج</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}" data-available="{{ $product->supply_quantity }}" {{ old("products.{$index}.product_id", $item->product_id) == $product->id ? 'selected' : '' }}>
                                                            {{ $product->name }} (الكمية المتوفرة: {{ number_format($product->supply_quantity, 2) }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">الكمية</label>
                                                <input type="number" step="0.01" name="products[{{ $index }}][quantity]" value="{{ old("products.{$index}.quantity", $item->quantity) }}" class="quantity-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required min="0.01">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('supplies.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 ml-2">إلغاء</a>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">حفظ التغييرات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        let productCount = {{ count($supplyItems) }};

        $(document).ready(function() {
            // تهيئة Select2 للزبون
            $('#supplier_id').select2({
                placeholder: 'ابحث عن مورد...',
                allowClear: true,
                dir: 'rtl'
            });

            // تهيئة Select2 للمنتجات الموجودة
            initializeSelect2();

            // إضافة منتج جديد
            $('#add-product').click(function() {
                const row = `
                    <div class="product-row border rounded-lg p-4 mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="text-md font-medium text-gray-700">منتج ${productCount + 1}</h4>
                            <button type="button" onclick="removeProductRow(this)" class="text-red-600 hover:text-red-800">
                                حذف
                            </button>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">المنتج</label>
                                <select name="products[${productCount}][product_id]" class="product-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="">اختر المنتج</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-available="{{ $product->supply_quantity }}">
                                            {{ $product->name }} (الكمية المتوفرة: {{ number_format($product->supply_quantity, 2) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">الكمية</label>
                                <input type="number" step="0.01" name="products[${productCount}][quantity]" class="quantity-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required min="0.01">
                            </div>
                        </div>
                    </div>
                `;
                $('#productsContainer').append(row);
                initializeSelect2();
                productCount++;
            });
        });

        function removeProductRow(button) {
            $(button).closest('.product-row').remove();
        }

        function initializeSelect2() {
            $('.product-select').select2({
                placeholder: 'ابحث عن منتج...',
                allowClear: true,
                dir: 'rtl'
            });
        }
    </script>
    @endpush

    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            height: 38px;
            padding: 5px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
    </style>
    @endpush
</x-app-layout> 