<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('إضافة صرف جديد') }}
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

                    <form action="{{ route('disbursements.store') }}" method="POST" id="disbursementForm">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="customer_id" class="block text-sm font-medium text-gray-700">الزبون</label>
                            <select name="customer_id" id="customer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                <option value="">اختر الزبون</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="disbursement_date" class="block text-sm font-medium text-gray-700">تاريخ الصرف</label>
                            <input type="date" name="disbursement_date" id="disbursement_date" value="{{ old('disbursement_date', date('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('disbursement_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="mb-2">
                                <h3 class="text-lg font-medium text-gray-900">المنتجات</h3>
                            </div>
                            
                            <div id="productsContainer">
                                <!-- سيتم إضافة المنتجات هنا ديناميكياً -->
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('disbursements.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 ml-2">إلغاء</a>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">حفظ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- زر إضافة منتج عائم -->
    <button type="button" onclick="addProductRow()" class="floating-add-button bg-green-500 text-white hover:bg-green-600">
        + إضافة منتج
    </button>

    @push('styles')
    <link href="{{ asset('js/select2/select2.min.css') }}" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            height: 38px;
            padding: 5px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
        .floating-add-button {
            position: fixed;
            top: 150px;
            right: 300px;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 50px;
            padding: 12px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .floating-add-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }
    </style>
    @endpush

    @push('scripts')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script>
        let productCount = 0;

        function addProductRow() {
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
            document.getElementById('productsContainer').insertAdjacentHTML('beforeend', row);
            initializeSelect2();
            productCount++;
            updateProductOptions();
        }

        function removeProductRow(button) {
            button.closest('.product-row').remove();
            updateProductOptions();
        }

        function updateProductOptions() {
            let selects = $('.product-select');
            let selectedValues = [];
            selects.each(function() {
                let val = $(this).val();
                if (val) selectedValues.push(val);
            });
            selects.each(function() {
                let currentSelect = $(this);
                let currentValue = currentSelect.val();
                currentSelect.find('option').each(function() {
                    let optionVal = $(this).attr('value');
                    if (!optionVal) return;
                    if (selectedValues.includes(optionVal) && optionVal !== currentValue) {
                        $(this).attr('disabled', 'disabled');
                    } else {
                        $(this).removeAttr('disabled');
                    }
                });
                // التحقق من وجود Select2 قبل استخدام trigger
                if (typeof $.fn.select2 !== 'undefined') {
                    currentSelect.trigger('change.select2');
                }
            });
        }

        $(document).on('change', '.product-select', function() {
            updateProductOptions();
        });

        function initializeSelect2() {
            // التحقق من وجود Select2 قبل استخدامه
            if (typeof $.fn.select2 !== 'undefined') {
                $('.product-select').select2({
                    placeholder: 'ابحث عن منتج...',
                    allowClear: true,
                    dir: 'rtl'
                });
            } else {
                console.log('Select2 غير متوفر، سيتم استخدام قوائم منسدلة عادية');
            }
        }

        // إضافة أول صف منتج عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            addProductRow();
            
            // التحقق من وجود Select2 قبل استخدامه للزبائن
            if (typeof $.fn.select2 !== 'undefined') {
                $('#customer_id').select2({
                    placeholder: 'ابحث عن زبون...',
                    allowClear: true,
                    dir: 'rtl'
                });
            }
            
            updateProductOptions();
        });
    </script>
    @endpush
</x-app-layout> 