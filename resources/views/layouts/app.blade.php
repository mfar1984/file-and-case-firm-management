<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title . ' - ' . config('app.name', 'Laravel') : config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        
        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Alpine.js x-cloak CSS -->
        <style>
            [x-cloak] { display: none !important; }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-50 flex">
            <!-- Sidebar -->
            @component('components.sidebar')
            @endcomponent

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Navigation -->
                <header class="bg-white shadow-lg z-10">
                    <div class="flex justify-between items-center px-6 py-3">
                        <!-- Welcome & Date/Time -->
                        <div class="flex items-center">
                            <div class="text-xs">
                                <span class="font-medium">Welcome, {{ Auth::user()->name }}</span>
                                <span class="mx-2 text-gray-400">|</span>
                                <span id="current-date-time" class="text-gray-500"></span>
                            </div>
                        </div>

                        <!-- Notifications & User Menu -->
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            <div class="flex items-center justify-center h-8" x-data="{ showNotifications: false, notifications: [], unreadCount: 0 }" id="notification-container">
                                <button @click="showNotifications = !showNotifications" class="text-gray-500 hover:text-gray-700 flex items-center justify-center relative">
                                    <span class="material-icons text-xl">notifications</span>
                                    <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute -top-2 -right-2 h-5 w-5 rounded-full bg-red-500 text-white text-xs flex items-center justify-center"></span>
                                </button>
                                
                                <!-- Notifications Dropdown -->
                                <div x-show="showNotifications" @click.away="showNotifications = false" class="absolute right-16 top-16 mt-2 w-80 bg-white rounded-md shadow-lg z-20 border border-gray-200 overflow-hidden">
                                    <div class="py-2 px-3 bg-gray-100 border-b border-gray-200 flex justify-between items-center">
                                        <h3 class="text-xs font-semibold text-gray-700">Notifications</h3>
                                        <span x-show="unreadCount > 0" @click="window.markAllAsRead()" class="text-xs text-blue-500 hover:text-blue-700 cursor-pointer">Mark all as read</span>
                                    </div>
                                    
                                    <div class="max-h-64 overflow-y-auto">
                                        <template x-if="notifications.length === 0">
                                            <div class="py-4 px-3 text-center text-gray-500 text-xs">
                                                <p>No new notifications</p>
                                            </div>
                                        </template>
                                        
                                        <template x-for="notification in notifications" :key="notification.id">
                                            <a :href="notification.url" class="block py-2 px-3 hover:bg-gray-50 border-b border-gray-100 transition duration-150 ease-in-out" :class="{'bg-blue-50': !notification.read_at}">
                                                <div class="flex items-start">
                                                    <div class="flex-shrink-0 mr-2">
                                                        <span class="material-icons text-blue-600" x-text="notification.icon || 'forum'"></span>
                                                    </div>
                                                    <div class="flex-grow">
                                                        <p class="text-xs font-medium" x-text="notification.title"></p>
                                                        <p class="text-xs text-gray-500" x-text="notification.message"></p>
                                                        <p class="text-xs text-gray-400 mt-1" x-text="notification.time"></p>
                                                    </div>
                                                    <div x-show="!notification.read_at" class="flex-shrink-0 ml-2">
                                                        <span class="h-2 w-2 rounded-full bg-blue-500 inline-block"></span>
                                                    </div>
                                                </div>
                                            </a>
                                        </template>
                                    </div>
                                    
                                    <a href="#" class="block text-center py-2 text-xs text-blue-600 hover:bg-gray-50 font-medium">
                                        View all notifications
                                    </a>
                                </div>
                            </div>

                            <!-- User Menu -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center text-xs font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition duration-150 ease-in-out">
                                    <div class="flex items-center space-x-2">
                                        <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                            <span class="material-icons text-xs text-gray-500">person</span>
                                        </div>
                                        <div class="hidden md:flex">
                                            <span class="text-xs">{{ Auth::user()->name }}</span>
                                        </div>
                                    </div>

                                    <div class="ml-1">
                                        <svg class="fill-current h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                                
                                <!-- Dropdown Menu -->
                                <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-20 border border-gray-200 py-2">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">
                                        <div class="flex items-center space-x-2">
                                            <span class="material-icons text-xs">account_circle</span>
                                            <span class="text-xs">Profile</span>
                                        </div>
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">
                                            <div class="flex items-center space-x-2">
                                                <span class="material-icons text-xs">logout</span>
                                                <span class="text-xs">Log Out</span>
                                            </div>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Breadcrumb -->
                    <div class="px-6 py-2 bg-gray-50 border-t border-gray-100">
                        <nav class="flex items-center text-xs">
                            <a href="{{ route('dashboard') }}" class="text-blue-600 flex items-center">
                                <span class="material-icons text-xs">home</span>
                                <span class="ml-1">Home</span>
                            </a>
                            @hasSection('breadcrumb')
                                <span class="mx-2 text-gray-500">></span>
                                @yield('breadcrumb')
                            @endif
                        </nav>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
                    @yield('content')
                </main>
            </div>
        </div>

        <script>
            // Update current date and time
            function updateDateTime() {
                const now = new Date();
                const options = { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                };
                document.getElementById('current-date-time').textContent = now.toLocaleDateString('en-US', options);
            }
            
            // Update every second
            setInterval(updateDateTime, 1000);
            updateDateTime();
        </script>
    </body>
</html>
