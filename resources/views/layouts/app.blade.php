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

        <!-- Tagify CSS and JS -->
        <link href="https://unpkg.com/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
        <script src="https://unpkg.com/@yaireo/tagify"></script>

        <!-- Custom Tagify Styling to match Tailwind input exactly like Section -->
        <style>
            .tagify {
                width: 100%;
                border-radius: 0.375rem;
                border: 1px solid #d1d5db;
                font-size: 0.75rem;
                padding: 0.5rem 0.75rem;
                min-height: 2.25rem;
                background: #fff;
                box-shadow: none;
                transition: border-color 0.2s, box-shadow 0.2s;
            }
            .tagify__input, .tagify__input:focus {
                font-size: 0.75rem !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .tagify__tag {
                font-size: 0.75rem !important;
                margin: 0 2px 0 0 !important;
                padding: 0.1rem 0.5rem !important;
            }
            .tagify--focus {
                box-shadow: 0 0 0 2px #a78bfa33;
                border-color: #a78bfa;
            }
            /* Ensure Tagify dropdown and items use text-xs (0.75rem) */
            .tagify__dropdown, .tagify__dropdown__item {
                font-size: 0.75rem !important;
            }
        </style>

        <!-- Alpine.js x-cloak CSS -->
        <style>
            [x-cloak] { display: none !important; }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-50 flex" x-data="{ sidebarOpen: false }">
            <!-- Sidebar Overlay (Mobile) -->
            <div x-show="sidebarOpen" x-cloak class="fixed inset-0 bg-black bg-opacity-40 z-40 md:hidden" @click="sidebarOpen = false"></div>

            <!-- Sidebar -->
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed z-50 inset-y-0 left-0 w-64 bg-white shadow-lg border-r border-gray-200 flex flex-col transform transition-transform duration-200 md:relative md:translate-x-0 md:flex md:w-64">
                @component('components.sidebar')
                @endcomponent
                
                <!-- Close button for mobile -->
                <button @click="sidebarOpen = false" class="absolute top-4 right-4 md:hidden">
                    <span class="material-icons text-gray-500 hover:text-gray-700">close</span>
                </button>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Navigation -->
                <header class="bg-white shadow-lg z-10">
                    <!-- Mobile Header -->
                    <div class="md:hidden">
                        <div class="flex justify-between items-center px-4 py-3">
                            <!-- Hamburger Menu -->
                            <button @click="sidebarOpen = true" class="p-1">
                                <span class="material-icons text-2xl text-gray-600 hover:text-gray-800">menu</span>
                            </button>
                            
                            <!-- Page Title (from breadcrumb) -->
                            <div class="flex-1 text-center">
                                <h1 class="text-sm font-semibold text-gray-800 truncate">
                                    @hasSection('breadcrumb')
                                        @yield('breadcrumb')
                                    @else
                                        Dashboard
                                    @endif
                                </h1>
                            </div>
                            
                            <!-- Mobile Actions -->
                            <div class="flex items-center space-x-2">
                                <!-- Notifications (Mobile) -->
                                <div class="relative" x-data="{ showNotifications: false, notifications: [], unreadCount: 0 }">
                                    <button @click="showNotifications = !showNotifications" class="p-1 text-gray-500 hover:text-gray-700 relative">
                                        <span class="material-icons text-xl">notifications</span>
                                        <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute -top-1 -right-1 h-4 w-4 rounded-full bg-red-500 text-white text-xs flex items-center justify-center"></span>
                                    </button>
                                    
                                    <!-- Mobile Notifications Dropdown -->
                                    <div x-show="showNotifications" @click.away="showNotifications = false" class="absolute right-0 top-10 mt-1 w-72 bg-white rounded-md shadow-lg z-20 border border-gray-200 overflow-hidden">
                                        <div class="py-2 px-3 bg-gray-100 border-b border-gray-200 flex justify-between items-center">
                                            <h3 class="text-xs font-semibold text-gray-700">Notifications</h3>
                                            <span x-show="unreadCount > 0" @click="window.markAllAsRead()" class="text-xs text-blue-500 hover:text-blue-700 cursor-pointer">Mark all as read</span>
                                        </div>
                                        
                                        <div class="max-h-48 overflow-y-auto">
                                            <template x-if="notifications.length === 0">
                                                <div class="py-4 px-3 text-center text-gray-500 text-xs">
                                                    <p>No new notifications</p>
                                                </div>
                                            </template>
                                            
                                            <template x-for="notification in notifications" :key="notification.id">
                                                <a :href="notification.url" class="block py-2 px-3 hover:bg-gray-50 border-b border-gray-100 transition duration-150 ease-in-out" :class="{'bg-blue-50': !notification.read_at}">
                                                    <div class="flex items-start">
                                                        <div class="flex-shrink-0 mr-2">
                                                            <span class="material-icons text-blue-600 text-sm" x-text="notification.icon || 'forum'"></span>
                                                        </div>
                                                        <div class="flex-grow min-w-0">
                                                            <p class="text-xs font-medium truncate" x-text="notification.title"></p>
                                                            <p class="text-xs text-gray-500 truncate" x-text="notification.message"></p>
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
                                
                                <!-- User Menu (Mobile) -->
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="p-1 text-gray-500 hover:text-gray-700">
                                        <span class="material-icons text-xl">account_circle</span>
                                    </button>
                                    
                                    <!-- Mobile User Dropdown -->
                                    <div x-show="open" @click.away="open = false" class="absolute right-0 top-10 mt-1 w-48 bg-white rounded-md shadow-lg z-20 border border-gray-200 overflow-hidden">
                                        <div class="py-2 px-3 bg-gray-100 border-b border-gray-200">
                                            <p class="text-xs font-semibold text-gray-700">
                                                @auth
                                                    {{ Auth::user()->name }}
                                                @else
                                                    Guest
                                                @endauth
                                            </p>
                                        </div>
                                        
                                        <div class="py-1">
                                            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-xs text-gray-700 hover:bg-gray-50">
                                                <span class="material-icons text-sm mr-2">person</span>
                                                Profile
                                            </a>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="block w-full text-left px-3 py-2 text-xs text-gray-700 hover:bg-gray-50">
                                                    <span class="material-icons text-sm mr-2">logout</span>
                                                    Logout
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Desktop Header -->
                    <div class="hidden md:block">
                        <div class="flex justify-between items-center px-6 py-3">
                            <!-- Welcome & Date/Time -->
                            <div class="flex items-center">
                                <div class="text-xs">
                                    @auth
                                        <span class="font-medium">Welcome, {{ Auth::user()->name }}</span>
                                        <span class="mx-2 text-gray-400">|</span>
                                    @else
                                        <span class="font-medium">Welcome, Guest</span>
                                        <span class="mx-2 text-gray-400">|</span>
                                    @endauth
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
                                    
                                    <!-- Desktop Notifications Dropdown -->
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
                                                @auth
                                                    <span class="text-xs">{{ Auth::user()->name }}</span>
                                                @else
                                                    <span class="text-xs">Guest</span>
                                                @endauth
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
                                        @auth
                                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">
                                                <div class="flex items-center space-x-2">
                                                    <span class="material-icons text-xs">account_circle</span>
                                                    <span class="text-xs">Profile</span>
                                                </div>
                                            </a>
                                        @endauth
                                        @auth
                                            <form method="POST" action="{{ route('logout') }}" class="block">
                                                @csrf
                                                <button type="submit" class="w-full text-left px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">
                                                    <div class="flex items-center space-x-2">
                                                        <span class="material-icons text-xs">logout</span>
                                                        <span class="text-xs">Log Out</span>
                                                    </div>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('login') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">
                                                <div class="flex items-center space-x-2">
                                                    <span class="material-icons text-xs">login</span>
                                                    <span class="text-xs">Log In</span>
                                                </div>
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Breadcrumb & Weather Section -->
                    <div class="bg-gray-50 border-t border-gray-100 px-6" style="padding-top: 6px; padding-bottom: 6.5px;">
                        <div class="flex justify-between items-center">
                            <!-- Breadcrumb (Desktop Only) -->
                            <nav class="hidden md:flex items-center text-xs">
                                <a href="{{ route('dashboard') }}" class="text-blue-600 flex items-center hover:text-blue-800">
                                    <span class="material-icons text-xs">home</span>
                                    <span class="ml-1">Home</span>
                                </a>
                                @hasSection('breadcrumb')
                                    <span class="mx-2 text-gray-500">></span>
                                    <span class="text-gray-700">@yield('breadcrumb')</span>
                                @endif
                            </nav>
                            
                            <!-- Weather Widget (Desktop Only) -->
                            <div class="hidden md:block relative" x-data="{ weather: null, loading: true, showTooltip: false, locationInfo: null }" x-init="
                                // Fetch weather data with error handling
                                fetch('/api/weather')
                                    .then(response => {
                                        if (!response.ok) {
                                            throw new Error('Weather API not available');
                                        }
                                        return response.json();
                                    })
                                    .then(data => {
                                        weather = data;
                                        loading = false;
                                    })
                                    .catch(error => {
                                        console.warn('Weather fetch error:', error.message);
                                        loading = false;
                                    });
                                
                                // Fetch location info from settings with error handling
                                fetch('/settings/weather/get')
                                    .then(response => {
                                        if (!response.ok) {
                                            throw new Error('Location API not available');
                                        }
                                        return response.json();
                                    })
                                    .then(data => {
                                        if (data) {
                                            locationInfo = data;
                                        }
                                    })
                                    .catch(error => {
                                        console.warn('Location info fetch error:', error.message);
                                    });
                            ">
                                <div class="flex items-center space-x-2 text-xs cursor-pointer" 
                                     @mouseenter="showTooltip = true" 
                                     @mouseleave="showTooltip = false">
                                    <span class="material-icons text-blue-600 text-sm">wb_sunny</span>
                                    <div x-show="!loading && weather" class="flex items-center space-x-1">
                                        <span x-text="weather?.location || 'Unknown Location'" class="font-medium text-gray-700"></span>
                                        <span x-text="weather?.current?.temperature || 'N/A'" class="text-gray-600"></span>
                                        <span x-text="weather?.current?.skytext || 'N/A'" class="text-gray-500"></span>
                                    </div>
                                    <div x-show="loading" class="flex items-center space-x-1">
                                        <span class="text-gray-500">Loading weather...</span>
                                    </div>
                                    <div x-show="!loading && !weather" class="flex items-center space-x-1">
                                        <span class="text-gray-500">Weather unavailable</span>
                                    </div>
                                </div>
                                
                                <!-- Weather Tooltip -->
                                <div x-show="showTooltip && weather" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50 p-4">
                                    
                                    <!-- Location Info -->
                                    <div class="mb-4 pb-3 border-b border-gray-200">
                                        <div class="flex items-center justify-between mb-2">
                                            <h3 class="text-sm font-semibold text-gray-800" x-text="weather?.location || 'Unknown Location'"></h3>
                                            <span class="text-xs text-gray-500" x-text="weather?.source === 'webhook' ? 'Webhook Data' : weather?.source === 'api' ? 'API Data' : 'Fallback Data'"></span>
                                        </div>
                                        <div x-show="locationInfo" class="text-xs text-gray-600 space-y-1">
                                            <div x-show="locationInfo?.city" class="flex justify-between">
                                                <span>City:</span>
                                                <span x-text="locationInfo?.city || 'N/A'" class="font-medium"></span>
                                            </div>
                                            <div x-show="locationInfo?.state" class="flex justify-between">
                                                <span>State:</span>
                                                <span x-text="locationInfo?.state || 'N/A'" class="font-medium"></span>
                                            </div>
                                            <div x-show="locationInfo?.postcode" class="flex justify-between">
                                                <span>Postcode:</span>
                                                <span x-text="locationInfo?.postcode || 'N/A'" class="font-medium"></span>
                                            </div>
                                            <div x-show="locationInfo?.country" class="flex justify-between">
                                                <span>Country:</span>
                                                <span x-text="locationInfo?.country || 'N/A'" class="font-medium"></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Current Weather -->
                                    <div class="mb-4">
                                        <h3 class="text-sm font-semibold text-gray-800 mb-2">Current Weather</h3>
                                        <div class="grid grid-cols-2 gap-3 text-xs">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Temperature:</span>
                                                <span x-text="weather?.current?.temperature || 'N/A'" class="font-medium"></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Feels Like:</span>
                                                <span x-text="weather?.current?.feels_like || 'N/A'" class="font-medium"></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Humidity:</span>
                                                <span x-text="weather?.current?.humidity || 'N/A'" class="font-medium"></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Wind:</span>
                                                <span x-text="weather?.current?.winddisplay || 'N/A'" class="font-medium"></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Pressure:</span>
                                                <span x-text="weather?.current?.pressure || 'N/A'" class="font-medium"></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Visibility:</span>
                                                <span x-text="weather?.current?.visibility || 'N/A'" class="font-medium"></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Forecast -->
                                    <div class="mb-4" x-show="weather?.forecast">
                                        <h3 class="text-sm font-semibold text-gray-800 mb-2">3-Day Forecast</h3>
                                        <div class="space-y-2">
                                            <template x-for="(day, index) in weather?.forecast || []" :key="index">
                                                <div class="flex justify-between items-center text-xs">
                                                    <span x-text="day?.day || 'N/A'" class="font-medium"></span>
                                                    <span class="text-gray-500 ml-4" x-text="day?.condition || 'N/A'"></span>
                                                    <div class="flex space-x-2">
                                                        <span class="text-red-500 font-medium" x-text="day?.high || 'N/A'"></span>
                                                        <span class="text-blue-500 font-medium" x-text="day?.low || 'N/A'"></span>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    
                                    <!-- Footer -->
                                    <div class="pt-3 border-t border-gray-200">
                                        <div class="flex justify-between items-center text-xs">
                                            <span x-text="weather?.source === 'webhook' ? '📡 Webhook' : weather?.source === 'api' ? '🌐 API' : '🔄 Fallback'"></span>
                                        </div>
                                        <div x-show="weather?.received_at" class="text-xs text-gray-400 text-center mt-1">
                                            <span x-text="'Received: ' + (weather?.received_at ? new Date(weather.received_at).toLocaleString() : 'N/A')"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

            <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
                    @yield('content')
            </main>
            </div>
        </div>

        <script>
            // Simple date and time formatting for topbar
            function updateDateTime() {
                const now = new Date();
                
                // Format: "Wednesday, 6 August 2025 at 02:00:00 am"
                const dayName = now.toLocaleString('en-US', { weekday: 'long' });
                const day = now.getDate();
                const month = now.toLocaleString('en-US', { month: 'long' });
                const year = now.getFullYear();
                
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                const ampm = now.getHours() < 12 ? 'am' : 'pm';
                
                const formattedDateTime = `${dayName}, ${day} ${month} ${year} at ${hours}:${minutes}:${seconds} ${ampm}`;
                document.getElementById('current-date-time').textContent = formattedDateTime;
            }
            
            // Initialize and update every second
            updateDateTime();
            setInterval(updateDateTime, 1000);
        </script>
    </body>
</html>
