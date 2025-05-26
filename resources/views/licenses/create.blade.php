<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">إضافة ترخيص جديد</h1>
                <a href="{{ route('licenses.index') }}"
                    class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-gray-100 transition-colors">
                    العودة إلى قائمة التراخيص
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-700"
                 x-data="{
                     clients: @js($clientsForDropdown),
                     selectedClientId: '',
                     selectedFactoryId: '',
                     get selectedClient() {
                         return this.clients.find(c => c.id === parseInt(this.selectedClientId)) || null;
                     },
                     get factories() {
                         return this.selectedClient ? this.selectedClient.factories : [];
                     },
                     get factoriesCount() {
                         return this.factories.length;
                     }
                 }">
                <form action="{{ route('licenses.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            العميل <span class="text-red-500">*</span>
                        </label>
                        <select name="client_id"
                                x-model="selectedClientId"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>
                            <option value="">اختر العميل</option>
                            <template x-for="client in clients" :key="client.id">
                                <option :value="client.id" x-text="client.name"></option>
                            </template>
                        </select>
                        <div x-show="selectedClient" class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            <p x-text="'عدد المصانع: ' + factoriesCount"></p>
                        </div>
                        @error('client_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="factory_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            المصنع <span class="text-red-500">*</span>
                        </label>
                        <select name="factory_id"
                                x-model="selectedFactoryId"
                                :disabled="!selectedClient"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required>
                            <option value="">اختر المصنع</option>
                            <template x-for="factory in factories" :key="factory.id">
                                <option :value="factory.id" x-text="factory.name"></option>
                            </template>
                        </select>
                        @error('factory_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            تاريخ البداية <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                               class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                               required>
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            تاريخ الانتهاء <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                               class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                               required>
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-4 py-2 rounded transition-colors">
                            حفظ الترخيص
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
