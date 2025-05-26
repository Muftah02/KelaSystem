<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">تعديل الترخيص</h1>
                <a href="{{ route('licenses.index') }}"
                    class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                    <i class="fas fa-arrow-left ml-2"></i> العودة للقائمة
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700">
                <form action="{{ route('licenses.update', $license) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            العميل <span class="text-red-500">*</span>
                        </label>
                        <div x-data="{
                            clients: @js(
                                $clients
                                    ->map(
                                        fn($c) => [
                                            'id' => $c->id,
                                            'name' => $c->full_name . ' (' . $c->membership_number . ')',
                                            'factories' => $c->factories
                                                ->map(
                                                    fn($f) => [
                                                        'id' => $f->id,
                                                        'name' => $f->name,
                                                    ],
                                                )
                                                ->values()
                                                ->all(),
                                        ],
                                    )
                                    ->values()
                                    ->all(),
                            ),
                            selectedClientId: '{{ old('client_id', $license->client_id) }}' ? Number('{{ old('client_id', $license->client_id) }}') : '',
                            selectedFactoryId: '{{ old('factory_id', $license->factory_id) }}' ? Number('{{ old('factory_id', $license->factory_id) }}') : '',

                            get selectedClient() {
                                return this.clients.find(c => c.id == this.selectedClientId) || null;
                            }
                        }" x-init="$nextTick(() => {
                            // تحديث المصانع عند تحميل الصفحة
                            if (selectedClientId) {
                                const client = clients.find(c => c.id == selectedClientId);
                                if (client) {
                                    selectedFactoryId = '{{ old('factory_id', $license->factory_id) }}';
                                }
                            }
                        })">
                            <select name="client_id" x-model="selectedClientId"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>
                                <option value="">اختر العميل</option>
                                <template x-for="client in clients" :key="client.id">
                                    <option :value="client.id" x-text="client.name"></option>
                                </template>
                            </select>
                            @error('client_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror

                            <div class="mt-4">
                                <label for="factory_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    المصنع <span class="text-red-500">*</span>
                                </label>
                                <select name="factory_id" x-model="selectedFactoryId"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    :disabled="!selectedClient" required>
                                    <option value="">اختر المصنع</option>
                                    <template x-for="factory in selectedClient ? selectedClient.factories : []"
                                        :key="factory.id">
                                        <option :value="factory.id" x-text="factory.name"></option>
                                    </template>
                                </select>
                                @error('factory_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="start_date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                تاريخ البداية <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="start_date" id="start_date"
                                value="{{ old('start_date', $license->start_date->format('Y-m-d')) }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="end_date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                تاريخ الانتهاء <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="end_date" id="end_date"
                                value="{{ old('end_date', $license->end_date->format('Y-m-d')) }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-8">
                        <a href="{{ route('licenses.index') }}"
                            class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded transition-colors">
                            إلغاء
                        </a>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white px-4 py-2 rounded transition-colors">
                            <i class="fas fa-save ml-2"></i> حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
