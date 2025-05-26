<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight dark:bg-gray-900">
            إضافة عميل جديد
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow mt-6">
        <form action="{{ route('clients.store') }}" method="POST">
            @csrf

            <!-- بيانات العميل الأساسية -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg mb-6">
                <h3 class="text-xl font-bold mb-4 text-gray-800 dark:text-white border-b-2 border-blue-200 dark:border-blue-800 pb-2">
                    البيانات الأساسية للعميل
                </h3>
                
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 dark:bg-red-200 dark:text-red-800">
                        <strong class="font-bold">حدث خطأ!</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">رقم العضوية <span class="text-red-500">*</span></label>
                        <input type="text" name="membership_number" required
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">الاسم الرباعي <span class="text-red-500">*</span></label>
                        <input type="text" name="full_name" required
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">الرقم الوطني <span class="text-red-500">*</span></label>
                        <input type="text" name="national_id" required
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">رقم الهاتف <span class="text-red-500">*</span></label>
                        <input type="text" name="phone" required
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">المدينة <span class="text-red-500">*</span></label>
                        <input type="text" name="city" required
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">العنوان التفصيلي <span class="text-red-500">*</span></label>
                        <textarea name="address" rows="2" required
                                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                </div>
            </div>

            <!-- بيانات الموكل -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white border-b-2 border-blue-200 dark:border-blue-800 pb-2">
                        بيانات الموكل (اختياري)
                    </h3>
                    <button type="button" onclick="toggleProxy()" 
                            class="text-blue-500 hover:text-blue-700 dark:text-blue-400 text-sm">
                        <i class="fas fa-eye mr-1"></i>إظهار/إخفاء
                    </button>
                </div>

                <div id="proxy-section" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">اسم الموكل</label>
                        <input type="text" name="proxy_full_name"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">الرقم الوطني</label>
                        <input type="text" name="proxy_national_id"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">رقم الهاتف</label>
                        <input type="text" name="proxy_phone"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">المدينة</label>
                        <input type="text" name="proxy_city"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">العنوان</label>
                        <textarea name="proxy_address" rows="2"
                                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                </div>
            </div>

            <!-- قسم المصانع -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white border-b-2 border-blue-200 dark:border-blue-800 pb-2">
                        المصانع التابعة <span class="text-red-500">*</span>
                    </h3>
                    <button type="button" onclick="addFactory()" 
                            class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700">
                        <i class="fas fa-plus mr-2"></i>إضافة مصنع
                    </button>
                </div>

                <div id="factories-container" class="space-y-4">
                    <!-- مصنع افتراضي -->
                    <div class="factory-group bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="font-semibold text-lg dark:text-white">المصنع #1</h4>
                            <button type="button" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                                    onclick="this.closest('.factory-group').remove()">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">اسم المصنع <span class="text-red-500">*</span></label>
                                <input type="text" name="factories[0][name]" required
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">نوع الآلة <span class="text-red-500">*</span></label>
                                <select name="factories[0][machine_type]" required 
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="آلي">آلي</option>
                                    <option value="نصف آلي">نصف آلي</option>
                                    <option value="يدوي">يدوي</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">نوع الحجز <span class="text-red-500">*</span></label>
                                <select name="factories[0][reservation_type]" required 
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="شهري">شهري</option>
                                    <option value="سنوي">سنوي</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">المساحة (م²) <span class="text-red-500">*</span></label>
                                <input type="number" step="0.01" name="factories[0][factory_area]" required
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">السعة (طن) <span class="text-red-500">*</span></label>
                                <input type="number" step="0.01" name="factories[0][specified_ton_quantity]" required
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">الموقع الجغرافي <span class="text-red-500">*</span></label>
                                <input type="url" name="factories[0][map_location]" required
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="مثال: https://maps.google.com/?q=26.3351,17.2283">
                                <p class="mt-2 text-sm text-gray-500">
                                    يمكنك نسخ رابط الموقع من Google Maps عن طريق:
                                    <br>1. البحث عن الموقع في Google Maps
                                    <br>2. النقر على زر "مشاركة"
                                    <br>3. نسخ الرابط
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- أزرار التحكم -->
            <div class="flex justify-end space-x-4 mt-8">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800">
                    <i class="fas fa-save mr-2"></i>حفظ البيانات
                </button>
                <a href="{{ route('clients.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600">
                    <i class="fas fa-times mr-2"></i>إلغاء
                </a>
            </div>
        </form>
    </div>

    <script>
        // تبديل عرض قسم الموكل
        function toggleProxy() {
            const section = document.getElementById('proxy-section');
            section.classList.toggle('hidden');
        }

        // إضافة مصانع ديناميكيًا
        let factoryIndex = 1;
        function addFactory() {
            const container = document.getElementById('factories-container');
            const newIndex = factoryIndex++;
            
            const html = `
            <div class="factory-group bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="font-semibold text-lg dark:text-white">المصنع #${newIndex + 1}</h4>
                    <button type="button" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                            onclick="this.closest('.factory-group').remove()">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">اسم المصنع <span class="text-red-500">*</span></label>
                        <input type="text" name="factories[${newIndex}][name]" required
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">نوع الآلة <span class="text-red-500">*</span></label>
                        <select name="factories[${newIndex}][machine_type]" required 
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="آلي">آلي</option>
                            <option value="نصف آلي">نصف آلي</option>
                            <option value="يدوي">يدوي</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">نوع الحجز <span class="text-red-500">*</span></label>
                        <select name="factories[${newIndex}][reservation_type]" required 
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="شهري">شهري</option>
                            <option value="سنوي">سنوي</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">المساحة (م²) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="factories[${newIndex}][factory_area]" required
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">السعة (طن) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="factories[${newIndex}][specified_ton_quantity]" required
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">الموقع الجغرافي <span class="text-red-500">*</span></label>
                        <input type="url" name="factories[${newIndex}][map_location]" required
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="مثال: https://maps.google.com/?q=26.3351,17.2283">
                        <p class="mt-2 text-sm text-gray-500">
                            يمكنك نسخ رابط الموقع من Google Maps عن طريق:
                            <br>1. البحث عن الموقع في Google Maps
                            <br>2. النقر على زر "مشاركة"
                            <br>3. نسخ الرابط
                        </p>
                    </div>
                </div>
            </div>`;
            
            container.insertAdjacentHTML('beforeend', html);
        }
    </script>
</x-app-layout>