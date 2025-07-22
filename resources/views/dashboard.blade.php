<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - نظام الإدارة</title>
    <style>
        :root {
            --bg-primary: #f8fafc;
            --bg-card: #ffffff;
            --text-primary: #1f2937;
            --text-secondary: #4b5563;
            --border-color: rgba(241, 245, 249, 0.8);
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            --card-hover-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08);
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg-primary: #111827;
                --bg-card: #1f2937;
                --text-primary: #f3f4f6;
                --text-secondary: #9ca3af;
                --border-color: rgba(31, 41, 55, 0.8);
                --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
                --card-hover-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
            }
        }

        body {
            font-family: system-ui, -apple-system, sans-serif;
            background: #f8fafc;
            color: #1f2937;
        }

        @media (prefers-color-scheme: dark) {
            body {
                background: #111827;
                color: #f3f4f6;
            }

            .bg-white {
                background-color: #1f2937 !important;
            }

            .text-gray-800 {
                color: #f3f4f6 !important;
            }

            .text-gray-600 {
                color: #9ca3af !important;
            }

            .text-gray-500 {
                color: #d1d5db !important;
            }

            .border-gray-100 {
                border-color: #374151 !important;
            }

            .bg-gray-50 {
                background-color: #374151 !important;
            }

            .card-footer {
                background: rgba(31, 41, 55, 0.5) !important;
            }

            .stat-icon {
                background: rgba(59, 130, 246, 0.2) !important;
            }
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .dashboard-card {
            transition: all 0.3s ease;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(241, 245, 249, 0.8);
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08);
        }

        .card-icon {
            position: absolute;
            top: -10px;
            left: -10px;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            font-size: 24px;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.15);
            z-index: 10;
        }

        .card-bg {
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 120px;
            opacity: 0.1;
            z-index: 0;
        }

        .card-content {
            position: relative;
            z-index: 2;
            padding: 24px;
            background: white;
        }

        .card-content h3 {
            color: var(--text-primary);
        }

        .card-content p {
            color: var(--text-secondary);
        }

        .card-footer {
            background: rgba(241, 245, 249, 0.5);
            padding: 12px 24px;
            border-top: 1px solid rgba(226, 232, 240, 0.5);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-gradient {
            background: linear-gradient(90deg, #1e3a8a 0%, #2563eb 100%);
        }

        .section-title {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 60px;
            height: 4px;
            border-radius: 2px;
            background: #3b82f6;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
        }

        .stat-card p:first-child {
            color: var(--text-secondary);
        }

        .stat-card p:last-child {
            color: var(--text-primary);
            font-size: 1.5rem;
            font-weight: bold;
            margin-top: 0.5rem;
        }

        .stat-icon {
            background: rgba(59, 130, 246, 0.1);
            padding: 0.75rem;
            border-radius: 0.75rem;
        }
    </style>
</head>
<body>
<x-app-layout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- شريط العنوان -->
                <div class="mb-8">
                    <div class="header-gradient text-white rounded-2xl p-6 shadow-lg">
                        <div class="flex flex-col md:flex-row justify-between items-center">
                            <div class="flex items-center mb-4 md:mb-0">
                                <div>
                                    <h1 class="text-2xl font-bold">لوحة التحكم</h1>
                                    <p class="text-blue-100 ">مرحبًا بك في نظام إدارة التوريدات والمخازن</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- رسائل التنبيه -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
                
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl">
                    <div class="p-6">
                        <h2 class="section-title text-xl font-bold">الوحدات الإدارية</h2>
                        
                        <div class="dashboard-grid">
                            <!-- التوريدات -->
                            <a href="{{ route('supplies.index') }}" class="dashboard-card">
                                <div class="card-icon bg-blue-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                    </svg>
                                </div>
                                <div class="card-bg bg-blue-500"></div>
                                <div class="card-content">
                                    <h3 class="text-xl font-bold mb-2">التوريدات</h3>
                                    <p>إدارة عمليات التوريد والمخازن</p>
                                </div>
                                <div class="card-footer">
                                    <span class="text-blue-500 font-medium">الذهاب للوحدة</span>
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </div>
                            </a>
                            
                            <!-- الصرف -->
                            <a href="{{ route('disbursements.index') }}" class="dashboard-card">
                                <div class="card-icon bg-green-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <div class="card-bg bg-green-500"></div>
                                <div class="card-content">
                                    <h3 class="text-xl font-bold mb-2">الصرف</h3>
                                    <p>إدارة عمليات الصرف والتوزيع</p>
                                </div>
                                <div class="card-footer">
                                    <span class="text-green-500 font-medium">الذهاب للوحدة</span>
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </div>
                            </a>
                            
                            <!-- المنتجات -->
                            <a href="{{ route('products.index') }}" class="dashboard-card">
                                <div class="card-icon bg-amber-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <div class="card-bg bg-amber-500"></div>
                                <div class="card-content">
                                    <h3 class="text-xl font-bold mb-2">المنتجات</h3>
                                    <p>إدارة المنتجات والفئات</p>
                                </div>
                                <div class="card-footer">
                                    <span class="text-amber-500 font-medium">الذهاب للوحدة</span>
                                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </div>
                            </a>
                            
                            <!-- الموردين -->
                            <a href="{{ route('suppliers.index') }}" class="dashboard-card">
                                <div class="card-icon bg-purple-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div class="card-bg bg-purple-500"></div>
                                <div class="card-content">
                                    <h3 class="text-xl font-bold mb-2">الموردين</h3>
                                    <p>إدارة الموردين والشركاء</p>
                                </div>
                                <div class="card-footer">
                                    <span class="text-purple-500 font-medium">الذهاب للوحدة</span>
                                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </div>
                            </a>
                            
                            <!-- الزبائن -->
                            <a href="{{ route('customers.index') }}" class="dashboard-card">
                                <div class="card-icon bg-cyan-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                                <div class="card-bg bg-cyan-500"></div>
                                <div class="card-content">
                                    <h3 class="text-xl font-bold mb-2">الزبائن</h3>
                                    <p>إدارة الزبائن والعلاقات</p>
                                </div>
                                <div class="card-footer">
                                    <span class="text-cyan-500 font-medium">الذهاب للوحدة</span>
                                    <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </div>
                            </a>
                            
                            <!-- النسخ الاحتياطي -->
                            <a href="{{ route('backup') }}" class="dashboard-card">
                                <div class="card-icon bg-red-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                                    </svg>
                                </div>
                                <div class="card-bg bg-red-500"></div>
                                <div class="card-content">
                                    <h3 class="text-xl font-bold mb-2">النسخ الاحتياطي</h3>
                                    <p>إنشاء نسخة احتياطية من قاعدة البيانات</p>
                                </div>
                                <div class="card-footer">
                                    <span class="text-red-500 font-medium">تنفيذ النسخ</span>
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- قسم الإحصائيات السريعة -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="stat-card">
                        <div class="flex justify-between items-center">
                            <div>
                                <p>إجمالي التوريدات</p>
                                <p class="text-blue-600">{{ \App\Models\Supply::count() }}</p>
                            </div>
                            <div class="stat-icon">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="flex justify-between items-center">
                            <div>
                                <p>عمليات الصرف</p>
                                <p class="text-green-600">{{ \App\Models\Disbursement::count() }}</p>
                            </div>
                            <div class="stat-icon">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="flex justify-between items-center">
                            <div>
                                <p>المنتجات المسجلة</p>
                                <p class="text-amber-600">{{ \App\Models\Product::count() }}</p>
                            </div>
                            <div class="stat-icon">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="flex justify-between items-center">
                            <div>
                                <p>الموردين النشطين</p>
                                <p class="text-purple-600">{{ \App\Models\Supplier::count() }}</p>
                            </div>
                            <div class="stat-icon">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>
</body>
</html>