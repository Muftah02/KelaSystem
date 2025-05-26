<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'رابطة الطوب الاسمنتي') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
              <!-- Logo -->
            <div class="shrink-0 flex items-center">
                <a href="{{ route('dashboard') }}">
                    <svg class="w-9 h-9 text-gray-800 dark:text-gray-200 group" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <!-- القاعدة ثلاثية الأبعاد -->
                        <path d="M10 70 L90 70 L80 90 L20 90 Z" 
                              class="fill-current group-hover:fill-purple-600 transition-colors"/>
                        
                        <!-- المصانع المترابطة -->
                        <g transform="translate(0 -5)">
                            <!-- المصنع الأول -->
                            <g class="transform group-hover:-translate-y-1 transition-transform">
                                <path d="M20 40 L30 20 L40 40 Z" class="fill-purple-500/80"/>
                                <rect x="22" y="40" width="16" height="30" 
                                      class="fill-[url(#purpleGradient)]"/>
                                <path d="M22 40 L38 40 L36 42 L24 42 Z" class="fill-purple-400"/>
                            </g>
            
                            <!-- المصنع الثاني -->
                            <g class="transform group-hover:-translate-y-[5px] transition-transform delay-100">
                                <path d="M50 30 L60 10 L70 30 Z" class="fill-indigo-500/80"/>
                                <rect x="52" y="30" width="16" height="40" 
                                      class="fill-[url(#indigoGradient)]"/>
                                <path d="M52 30 L68 30 L66 32 L54 32 Z" class="fill-indigo-400"/>
                            </g>
            
                            <!-- المصنع الثالث -->
                            <g class="transform group-hover:-translate-y-0.5 transition-transform delay-200">
                                <path d="M70 45 L80 25 L90 45 Z" class="fill-blue-500/80"/>
                                <rect x="72" y="45" width="16" height="25" 
                                      class="fill-[url(#blueGradient)]"/>
                                <path d="M72 45 L88 45 L86 47 L74 47 Z" class="fill-blue-400"/>
                            </g>
            
                            <!-- الروابط ثلاثية الأبعاد -->
                            <path d="M40 70 Q50 65 60 70" 
                                  class="stroke-[3px] stroke-purple-400 fill-none opacity-75"
                                  style="stroke-dasharray: 5 3"/>
                            <path d="M60 70 Q70 60 80 65" 
                                  class="stroke-[3px] stroke-indigo-400 fill-none opacity-75"
                                  style="stroke-dasharray: 5 3"/>
                            <path d="M30 65 Q45 55 55 60" 
                                  class="stroke-[3px] stroke-blue-400 fill-none opacity-75"
                                  style="stroke-dasharray: 5 3"/>
                        </g>
            
                        <!-- التأثيرات ثلاثية الأبعاد -->
                        <defs>
                            <linearGradient id="purpleGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" stop-color="#7c3aed"/>
                                <stop offset="100%" stop-color="#4338ca"/>
                            </linearGradient>
                            <linearGradient id="indigoGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" stop-color="#4f46e5"/>
                                <stop offset="100%" stop-color="#1e40af"/>
                            </linearGradient>
                            <linearGradient id="blueGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" stop-color="#2563eb"/>
                                <stop offset="100%" stop-color="#0e7490"/>
                            </linearGradient>
                        </defs>
            
                        <!-- النوافذ التفاعلية -->
                        <g class="opacity-75 group-hover:opacity-100 transition-opacity">
                            <rect x="25" y="45" width="3" height="5" class="fill-cyan-300 animate-pulse"/>
                            <rect x="30" y="45" width="3" height="5" class="fill-cyan-300 animate-pulse delay-150"/>
                            <rect x="55" y="35" width="3" height="5" class="fill-indigo-300 animate-pulse"/>
                            <rect x="60" y="35" width="3" height="5" class="fill-indigo-300 animate-pulse delay-75"/>
                            <rect x="75" y="50" width="3" height="5" class="fill-blue-300 animate-pulse"/>
                            <rect x="80" y="50" width="3" height="5" class="fill-blue-300 animate-pulse delay-100"/>
                        </g>
                    </svg>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
