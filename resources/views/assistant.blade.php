<x-app-layout>
<div class="container mx-auto max-w-2xl py-8">
    <h2 class="text-2xl font-bold mb-6 text-center">المساعد الذكي للاستعلام عن البيانات</h2>
    <form id="assistant-form" class="bg-white dark:bg-gray-800 shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2" for="question">
                اكتب سؤالك بالعربية
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-900 leading-tight focus:outline-none focus:shadow-outline" id="question" type="text" placeholder="مثال: ما هي أسماء المنتجات؟" required>
        </div>
        <div class="flex items-center justify-between">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                إرسال
            </button>
        </div>
    </form>
    <div id="result" class="mt-6 hidden">
        <h3 class="text-lg font-semibold mb-2">نتيجة الاستعلام:</h3>
        <div class="mb-2">
            <span class="font-bold">SQL:</span>
            <span id="sql-query" class="font-mono text-sm bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded"></span>
        </div>
        <div>
            <span class="font-bold">البيانات:</span>
            <div id="sql-table-container"></div>
        </div>
    </div>
    <div id="error" class="mt-6 text-red-600 font-bold hidden"></div>
</div>
<script>
    function renderTable(data) {
        if (!Array.isArray(data) || data.length === 0) {
            return '<div class="text-gray-500">لا توجد بيانات.</div>';
        }
        let columns = Object.keys(data[0]);
        let thead = '<thead><tr>' + columns.map(col => `<th class="border px-2 py-1">${col}</th>`).join('') + '</tr></thead>';
        let tbody = '<tbody>' + data.map(row => '<tr>' + columns.map(col => `<td class="border px-2 py-1">${row[col]}</td>`).join('') + '</tr>').join('') + '</tbody>';
        return `<table class="min-w-full bg-white dark:bg-gray-800 border mt-2">${thead}${tbody}</table>`;
    }
    document.getElementById('assistant-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        document.getElementById('result').classList.add('hidden');
        document.getElementById('error').classList.add('hidden');
        const question = document.getElementById('question').value;
        try {
            const response = await fetch('/api/ask', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ question })
            });
            const data = await response.json();
            if (data.success) {
                document.getElementById('sql-query').textContent = data.query;
                document.getElementById('sql-table-container').innerHTML = renderTable(data.data);
                document.getElementById('result').classList.remove('hidden');
            } else {
                document.getElementById('error').textContent = data.message || 'حدث خطأ ما.';
                document.getElementById('error').classList.remove('hidden');
            }
        } catch (err) {
            document.getElementById('error').textContent = 'فشل الاتصال بالخادم.';
            document.getElementById('error').classList.remove('hidden');
        }
    });
</script>
</x-app-layout> 