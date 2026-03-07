<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- jQuery for Select2 -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
        <!-- Select2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        
        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-50 flex">
            {{-- Sidebar --}}
            @auth
            <div class="hidden md:flex flex-col w-64 bg-gradient-to-b from-slate-900 to-slate-800 text-white fixed h-screen">
                <div class="flex items-center justify-center h-20 border-b border-slate-700 px-4">
                    <h1 class="text-2xl font-bold text-center">{{ config('app.name', 'GST Invoice') }}</h1>
                </div>
                
                <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-600' : 'text-gray-300 hover:bg-slate-700' }} transition">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4m0 0l4 4"></path>
                        </svg>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('invoices.index') }}" class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('invoices.*') ? 'bg-blue-600' : 'text-gray-300 hover:bg-slate-700' }} transition">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Invoices
                    </a>
                    
                    <a href="{{ route('products.index') }}" class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('products.*') ? 'bg-blue-600' : 'text-gray-300 hover:bg-slate-700' }} transition">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Products
                    </a>
                    
                    <a href="{{ route('coupons.index') }}" class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('coupons.*') ? 'bg-blue-600' : 'text-gray-300 hover:bg-slate-700' }} transition">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                        Coupons
                    </a>
                    
                    <a href="{{ route('customers.index') }}" class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('customers.*') ? 'bg-blue-600' : 'text-gray-300 hover:bg-slate-700' }} transition">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        Customers
                    </a>
                    
                    <a href="{{ route('dues.index') }}" class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('dues.*') ? 'bg-blue-600' : 'text-gray-300 hover:bg-slate-700' }} transition">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Due Payments
                    </a>
                </nav>
                
                <div class="border-t border-slate-700 p-4 space-y-2">
                    <a href="{{ route('settings') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('settings') ? 'bg-blue-600' : 'text-gray-300 hover:bg-slate-700' }} rounded-lg transition">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Settings
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-3 text-gray-300 hover:bg-red-600 rounded-lg transition">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
            @endauth

            {{-- Main Content --}}
            <div class="flex-1 @auth md:ml-64 @endauth flex flex-col">
                {{-- Top Header --}}
                <div class="bg-white border-b border-gray-200 h-20 flex items-center justify-between px-6 shadow-sm">
                    <div class="flex items-center space-x-4">
                        {{-- Mobile menu button --}}
                        @auth
                        <button class="md:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        @endauth
                        
                        <h1 class="text-2xl font-bold text-gray-900">@yield('page_title', 'Dashboard')</h1>
                    </div>
                    
                    <div class="flex items-center space-x-6">
                        @guest
                            <a href="{{ route('login') }}" class="px-4 py-2 text-gray-700 hover:text-gray-900 font-medium">{{ __('Login') }}</a>
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">{{ __('Register') }}</a>
                        @endguest
                        
                        @auth
                            <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>
                            
                            <div class="flex items-center space-x-3 border-l border-gray-200 pl-6">
                                <div class="text-right hidden sm:block">
                                    <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                </div>
                                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=0D8ABC&color=fff" alt="Profile" class="w-10 h-10 rounded-full">
                            </div>
                        @endauth
                    </div>
                </div>

                {{-- Scrollable Content --}}
                <main class="flex-1 overflow-y-auto p-6">
                    @yield('content')
                </main>
            </div>
        </div>

        {{-- Footer --}}
        <footer class="bg-gray-900 text-gray-400 text-center py-4 text-sm border-t border-gray-800 w-full">
            <p>Developed by <strong>GIRI TechLab</strong> | <a href="mailto:soumendugiri654@gmail.com" class="text-blue-400 hover:text-blue-300">soumendugiri654@gmail.com</a></p>
        </footer>

        {{-- Toast Container --}}
        <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

        <script>
            function showToast(message, type = 'info', duration = 3000) {
                const container = document.getElementById('toast-container');
                
                const toast = document.createElement('div');
                toast.className = `flex items-center p-4 rounded-lg shadow-lg text-white transform transition-all duration-300 animate-slide-in ${
                    type === 'success' ? 'bg-green-500' :
                    type === 'error' ? 'bg-red-500' :
                    type === 'warning' ? 'bg-yellow-500' :
                    'bg-blue-500'
                }`;
                
                toast.innerHTML = `
                    <div class="flex-1">${message}</div>
                    <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                `;
                
                container.appendChild(toast);
                
                setTimeout(() => {
                    toast.classList.add('opacity-0');
                    setTimeout(() => toast.remove(), 300);
                }, duration);
            }
        </script>

        <style>
            @keyframes slide-in {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            .animate-slide-in {
                animation: slide-in 0.3s ease-out;
            }
        </style>
    </body>
</html>