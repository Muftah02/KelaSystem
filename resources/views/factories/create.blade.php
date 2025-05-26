<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">إضافة مصنع جديد</h1>
                <a href="{{ route('factories.index') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                    <i class="fas fa-arrow-left ml-2"></i> العودة للقائمة
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                <form action="{{ route('factories.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            العميل <span class="text-red-500">*</span>
                        </label>
                        <select name="client_id" id="client_id" 
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>
                            <option value="">اختر العميل</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->full_name }} ({{ $client->membership_number }})
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            اسم المصنع <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" 
                               value="{{ old('name') }}"
                               class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="machine_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                نوع الآلة <span class="text-red-500">*</span>
                            </label>
                            <select name="machine_type" id="machine_type" 
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    required>
                                <option value="">اختر نوع الآلة</option>
                                <option value="آلي" {{ old('machine_type') == 'آلي' ? 'selected' : '' }}>آلي</option>
                                <option value="نصف آلي" {{ old('machine_type') == 'نصف آلي' ? 'selected' : '' }}>نصف آلي</option>
                                <option value="يدوي" {{ old('machine_type') == 'يدوي' ? 'selected' : '' }}>يدوي</option>
                            </select>
                            @error('machine_type')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="reservation_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                نوع الحجز <span class="text-red-500">*</span>
                            </label>
                            <select name="reservation_type" id="reservation_type" 
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    required>
                                <option value="">اختر نوع الحجز</option>
                                <option value="شهري" {{ old('reservation_type') == 'شهري' ? 'selected' : '' }}>شهري</option>
                                <option value="سنوي" {{ old('reservation_type') == 'سنوي' ? 'selected' : '' }}>سنوي</option>
                            </select>
                            @error('reservation_type')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="factory_area" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                المساحة (م²) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" step="0.01" name="factory_area" id="factory_area" 
                                   value="{{ old('factory_area') }}"
                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                   required>
                            @error('factory_area')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="specified_ton_quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                الكمية المحددة (طن) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" step="0.01" name="specified_ton_quantity" id="specified_ton_quantity" 
                                   value="{{ old('specified_ton_quantity') }}"
                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                   required>
                            @error('specified_ton_quantity')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="map_location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            الموقع <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="map_location" id="map_location" 
                               value="{{ old('map_location') }}"
                               class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                               required>
                        @error('map_location')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3 mt-8">
                        <a href="{{ route('factories.index') }}" 
                           class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded transition-colors">
                            إلغاء
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white px-4 py-2 rounded transition-colors">
                            <i class="fas fa-save ml-2"></i> حفظ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout> 