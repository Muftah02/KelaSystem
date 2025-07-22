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
        <script src="{{ asset('js/jquery.min.js') }}"></script>
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
                                تم تطوير النظام بواسطة : شركة وصلة
                                <br>
                                للتواصل: <a href="tel:0910309736" class="text-blue-600 dark:text-blue-400 hover:underline">0910309736</a>
                            </p>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>