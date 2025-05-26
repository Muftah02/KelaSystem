<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight dark:bg-gray-900">
            تعديل بيانات العميل
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow mt-6">
        <form action="{{ route('clients.update', $client->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- بيانات العميل الأساسية -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg mb-6">
                <h3 class="text-xl font-bold mb-4 text-gray-800 dark:text-white border-b-2 border-blue-200 dark:border-blue-800 pb-2">
                    البيانات الأساسية للعميل
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">رقم العضوية</label>
                        <input type="text" name="membership_number" 
                               value="{{ old('membership_number', $client->membership_number) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('membership_number')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">الاسم الكامل</label>
                        <input type="text" name="full_name" 
                               value="{{ old('full_name', $client->full_name) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('full_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    
                    <!-- باقي حقول العميل -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">الرقم القومي</label>
                        <input type="text" name="national_id" 
                               value="{{ old('national_id', $client->national_id) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('national_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">رقم الهاتف</label>
                        <input type="text" name="phone" 
                               value="{{ old('phone', $client->phone) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('phone')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                   
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">المدينة</label>
                        <input type="text" name="city" 
                               value="{{ old('city', $client->city) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('city')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">العنوان التفصيلي</label>
                        <textarea name="address" 
                                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('address', $client->address) }}</textarea>
                        @error('address')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
            </div>

            <!-- بيانات الموكل -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg mb-6">
                <h3 class="text-xl font-bold mb-4 text-gray-800 dark:text-white border-b-2 border-blue-200 dark:border-blue-800 pb-2">
                    بيانات الموكل (اختياري)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">اسم الموكل</label>
                        <input type="text" name="proxy_full_name" 
                               value="{{ old('proxy_full_name', optional($client->proxy)->full_name) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('proxy_full_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">رقم الهوية الموكل</label>
                        <input type="text" name="proxy_national_id" 
                               value="{{ old('proxy_national_id', optional($client->proxy)->national_id) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('proxy_national_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                        
                </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">رقم هاتف الموكل</label>
                        <input type="text" name="proxy_phone" 
                               value="{{ old('proxy_phone', optional($client->proxy)->phone) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('proxy_phone')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">مدينة الموكل</label>
                        <input type="text" name="proxy_city" 
                               value="{{ old('proxy_city', optional($client->proxy)->city) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('proxy_city')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
            </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">عنوان الموكل</label>
                        <textarea name="proxy_address" 
                                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('proxy_address', optional($client->proxy)->address) }}</textarea>
                        @error('proxy_address')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- قسم المصانع -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white border-b-2 border-blue-200 dark:border-blue-800 pb-2">
                        المصانع التابعة
                    </h3>
                    <button type="button" onclick="addFactory()" 
                            class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700">
                        + إضافة مصنع
                    </button>
                </div>

                <div id="factories-container">
                    @foreach($client->factories as $index => $factory)
                    <div class="factory-group bg-white dark:bg-gray-800 p-4 rounded-lg shadow mb-4">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="font-semibold text-lg dark:text-white">المصنع #{{ $index + 1 }}</h4>
                            <button type="button" onclick="deleteFactory(this, {{ $factory->id }})" 
                                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                حذف
                            </button>
                        </div>
                        
                        <input type="hidden" name="factories[{{ $index }}][id]" value="{{ $factory->id }}">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">اسم المصنع</label>
                                <input type="text" name="factories[{{ $index }}][name]" 
                                       value="{{ old('factories.'.$index.'.name', $factory->name) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('factories.'.$index.'.name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">نوع الآلة</label>
                                <select name="factories[{{ $index }}][machine_type]" 
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="آلي" {{ $factory->machine_type == 'آلي' ? 'selected' : '' }}>آلي</option>
                                    <option value="نصف آلي" {{ $factory->machine_type == 'نصف آلي' ? 'selected' : '' }}>نصف آلي</option>
                                    <option value="يدوي" {{ $factory->machine_type == 'يدوي' ? 'selected' : '' }}>يدوي</option>
                                </select>
                                @error('factories.'.$index.'.machine_type')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                              </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">نوع الحجز</label>
                                <select name="factories[{{ $index }}][reservation_type]" 
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="شهري" {{ $factory->reservation_type == 'شهري' ? 'selected' : '' }}>شهري</option>
                                    <option value="سنوي" {{ $factory->reservation_type == 'سنوي' ? 'selected' : '' }}>سنوي</option>
                                </select>
                                @error('factories.'.$index.'.reservation_type')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">المساحة (م²)</label>
                                <input type="number" step="0.01" name="factories[{{ $index }}][factory_area]" 
                                       value="{{ old('factories.'.$index.'.factory_area', $factory->factory_area) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('factories.'.$index.'.factory_area')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">الكمية المحددة (طن)</label>
                                <input type="number" step="0.01" name="factories[{{ $index }}][specified_ton_quantity]" 
                                       value="{{ old('factories.'.$index.'.specified_ton_quantity', $factory->specified_ton_quantity) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('factories.'.$index.'.specified_ton_quantity')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">الموقع</label>
                                <input type="text" name="factories[{{ $index }}][map_location]" 
                                       value="{{ old('factories.'.$index.'.map_location', $factory->map_location) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('factories.'.$index.'.map_location')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>  

                            
                           
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <a href="{{ route('clients.index') }}" 
                   class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600">
                    إلغاء
                </a>
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800">
                    تحديث البيانات
                </button>
            </div>
        </form>
    </div>

    <script>
        let factoryIndex = {{ count($client->factories) }};
        
        function addFactory() {
            const container = document.getElementById('factories-container');
            const html = `
            <div class="factory-group bg-white dark:bg-gray-800 p-4 rounded-lg shadow mb-4">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="font-semibold text-lg dark:text-white">مصنع جديد</h4>
                    <button type="button" onclick="deleteFactory(this)" 
                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                        حذف
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">اسم المصنع</label>
                        <input type="text" name="factories[${factoryIndex}][name]" 
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">نوع الآلة</label>
                        <select name="factories[${factoryIndex}][machine_type]" 
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                            <option value="آلي">آلي</option>
                            <option value="نصف آلي">نصف آلي</option>
                            <option value="يدوي">يدوي</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">نوع الحجز</label>
                        <select name="factories[${factoryIndex}][reservation_type]" 
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                            <option value="شهري">شهري</option>
                            <option value="سنوي">سنوي</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">المساحة (م²)</label>
                        <input type="number" step="0.01" name="factories[${factoryIndex}][factory_area]" 
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">الكمية المحددة (طن)</label>
                        <input type="number" step="0.01" name="factories[${factoryIndex}][specified_ton_quantity]" 
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               required>
                    </div>
                </div>
            </div>`;

            container.insertAdjacentHTML('beforeend', html);
            factoryIndex++;
        }

        function deleteFactory(button, factoryId = null) {
            if (confirm('هل أنت متأكد من الحذف؟')) {
                if (factoryId) {
                    // إرسال طلب حذف عبر AJAX إذا كان مصنع موجود
                    fetch(`/factories/${factoryId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    });
                }
                button.closest('.factory-group').remove();
            }
        }
    </script>
</x-app-layout>