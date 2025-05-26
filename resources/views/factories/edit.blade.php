<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white">تعديل بيانات المصنع</h1>
                <a href="{{ route('factories.index') }}"
                    class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-arrow-left ml-2"></i> رجوع للقائمة
                </a>
            </div>

            <form action="{{ route('factories.update', $factory) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- حقل العميل -->
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">اسم العميل</label>
                        <p class="p-3 bg-gray-100 dark:bg-gray-700 rounded dark:text-gray-200">
                            {{ $factory->client->full_name ?? 'غير معروف' }}
                        </p>
                        <input type="hidden" name="client_id" value="{{ $factory->client_id }}">
                    </div>

                    <!-- اسم المصنع -->
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            اسم المصنع <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $factory->name) }}"
                            class="w-full border dark:border-gray-600 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('name') border-red-500 @enderror"
                            placeholder="أدخل اسم المصنع">
                        @error('name')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- نوع الآلة -->
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            نوع الآلة <span class="text-red-500">*</span>
                        </label>
                        <select name="machine_type"
                            class="w-full border dark:border-gray-600 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('machine_type') border-red-500 @enderror">
                            <option value="آلي" {{ $factory->machine_type == 'آلي' ? 'selected' : '' }}>آلي</option>
                            <option value="نصف آلي" {{ $factory->machine_type == 'نصف آلي' ? 'selected' : '' }}>نصف آلي</option>
                            <option value="يدوي" {{ $factory->machine_type == 'يدوي' ? 'selected' : '' }}>يدوي</option>
                        </select>
                        @error('machine_type')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- نوع الحجز -->
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            نوع الحجز <span class="text-red-500">*</span>
                        </label>
                        <select name="reservation_type"
                            class="w-full border dark:border-gray-600 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('reservation_type') border-red-500 @enderror">
                            <option value="شهري" {{ $factory->reservation_type == 'شهري' ? 'selected' : '' }}>شهري</option>
                            <option value="سنوي" {{ $factory->reservation_type == 'سنوي' ? 'selected' : '' }}>سنوي</option>
                        </select>
                        @error('reservation_type')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- مساحة المصنع -->
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            المساحة (م²) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.01" name="factory_area"
                            value="{{ old('factory_area', $factory->factory_area) }}"
                            class="w-full border dark:border-gray-600 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('factory_area') border-red-500 @enderror"
                            placeholder="أدخل المساحة بالمتر المربع">
                        @error('factory_area')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- كمية الطن -->
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            كمية الطن المحددة <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.01" name="specified_ton_quantity"
                            value="{{ old('specified_ton_quantity', $factory->specified_ton_quantity) }}"
                            class="w-full border dark:border-gray-600 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('specified_ton_quantity') border-red-500 @enderror"
                            placeholder="أدخل كمية الطن">
                        @error('specified_ton_quantity')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- الموقع على الخريطة -->
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            الموقع على الخريطة <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="map_location"
                            value="{{ old('map_location', $factory->map_location) }}"
                            class="w-full border dark:border-gray-600 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('map_location') border-red-500 @enderror"
                            placeholder="أدخل رابط الموقع الجغرافي">
                        @error('map_location')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- الموقع الجغرافي -->
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            الموقع الجغرافي <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="latitude" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                    خط العرض <span class="text-red-500">*</span>
                                </label>
                                <input type="number" step="0.00000001" name="latitude" id="latitude" value="{{ old('latitude', $factory->latitude) }}"
                                    class="w-full border dark:border-gray-600 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('latitude') border-red-500 @enderror"
                                    placeholder="مثال: 26.3351">
                                @error('latitude')
                                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="longitude" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                    خط الطول <span class="text-red-500">*</span>
                                </label>
                                <input type="number" step="0.00000001" name="longitude" id="longitude" value="{{ old('longitude', $factory->longitude) }}"
                                    class="w-full border dark:border-gray-600 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('longitude') border-red-500 @enderror"
                                    placeholder="مثال: 17.2283">
                                @error('longitude')
                                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            يمكنك نسخ الإحداثيات من Google Maps عن طريق النقر على الموقع المطلوب في الخريطة
                        </p>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-4">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white px-6 py-3 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-save ml-2"></i>
                        حفظ التغييرات
                    </button>

                    <button type="reset"
                        class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-6 py-3 rounded-lg transition duration-200">
                        إعادة تعيين
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // تهيئة حقول الاختيار
                const selectElements = document.querySelectorAll('select');
                selectElements.forEach(select => {
                    const choice = new Choices(select, {
                        removeItemButton: true,
                        searchEnabled: true,
                        placeholderValue: 'اختر من القائمة',
                        searchPlaceholderValue: 'ابحث...',
                        shouldSort: false,
                        classNames: {
                            containerInner: 'choices__inner bg-white dark:bg-gray-700',
                        }
                    });
                });

                // التحكم في الحقول الرقمية
                const numberInputs = document.querySelectorAll('input[type="number"]');
                numberInputs.forEach(input => {
                    input.addEventListener('input', (e) => {
                        if (e.target.value < 0) {
                            e.target.value = Math.abs(e.target.value);
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
