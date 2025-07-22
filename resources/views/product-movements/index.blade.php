<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-white leading-tight">
            {{ __('حركة المنتج') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <form id="searchForm" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="relative">
                        <label for="productSearch" class="block text-sm font-medium text-gray-700 mb-1">المنتج</label>
                        <input
                            type="text"
                            id="productSearch"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="ابحث عن منتج..."
                            autocomplete="off"
                        >
                        <input type="hidden" id="productId" name="product_id">
                        <div id="searchResults" class="absolute z-10 mt-1 w-full bg-white rounded-md shadow-lg border hidden">
                            <!-- سيتم ملؤها بالـ JavaScript -->
                        </div>
                    </div>

                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">تاريخ البداية</label>
                        <input
                            type="date"
                            id="start_date"
                            name="start_date"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">تاريخ النهاية</label>
                        <input
                            type="date"
                            id="end_date"
                            name="end_date"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        بحث
                    </button>
                </div>
            </form>

            <div id="results" class="mt-8">
                <!-- سيتم ملؤها بالنتائج -->
            </div>
        </div>
    </div>

    <script>
        // تخزين المنتجات
        const products = @json($products);
        let selectedProduct = null;

        // عناصر DOM
        const productSearch = document.getElementById('productSearch');
        const productId = document.getElementById('productId');
        const searchResults = document.getElementById('searchResults');
        const searchForm = document.getElementById('searchForm');
        const resultsDiv = document.getElementById('results');

        // فلترة المنتجات
        function filterProducts(query) {
            if (!query) {
                searchResults.classList.add('hidden');
                return;
            }

            const filtered = products.filter(product => 
                product.name.toLowerCase().includes(query.toLowerCase())
            );

            if (filtered.length > 0) {
                searchResults.innerHTML = filtered.map(product => `
                    <div class="cursor-pointer px-4 py-2 hover:bg-blue-100" 
                         onclick="selectProduct(${product.id}, '${product.name}')">
                        ${product.name}
                    </div>
                `).join('');
                searchResults.classList.remove('hidden');
            } else {
                searchResults.classList.add('hidden');
            }
        }

        // اختيار منتج
        function selectProduct(id, name) {
            selectedProduct = products.find(p => p.id === id);
            productSearch.value = name;
            productId.value = id;
            searchResults.classList.add('hidden');
        }

        // إخفاء النتائج عند النقر خارجها
        document.addEventListener('click', (e) => {
            if (!productSearch.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.add('hidden');
            }
        });

        // البحث عن المنتجات عند الكتابة
        productSearch.addEventListener('input', (e) => {
            filterProducts(e.target.value);
        });

        // معالجة تقديم النموذج
        searchForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            if (!selectedProduct) {
                alert('الرجاء اختيار منتج');
                return;
            }

            const formData = new FormData(searchForm);
            
            try {
                resultsDiv.innerHTML = `
                    <div class="text-center py-4">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                        <p class="mt-2 text-gray-600">جاري تحميل البيانات...</p>
                    </div>
                `;

                const response = await fetch('{{ route("product-movements.search") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(Object.fromEntries(formData))
                });

                const data = await response.json();

                if (data.movements.length === 0) {
                    resultsDiv.innerHTML = `
                        <div class="text-center text-gray-500 py-4">
                            لا توجد حركات لهذا المنتج
                        </div>
                    `;
                    return;
                }

                resultsDiv.innerHTML = `
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">نتائج البحث</h3>
                        <button onclick="printMovements()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            طباعة
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-6 py-3 border-b text-right text-sm font-semibold text-gray-600">رقم العملية</th>
                                    <th class="px-6 py-3 border-b text-right text-sm font-semibold text-gray-600">التاريخ</th>
                                    <th class="px-6 py-3 border-b text-right text-sm font-semibold text-gray-600">نوع الحركة</th>
                                    <th class="px-6 py-3 border-b text-right text-sm font-semibold text-gray-600">الكمية</th>
                                    <th class="px-6 py-3 border-b text-right text-sm font-semibold text-gray-600">الكمية المتبقية</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.movements.map(movement => `
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 border-b text-sm text-gray-700">
                                            <a href="/${movement.reference_type}s/${movement.reference_id}" 
                                               class="text-blue-600 hover:text-blue-800">
                                                ${movement.reference_type === 'supply' ? 'توريد' : 'صرف'} #${movement.reference_id}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 border-b text-sm text-gray-700">${movement.date}</td>
                                        <td class="px-6 py-4 border-b text-sm">
                                            <span class="px-2 py-1 rounded-full text-sm ${movement.type === 'توريد' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                                ${movement.type}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 border-b text-sm text-gray-700 ${movement.quantity > 0 ? 'text-green-600' : 'text-red-600'}">
                                            ${movement.quantity}
                                        </td>
                                        <td class="px-6 py-4 border-b text-sm text-gray-700">
                                            ${movement.remaining_quantity}
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            } catch (error) {
                console.error('Error:', error);
                resultsDiv.innerHTML = `
                    <div class="text-center text-red-500 py-4">
                        حدث خطأ أثناء تحميل البيانات
                    </div>
                `;
            }
        });

        // دالة الطباعة
        function printMovements() {
            if (!selectedProduct) return;
            
            const printWindow = window.open('', '_blank');
            const currentDate = new Date().toLocaleDateString('ar-SA');
            const currentTime = new Date().toLocaleTimeString('ar-SA');
            
            printWindow.document.write(`
                <!DOCTYPE html>
                <html dir="rtl">
                <head>
                    <title>حركة صنف - ${selectedProduct.name}</title>
                    <meta charset="UTF-8">
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            margin: 20px;
                            direction: rtl;
                        }
                        .header {
                            text-align: center;
                            margin-bottom: 20px;
                        }
                        .info {
                            margin-bottom: 20px;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-top: 20px;
                        }
                        th, td {
                            border: 1px solid #ddd;
                            padding: 8px;
                            text-align: right;
                        }
                        th {
                            background-color: #f5f5f5;
                        }
                        .supply {
                            color: green;
                        }
                        .disbursement {
                            color: red;
                        }
                        @media print {
                            .no-print {
                                display: none;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>حركة صنف: ${selectedProduct.name}</h1>
                        <div class="info">
                            <p>تاريخ الطباعة: ${currentDate}</p>
                            <p>وقت الطباعة: ${currentTime}</p>
                        </div>
                    </div>
                    <div class="no-print" style="text-align: center; margin: 20px;">
                        <button onclick="window.print()" style="padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">
                            طباعة
                        </button>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>رقم العملية</th>
                                <th>التاريخ</th>
                                <th>نوع الحركة</th>
                                <th>الكمية</th>
                                <th>الكمية المتبقية</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${document.querySelector('#results table tbody').innerHTML}
                        </tbody>
                    </table>
                </body>
                </html>
            `);
            printWindow.document.close();
        }
    </script>
</x-app-layout> 