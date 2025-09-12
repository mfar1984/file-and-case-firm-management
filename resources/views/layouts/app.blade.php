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
        <script>
            // Suppress benign Alpine.js cancelled transition warnings in console
            window.addEventListener('unhandledrejection', function(e) {
                if (e && e.reason && e.reason.isFromCancelledTransition) {
                    e.preventDefault();
                }
            });
        </script>


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

            /* Ensure Material Icons render properly */
            .material-icons {
                font-family: 'Material Icons';
                font-weight: normal;
                font-style: normal;
                font-size: 24px;
                line-height: 1;
                letter-spacing: normal;
                text-transform: none;
                display: inline-block;
                white-space: nowrap;
                word-wrap: normal;
                direction: ltr;
                -webkit-font-feature-settings: 'liga';
                -webkit-font-smoothing: antialiased;
            }
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
                                <div class="relative" x-data="notificationDropdown()" x-init="loadNotifications()" id="mobile-notification-container">
                                    <button @click="showNotifications = !showNotifications" class="p-1 text-gray-500 hover:text-gray-700 relative">
                                        <span class="material-icons text-xl">notifications</span>
                                        <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute -top-1 -right-1 h-3 w-3 rounded-full bg-red-500 text-white text-[9px] flex items-center justify-center font-medium"></span>
                                    </button>

                                    <!-- Mobile Notifications Dropdown -->
                                    <div x-show="showNotifications" @click.away="showNotifications = false" class="absolute right-0 top-10 mt-1 w-72 bg-white rounded-md shadow-lg z-20 border border-gray-200 overflow-hidden">
                                        <div class="py-2 px-3 bg-gray-100 border-b border-gray-200 flex justify-between items-center">
                                            <h3 class="text-xs font-semibold text-gray-700">Notifications</h3>
                                            <span x-show="unreadCount > 0" @click="markAllAsRead()" class="text-xs text-blue-500 hover:text-blue-700 cursor-pointer">Mark all as read</span>
                                        </div>

                                        <div class="max-h-48 overflow-y-auto">
                                            <template x-if="notifications.length === 0">
                                                <div class="py-4 px-3 text-center text-gray-500 text-xs">
                                                    <p>No new notifications</p>
                                                </div>
                                            </template>

                                            <template x-for="notification in notifications" :key="notification.id">
                                                <a :href="notification.url" @click="markAsRead(notification.id)" class="block py-2 px-3 hover:bg-gray-50 border-b border-gray-100 transition duration-150 ease-in-out" :class="{'bg-blue-50': !notification.read_at}">
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

                                        <button @click="showNotifications = false" class="block w-full text-center py-2 text-xs text-blue-600 hover:bg-gray-50 font-medium">
                                            Close notifications
                                        </button>
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
                                <div class="flex items-center justify-center h-8" x-data="notificationDropdown()" x-init="loadNotifications()" id="notification-container">
                                    <button @click="showNotifications = !showNotifications" class="text-gray-500 hover:text-gray-700 flex items-center justify-center relative">
                                        <span class="material-icons text-xl">notifications</span>
                                        <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute -top-1 -right-1 h-3 w-3 rounded-full bg-red-500 text-white text-[9px] flex items-center justify-center font-medium"></span>
                                    </button>

                                    <!-- Desktop Notifications Dropdown -->
                                    <div x-show="showNotifications" @click.away="showNotifications = false" class="absolute right-16 top-16 mt-2 w-80 bg-white rounded-md shadow-lg z-20 border border-gray-200 overflow-hidden">
                                        <div class="py-2 px-3 bg-gray-100 border-b border-gray-200 flex justify-between items-center">
                                            <h3 class="text-xs font-semibold text-gray-700">Notifications</h3>
                                            <span x-show="unreadCount > 0" @click="markAllAsRead()" class="text-xs text-blue-500 hover:text-blue-700 cursor-pointer">Mark all as read</span>
                                        </div>

                                        <div class="max-h-64 overflow-y-auto">
                                            <template x-if="notifications.length === 0">
                                                <div class="py-4 px-3 text-center text-gray-500 text-xs">
                                                    <p>No new notifications</p>
                                                </div>
                                            </template>

                                            <template x-for="notification in notifications" :key="notification.id">
                                                <a :href="notification.url" @click="markAsRead(notification.id)" class="block py-2 px-3 hover:bg-gray-50 border-b border-gray-100 transition duration-150 ease-in-out" :class="{'bg-blue-50': !notification.read_at}">
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

                                        <div class="border-t border-gray-200 flex">
                                            <button @click="showNotifications = false" class="flex-1 text-center py-2 text-xs text-blue-600 hover:bg-gray-50 font-medium">
                                                Close notifications
                                            </button>
                                            <button @click="toggleSound()" class="px-3 py-2 text-xs text-gray-500 hover:bg-gray-50 flex items-center border-l border-gray-200">
                                                <span class="material-icons text-xs mr-1" x-text="soundEnabled ? 'volume_up' : 'volume_off'"></span>
                                                <span x-text="soundEnabled ? 'On' : 'Off'"></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Firm Selector (Super Administrator only) -->
                                @auth
                                    @if(auth()->user()->hasRole('Super Administrator'))
                                        <div class="relative" x-data="{ firmOpen: false }">
                                            <button @click="firmOpen = !firmOpen" class="flex items-center text-xs font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition duration-150 ease-in-out">
                                                <div class="flex items-center space-x-2">
                                                    <div class="h-8 w-8 rounded-base bg-blue-100 flex items-center justify-center">
                                                        <span class="material-icons text-xs text-blue-600">business</span>
                                                    </div>
                                                    <div class="hidden md:flex flex-col items-start">
                                                        <span class="text-xs font-medium">
                                                            @if(isset($currentFirm))
                                                                {{ $currentFirm->name }}
                                                            @else
                                                                Select Firm
                                                            @endif
                                                        </span>
                                                        <span class="text-xs text-gray-400">Switch Firm</span>
                                                    </div>
                                                </div>
                                                <div class="ml-1">
                                                    <svg class="fill-current h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            </button>

                                            <!-- Firm Dropdown Menu -->
                                            <div x-show="firmOpen" @click.away="firmOpen = false" x-cloak class="absolute right-0 mt-2 w-64 bg-white rounded-base shadow-lg z-20 border border-gray-200 py-2">
                                                <div class="px-4 py-2 border-b border-gray-200">
                                                    <h3 class="text-xs font-semibold text-gray-700">Select Firm</h3>
                                                </div>
                                                <div class="max-h-64 overflow-y-auto">
                                                    @php
                                                        $allFirms = App\Models\Firm::where('status', 'active')->orderBy('name')->get();
                                                    @endphp
                                                    @foreach($allFirms as $firm)
                                                        <form method="POST" action="{{ route('firm.switch') }}" class="block">
                                                            @csrf
                                                            <input type="hidden" name="firm_id" value="{{ $firm->id }}">
                                                            <button type="submit" class="w-full text-left px-4 py-2 text-xs hover:bg-gray-100 transition-colors
                                                                {{ (isset($currentFirm) && $currentFirm->id === $firm->id) ? 'bg-blue-50 text-blue-700' : 'text-gray-700' }}">
                                                                <div class="flex items-center space-x-3">
                                                                    @if($firm->logo)
                                                                        <img src="{{ Storage::url($firm->logo) }}" alt="{{ $firm->name }}" class="h-6 w-6 rounded-base object-cover">
                                                                    @else
                                                                        <div class="h-6 w-6 rounded-base bg-gray-200 flex items-center justify-center">
                                                                            <span class="material-icons text-xs text-gray-400">business</span>
                                                                        </div>
                                                                    @endif
                                                                    <div class="flex-1">
                                                                        <div class="font-medium">{{ $firm->name }}</div>
                                                                        @if($firm->registration_number)
                                                                            <div class="text-gray-500">{{ $firm->registration_number }}</div>
                                                                        @endif
                                                                    </div>
                                                                    @if(isset($currentFirm) && $currentFirm->id === $firm->id)
                                                                        <span class="material-icons text-xs text-blue-600">check</span>
                                                                    @endif
                                                                </div>
                                                            </button>
                                                        </form>
                                                    @endforeach
                                                </div>
                                                <div class="border-t border-gray-200 pt-2">
                                                    <a href="{{ route('settings.firms.index') }}" class="block px-4 py-2 text-xs text-blue-600 hover:bg-gray-100">
                                                        <div class="flex items-center space-x-2">
                                                            <span class="material-icons text-xs">settings</span>
                                                            <span>Manage Firms</span>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endauth

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
                                fetch('/settings/weather/get', {
                                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                                        credentials: 'same-origin'
                                    })
                                    .then(response => {
                                        if (response.status === 401) {
                                            // Session expired; avoid redirecting login intent to this endpoint
                                            return null;
                                        }
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
                                            <span class="text-gray-400">Fallback</span>
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

        <!-- Notification System JavaScript -->
        <script>
            // Notification dropdown Alpine.js component
            function notificationDropdown() {
                return {
                    showNotifications: false,
                    notifications: [],
                    unreadCount: 0,
                    loading: false,
                    soundEnabled: localStorage.getItem('notificationSound') !== 'false',

                    async loadNotifications() {
                        const previousUnreadCount = this.unreadCount;
                        this.loading = true;

                        try {
                            const response = await fetch('/notifications', {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                credentials: 'same-origin'
                            });
                            const data = await response.json();
                            this.notifications = data.notifications;
                            this.unreadCount = data.unreadCount;

                            // Play sound and show browser notification if new notifications arrived
                            if (this.unreadCount > previousUnreadCount && previousUnreadCount >= 0) {
                                if (this.soundEnabled) {
                                    this.playNotificationSound();
                                }

                                // Show browser notification for new notifications
                                const newNotifications = this.notifications.filter(n => !n.read_at);
                                if (newNotifications.length > 0) {
                                    this.showBrowserNotification(newNotifications[0]);
                                }
                            }
                        } catch (error) {
                            console.error('Failed to load notifications:', error);
                        } finally {
                            this.loading = false;
                        }
                    },

                    playNotificationSound() {
                        try {
                            // Create a subtle notification sound
                            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                            const oscillator = audioContext.createOscillator();
                            const gainNode = audioContext.createGain();

                            oscillator.connect(gainNode);
                            gainNode.connect(audioContext.destination);

                            oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
                            oscillator.frequency.setValueAtTime(600, audioContext.currentTime + 0.1);

                            gainNode.gain.setValueAtTime(0, audioContext.currentTime);
                            gainNode.gain.linearRampToValueAtTime(0.1, audioContext.currentTime + 0.01);
                            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);

                            oscillator.start(audioContext.currentTime);
                            oscillator.stop(audioContext.currentTime + 0.2);
                        } catch (error) {
                            // Audio not supported or blocked - silently fail
                        }
                    },

                    toggleSound() {
                        this.soundEnabled = !this.soundEnabled;
                        localStorage.setItem('notificationSound', this.soundEnabled.toString());

                        // Play test sound if enabling
                        if (this.soundEnabled) {
                            this.playNotificationSound();
                        }
                    },

                    async showBrowserNotification(notification) {
                        // Request permission if not granted
                        if (Notification.permission === 'default') {
                            await Notification.requestPermission();
                        }

                        // Show notification if permission granted
                        if (Notification.permission === 'granted') {
                            const browserNotification = new Notification(notification.title, {
                                body: notification.message,
                                icon: '/favicon.ico',
                                badge: '/favicon.ico',
                                tag: 'calendar-reminder',
                                requireInteraction: false, // Changed to false to avoid issues
                                // Removed actions as they're only supported for Service Worker notifications
                            });

                            // Handle notification click
                            browserNotification.onclick = () => {
                                window.focus();
                                window.location.href = notification.url;
                                browserNotification.close();
                            };

                            // Auto-close after 10 seconds
                            setTimeout(() => {
                                browserNotification.close();
                            }, 10000);
                        }
                    },

                    async markAllAsRead() {
                        try {
                            const response = await fetch('/notifications/mark-all-read', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                },
                            });

                            if (response.ok) {
                                this.notifications.forEach(n => n.read_at = new Date().toISOString());
                                this.unreadCount = 0;
                            }
                        } catch (error) {
                            console.error('Failed to mark all as read:', error);
                        }
                    },

                    async markAsRead(notificationId) {
                        try {
                            const response = await fetch(`/notifications/${notificationId}/read`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                },
                            });

                            if (response.ok) {
                                const notification = this.notifications.find(n => n.id === notificationId);
                                if (notification && !notification.read_at) {
                                    notification.read_at = new Date().toISOString();
                                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                                }
                            }
                        } catch (error) {
                            console.error('Failed to mark as read:', error);
                        }
                    }
                }
            }

            // Auto-refresh notifications every 30 seconds
            setInterval(() => {
                // Refresh desktop notifications
                const container = document.getElementById('notification-container');
                if (container && container.__x) {
                    container.__x.$data.loadNotifications();
                }

                // Refresh mobile notifications
                const mobileContainer = document.getElementById('mobile-notification-container');
                if (mobileContainer && mobileContainer.__x) {
                    mobileContainer.__x.$data.loadNotifications();
                }
            }, 30000);

            // Listen for page visibility changes to refresh when user returns
            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) {
                    // Refresh desktop notifications
                    const container = document.getElementById('notification-container');
                    if (container && container.__x) {
                        container.__x.$data.loadNotifications();
                    }

                    // Refresh mobile notifications
                    const mobileContainer = document.getElementById('mobile-notification-container');
                    if (mobileContainer && mobileContainer.__x) {
                        mobileContainer.__x.$data.loadNotifications();
                    }
                }
            });

            // Global function for backward compatibility
            window.markAllAsRead = function() {
                // Mark all read for desktop
                const container = document.getElementById('notification-container');
                if (container && container.__x) {
                    container.__x.$data.markAllAsRead();
                }

                // Mark all read for mobile
                const mobileContainer = document.getElementById('mobile-notification-container');
                if (mobileContainer && mobileContainer.__x) {
                    mobileContainer.__x.$data.markAllAsRead();
                }
            };
        </script>

        <!-- Session Flash Messages -->
        @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Create custom toast notification instead of alert
                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded text-xs z-50 transition-opacity duration-300';
                toast.style.fontSize = '11px';
                toast.style.lineHeight = '1.2';
                toast.style.minHeight = '32px';
                toast.style.display = 'flex';
                toast.style.alignItems = 'center';
                toast.textContent = '{{ session('success') }}';

                document.body.appendChild(toast);

                // Auto remove after 3 seconds
                setTimeout(() => {
                    toast.style.opacity = '0';
                    setTimeout(() => {
                        if (toast.parentNode) {
                            document.body.removeChild(toast);
                        }
                    }, 300);
                }, 3000);
            });
        </script>
        @endif

        @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Create custom toast notification for errors
                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded text-xs z-50 transition-opacity duration-300';
                toast.style.fontSize = '11px';
                toast.style.lineHeight = '1.2';
                toast.style.minHeight = '32px';
                toast.style.display = 'flex';
                toast.style.alignItems = 'center';
                toast.textContent = '{{ session('error') }}';

                document.body.appendChild(toast);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    toast.style.opacity = '0';
                    setTimeout(() => {
                        if (toast.parentNode) {
                            document.body.removeChild(toast);
                        }
                    }, 300);
                }, 5000);
            });
        </script>
        @endif
    </body>
</html>
