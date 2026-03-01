<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BPBD') }}</title>

        <!-- Fonts: Outfit -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Leaflet CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Leaflet JS -->
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <!-- Axios for API calls -->
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

        <style>
            body {
                font-family: 'Outfit', sans-serif;
            }
            .glass {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.3);
            }
            .glass-dark {
                background: rgba(17, 24, 39, 0.8);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.05);
            }
            .gradient-bg {
                background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            }
            /* Custom Scrollbar */
            ::-webkit-scrollbar {
                width: 8px;
            }
            ::-webkit-scrollbar-track {
                background: #f1f1f1;
            }
            ::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 4px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }
        </style>
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-50 selection:bg-indigo-500 selection:text-white">
        <div class="min-h-screen flex flex-col relative">
            <!-- Background Decoration -->
            <div class="fixed top-0 left-0 w-full h-full z-[-1] opacity-40 pointer-events-none">
                <div class="absolute top-[-10%] right-[-5%] w-[500px] h-[500px] rounded-full bg-blue-400 blur-[120px]"></div>
                <div class="absolute bottom-[-10%] left-[-5%] w-[400px] h-[400px] rounded-full bg-indigo-400 blur-[100px]"></div>
            </div>

            <livewire:layout.navigation />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="glass shadow-sm sticky top-0 z-40 transition-all duration-200">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="flex-grow flex flex-col py-6">
                {{ $slot }}
            </main>
            
            <x-footer />
        </div>
        
        {{-- Page-specific scripts (e.g. for KPI dashboard) --}}
        @stack('scripts')
    </body>
</html>
