<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

        <title>{{ config('app.name', 'مستشفى الكلى') }}</title>

        <!-- Fonts -->
        <style>
            @font-face {
                font-family: 'Tajawal';
                src: url('/fonts/Tajawal-Regular.ttf') format('truetype');
                font-weight: normal;
                font-style: normal;
                font-display: swap;
            }

            @font-face {
                font-family: 'Tajawal';
                src: url('/fonts/Tajawal-Medium.ttf') format('truetype');
                font-weight: 500;
                font-style: normal;
                font-display: swap;
            }

            @font-face {
                font-family: 'Tajawal';
                src: url('/fonts/Tajawal-Bold.ttf') format('truetype');
                font-weight: bold;
                font-style: normal;
                font-display: swap;
            }
        </style>

        <!-- أيقونات Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
        <div class="min-h-screen">
            <!-- السايدبار -->
            <aside class="fixed inset-y-0 right-0 w-64 bg-white dark:bg-gray-800 shadow-lg z-10">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-hospital"></i>
                        {{ config('app.name', 'لوحة التحكم') }}
                    </h2>
                </div>
                <nav class="p-4 overflow-y-auto h-[calc(100vh-4rem)]">
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('dashboard') }}" class="flex items-center p-2 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('dashboard') ? 'bg-gray-100 dark:bg-gray-700 border-r-4 border-blue-500' : '' }}">
                                <i class="fas fa-home ml-2"></i>
                                <span>الرئيسية</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('categories.index') }}" class="flex items-center p-2 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('categories.*') ? 'bg-gray-100 dark:bg-gray-700 border-r-4 border-blue-500' : '' }}">
                                <i class="fas fa-tags ml-2"></i>
                                <span>التصنيفات</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('companies.index') }}" class="flex items-center p-2 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('companies.*') ? 'bg-gray-100 dark:bg-gray-700 border-r-4 border-blue-500' : '' }}">
                                <i class="fas fa-building ml-2"></i>
                                <span>الشركات</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('unit-types.index') }}" class="flex items-center p-2 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('unit-types.*') ? 'bg-gray-100 dark:bg-gray-700 border-r-4 border-blue-500' : '' }}">
                                <i class="fas fa-ruler ml-2"></i>
                                <span>أنواع الوحدات</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('products.index') }}" class="flex items-center p-2 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('products.*') ? 'bg-gray-100 dark:bg-gray-700 border-r-4 border-blue-500' : '' }}">
                                <i class="fas fa-box ml-2"></i>
                                <span>المنتجات</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('suppliers.index') }}" class="flex items-center p-2 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('suppliers.*') ? 'bg-gray-100 dark:bg-gray-700 border-r-4 border-blue-500' : '' }}">
                                <i class="fas fa-truck ml-2"></i>
                                <span>الموردين</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('customers.index') }}" class="flex items-center p-2 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('customers.*') ? 'bg-gray-100 dark:bg-gray-700 border-r-4 border-blue-500' : '' }}">
                                <i class="fas fa-users ml-2"></i>
                                <span>الزبائن</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('supplies.index') }}" class="flex items-center p-2 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('supplies.*') ? 'bg-gray-100 dark:bg-gray-700 border-r-4 border-blue-500' : '' }}">
                                <i class="fas fa-shopping-cart ml-2"></i>
                                <span>توريد</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('disbursements.index') }}" class="flex items-center p-2 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('disbursements.*') ? 'bg-gray-100 dark:bg-gray-700 border-r-4 border-blue-500' : '' }}">
                                <i class="fas fa-hand-holding ml-2"></i>
                                <span>الصرف</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('product-movements.index') }}" class="flex items-center p-2 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('product-movements.*') ? 'bg-gray-100 dark:bg-gray-700 border-r-4 border-blue-500' : '' }}">
                                <i class="fas fa-exchange-alt ml-2"></i>
                                <span>حركات المنتجات</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('inventory-card.index') }}" class="flex items-center p-2 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('inventory-card.*') ? 'bg-gray-100 dark:bg-gray-700 border-r-4 border-blue-500' : '' }}">
                                <i class="fas fa-clipboard-list ml-2"></i>
                                <span>بطاقة جرد المنتجات</span>
                            </a>
                        </li>
                        <li>
                            <button id="open-assistant" type="button" class="flex items-center w-full p-2 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-700 transition-colors focus:outline-none">
                                <i class="fas fa-robot ml-2"></i>
                                <span>المساعد الذكي</span>
                            </button>
                        </li>
                    </ul>
                </nav>
            </aside>

            <!-- المحتوى الرئيسي -->
            <div class="mr-64">
                @include('layouts.navigation')

                @isset($header)
                    <header class="bg-white dark:bg-gray-800 shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                                {{ $header }}
                            </h2>
                        </div>
                    </header>
                @endisset

                <main class="p-4">
                    {{ $slot }}
                </main>

                <!-- Footer -->
                <footer class="bg-white dark:bg-gray-800 shadow-lg mt-8">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        <div class="text-center text-gray-600 dark:text-gray-400">
                            <p class="text-sm">
                                تم تطوير النظام بواسطة: مفتاح ابوكيل
                                <br>
                                للتواصل: <a href="tel:0910309736" class="text-blue-600 dark:text-blue-400 hover:underline">0910309736</a>
                            </p>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <!-- Popup for Assistant (Modern Chat Style) -->
        <div id="assistant-popup" class="fixed bottom-6 left-6 z-50 flex items-end justify-end hidden">
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-80 max-w-full relative animate-fade-in-up border border-gray-200 dark:border-gray-700 flex flex-col" style="min-height:480px; max-height:80vh;">
                <!-- Header -->
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-gray-800 rounded-t-2xl bg-gray-50 dark:bg-gray-800">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-purple-100 text-purple-700 text-xl"><i class="fas fa-robot"></i></span>
                        <span class="font-bold text-gray-800 dark:text-white text-lg">مساعد كلى</span>
                    </div>
                    <button id="close-assistant" class="text-gray-400 hover:text-red-600 text-2xl focus:outline-none" title="إغلاق"><i class="fas fa-times"></i></button>
                </div>
                <!-- Chat Body -->
                <div id="chat-body" class="flex-1 overflow-y-auto px-4 py-3 space-y-3 bg-white dark:bg-gray-900" style="direction: rtl;">
                    <div class="flex justify-start">
                        <div class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2 max-w-[80%] shadow text-sm">
                            👋 مرحبًا! اكتب سؤالك وسأساعدك في الاستعلام عن بيانات النظام.
                        </div>
                    </div>
                </div>
                <!-- Input -->
                <form id="chat-form" class="flex items-center gap-2 border-t border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 rounded-b-2xl px-3 py-2">
                    <input id="chat-input" type="text" class="flex-1 bg-transparent focus:outline-none text-gray-800 dark:text-gray-100 placeholder-gray-400 text-sm" placeholder="اكتب سؤالك..." autocomplete="off" required>
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white rounded-full w-9 h-9 flex items-center justify-center focus:outline-none transition"><i class="fas fa-paper-plane"></i></button>
                </form>
                <div class="text-center text-xs text-gray-400 py-1">قد ينتج المساعد معلومات غير دقيقة</div>
            </div>
        </div>
        <style>
            @keyframes fade-in-up {
                0% { opacity: 0; transform: translateY(40px); }
                100% { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in-up { animation: fade-in-up 0.3s cubic-bezier(.4,0,.2,1); }
            #assistant-popup::-webkit-scrollbar { width: 6px; }
            #assistant-popup::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 3px; }
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const openBtn = document.getElementById('open-assistant');
                const popup = document.getElementById('assistant-popup');
                const closeBtn = document.getElementById('close-assistant');
                const chatBody = document.getElementById('chat-body');
                const chatForm = document.getElementById('chat-form');
                const chatInput = document.getElementById('chat-input');
                if (openBtn && popup && closeBtn) {
                    openBtn.addEventListener('click', function() {
                        popup.classList.remove('hidden');
                        setTimeout(() => { chatInput.focus(); }, 300);
                    });
                    closeBtn.addEventListener('click', function() {
                        popup.classList.add('hidden');
                    });
                }
                // إرسال الرسالة
                if (chatForm && chatInput && chatBody) {
                    chatForm.addEventListener('submit', async function(e) {
                        e.preventDefault();
                        const userMsg = chatInput.value.trim();
                        if (!userMsg) return;
                        // أضف رسالة المستخدم
                        chatBody.innerHTML += `<div class='flex justify-end'><div class='bg-purple-600 text-white rounded-xl px-4 py-2 max-w-[80%] shadow text-sm mb-1'>${userMsg}</div></div>`;
                        chatInput.value = '';
                        chatBody.scrollTop = chatBody.scrollHeight;
                        // أضف رسالة انتظار
                        const loadingId = 'loading-' + Date.now();
                        chatBody.innerHTML += `<div id='${loadingId}' class='flex justify-start'><div class='bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2 max-w-[80%] shadow text-sm flex items-center gap-2'><span class='loader'></span> جاري التفكير...</div></div>`;
                        chatBody.scrollTop = chatBody.scrollHeight;
                        // أرسل الطلب
                        try {
                            const response = await fetch('/api/ask', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({ question: userMsg })
                            });
                            const data = await response.json();
                            document.getElementById(loadingId)?.remove();
                            if (data.success) {
                                // عرض النتائج في جدول إذا كانت مصفوفة، أو نص إذا كانت قيمة واحدة
                                if (Array.isArray(data.data) && data.data.length > 0 && typeof data.data[0] === 'object') {
                                    let columns = Object.keys(data.data[0]);
                                    let thead = '<thead><tr>' + columns.map(col => `<th class="border px-2 py-1">${col}</th>`).join('') + '</tr></thead>';
                                    let tbody = '<tbody>' + data.data.map(row => '<tr>' + columns.map(col => `<td class="border px-2 py-1">${row[col]}</td>`).join('') + '</tr>').join('') + '</tbody>';
                                    let table = `<table class='min-w-full bg-white dark:bg-gray-800 border mt-2 mb-1 text-xs'>${thead}${tbody}</table>`;
                                    chatBody.innerHTML += `<div class='flex justify-start'><div class='bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-2 py-2 max-w-[80%] shadow text-sm'>${table}<div class='text-left text-xs text-gray-400 mt-1'>SQL: <span class='font-mono'>${data.query}</span></div></div></div>`;
                                } else if (Array.isArray(data.data) && data.data.length === 0) {
                                    chatBody.innerHTML += `<div class='flex justify-start'><div class='bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2 max-w-[80%] shadow text-sm'>لا توجد بيانات.</div></div>`;
                                } else {
                                    chatBody.innerHTML += `<div class='flex justify-start'><div class='bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2 max-w-[80%] shadow text-sm'>${JSON.stringify(data.data)}</div></div>`;
                                }
                            } else {
                                chatBody.innerHTML += `<div class='flex justify-start'><div class='bg-red-100 dark:bg-red-700 text-red-800 dark:text-red-100 rounded-xl px-4 py-2 max-w-[80%] shadow text-sm'>${data.message || 'حدث خطأ ما.'}</div></div>`;
                            }
                            chatBody.scrollTop = chatBody.scrollHeight;
                        } catch (err) {
                            document.getElementById(loadingId)?.remove();
                            chatBody.innerHTML += `<div class='flex justify-start'><div class='bg-red-100 dark:bg-red-700 text-red-800 dark:text-red-100 rounded-xl px-4 py-2 max-w-[80%] shadow text-sm'>فشل الاتصال بالخادم.</div></div>`;
                            chatBody.scrollTop = chatBody.scrollHeight;
                        }
                    });
                }
            });
        </script>

        @stack('scripts')
    </body>
</html>