<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
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
                

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('الصفحة الرئيسية') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" style=" font-weight: bold;">
                            {{ __('الملف الشخصي') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();" style=" font-weight: bold;">
                                {{ __('تسجيل خروج') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
