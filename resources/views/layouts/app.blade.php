<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        
        {{-- @vite('resources/css/app.css')
        @vite('resources/js/app.js') --}}
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'رابطة الطوب الاسمنتي') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- أيقونات Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

        <!-- Styles -->
        @stack('styles')

        <style>
            /* تخصيصات إضافية */
            .sidebar-rtl {
                right: 0;
                left: auto;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex">
            <!-- السايدبار الجديد -->
            <div class="w-64 bg-gradient-to-b from-blue-600 to-purple-600 dark:from-blue-700 dark:to-purple-700 shadow-lg fixed sidebar-rtl h-full z-10">
                <div class="p-4 border-b border-white/10">
                    <h2 class="text-xl font-semibold text-white">
                        {{ config('app.name', 'لوحة التحكم') }}
                    </h2>
                </div>
                <nav class="p-4">
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('dashboard') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('dashboard') ? 'bg-white/10' : '' }}">
                                <i class="fas fa-home ml-2"></i>
                                <span>الرئيسية</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('clients.index') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('clients.*') ? 'bg-white/10' : '' }}">
                                <i class="fas fa-users ml-2"></i>
                                <span>العملاء</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('factories.index') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('factories.*') ? 'bg-white/10' : '' }}">
                                <i class="fas fa-industry ml-2"></i>
                                <span>المصانع</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('licenses.index') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('licenses.*') ? 'bg-white/10' : '' }}">
                                <i class="fas fa-file-alt ml-2"></i>
                                <span>التراخيص</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- المحتوى الرئيسي -->
            <div class="flex-1 mr-64"> <!-- margin-right يتناسب مع عرض السايدبار -->
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-700 dark:to-purple-700 shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="p-4">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Scripts -->
        @stack('scripts')
    </body>
</html>