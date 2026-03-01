<div class="space-y-8">
    {{-- Welcome Section --}}
    <div class="text-center space-y-4 py-6">
        <h1
            class="text-2xl sm:text-4xl md:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-500 ">
            Pantau Bencana Terkini
        </h1>
        <p class="text-sm sm:text-base md:text-lg text-gray-600 max-w-2xl mx-auto">
            Sistem informasi Manajemen Bencana secara real-time.
        </p>
        <div class="pt-4 flex justify-center max-w-4xl mx-auto">
            <div class="px-3">
                <a href="{{ route('public.report.create') }}"
                    class="inline-flex items-center gap-2 bg-red-600 text-white px-8 py-4 rounded-full font-bold shadow-lg hover:bg-red-700 hover:shadow-xl transform hover:-translate-y-1 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                    Lapor Bencana
                </a>
            </div>
            <div class="px-3">
                <a href="{{ route('public.incidents') }}"
                    class="inline-flex items-center gap-2 bg-blue-600 text-white px-8 py-4 rounded-full font-bold shadow-lg hover:bg-indigo-700 hover:shadow-xl transform hover:-translate-y-1 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                        </path>
                    </svg>
                    Berita Bencana
                </a>
            </div>
        </div>
    </div>

    {{-- KPI Cards with API Integration --}}
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 max-w-6xl mx-auto" id="kpi-cards-container">
        {{-- Fallback to Livewire data if API fails or JS disabled --}}
        <noscript>
            <div
                class="glass p-6 shadow-md rounded-2xl flex items-center gap-6 transform hover:scale-105 transition-transform duration-300">
                <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Terverifikasi</p>
                    <p class="text-4xl font-bold text-gray-800">{{ number_format($totalVerified) }}</p>
                </div>
            </div>

            <div
                class="glass p-6 shadow-md rounded-2xl flex items-center gap-6 transform hover:scale-105 transition-transform duration-300">
                <div class="w-16 h-16 rounded-full bg-orange-100 flex items-center justify-center text-orange-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Laporan Hari Ini</p>
                    <p class="text-4xl font-bold text-gray-800">{{ number_format($todayReports) }}</p>
                </div>
            </div>
        </noscript>
    </div>

    {{-- JavaScript for Axios API calls --}}
    @push('scripts')
        <script>
            // Configuration
            const KPI_CONFIG = {
                apiUrl: '{{ url("/api/v1/kpi/dashboard") }}',
                refreshInterval: 300000, // 5 minutes
                timeout: 10000
            };

            // Icon mappings
            const ICON_MAP = {
                'alert-triangle': 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                'users': 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                'home': 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
            };

            const COLOR_CLASS_MAP = {
                'blue': 'bg-blue-100 text-blue-600',
                'orange': 'bg-orange-100 text-orange-600',
                'yellow': 'bg-yellow-100 text-yellow-600',
                'green': 'bg-green-100 text-green-600',
                'red': 'bg-red-100 text-red-600',
                'purple': 'bg-purple-100 text-purple-600',
            };

            /**
             * Render KPI card HTML from data
             */
            function renderKpiCard(label, value, icon = 'chart-bar', color = 'blue') {
                const colorClass = COLOR_CLASS_MAP[color] || COLOR_CLASS_MAP['blue'];
                const iconPath = ICON_MAP[icon] || ICON_MAP['check-circle'];

                return `
                    <div class="glass p-6 shadow-md rounded-2xl flex items-center gap-6 transform hover:scale-105 transition-transform duration-300">
                        <div class="w-16 h-16 rounded-full ${colorClass} flex items-center justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${iconPath}"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">${label}</p>
                            <p class="text-4xl font-bold text-gray-800">${new Intl.NumberFormat('id-ID').format(value)}</p>
                        </div>
                    </div>
                `;
            }

            /**
             * Fetch KPI data from API and update the dashboard
             */
            async function fetchAndRenderKpis() {
                const container = document.getElementById('kpi-cards-container');
                
                try {
                    console.log('Fetching KPI data from:', KPI_CONFIG.apiUrl);
                    
                    const response = await axios.get(KPI_CONFIG.apiUrl, {
                        timeout: KPI_CONFIG.timeout
                    });

                    console.log('API Response:', response);

                    if (response.data.success && response.data.data) {
                        const data = response.data.data;

                        // Build HTML from API response
                        let html = '';
                        html += renderKpiCard(
                            data.incident.label,
                            data.incident.total,
                            data.incident.icon,
                            data.incident.color
                        );
                        html += renderKpiCard(
                            data.affected_people.label,
                            data.affected_people.total,
                            data.affected_people.icon,
                            data.affected_people.color
                        );
                        html += renderKpiCard(
                            data.damaged_houses.label,
                            data.damaged_houses.total,
                            data.damaged_houses.icon,
                            data.damaged_houses.color
                        );

                        container.innerHTML = html;
                        console.log('KPI data loaded successfully from Google Sheets', data);
                    } else {
                        throw new Error('Invalid response format from API');
                    }
                } catch (error) {
                    console.error('Failed to fetch KPI data:', error);
                    console.error('Error details:', {
                        message: error.message,
                        response: error.response?.data,
                        status: error.response?.status
                    });
                    
                    // Display error in UI
                    container.innerHTML = `
                        <div class="col-span-full glass p-6 shadow-md rounded-2xl">
                            <div class="text-center space-y-2">
                                <svg class="w-12 h-12 text-red-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm font-medium text-gray-700">Gagal memuat data KPI</p>
                                <p class="text-xs text-gray-500">${error.message}</p>
                                <button onclick="window.refreshDashboardKpis()" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                                    Coba Lagi
                                </button>
                            </div>
                        </div>
                    `;
                }
            }

            /**
             * Initialize dashboard with periodic refresh
             */
            function initDashboard() {
                // Load KPIs immediately on page load
                fetchAndRenderKpis();

                // Set up periodic refresh (every 5 minutes)
                setInterval(fetchAndRenderKpis, KPI_CONFIG.refreshInterval);
            }

            // Initialize when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initDashboard);
            } else {
                initDashboard();
            }

            // Optional: Expose function for manual refresh button
            window.refreshDashboardKpis = fetchAndRenderKpis;
        </script>
    @endpush

    {{-- Looker Studio Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
        {{-- Page 1 --}}
        <div class="glass p-6 rounded-2xl space-y-4">
            <div class="flex justify-end items-center">
                <span class="font-bold text-xs text-white bg-blue-500 px-2 py-1 rounded">Live Data</span>
            </div>
            <div class="w-full aspect-video rounded-xl overflow-hidden shadow-sm bg-gray-50 border border-gray-100 relative group"
>
                <iframe
                    src="https://lookerstudio.google.com/embed/reporting/962829b6-9ae5-4b9b-b999-3bce9b8b24d8/page/2G6nF"
                    frameborder="0" style="border:0" allowfullscreen class="absolute top-0 left-0 w-full h-full"
                    title="Looker Studio Report Page 1">
                </iframe>
            </div>
        </div>

        <div class="glass p-6 rounded-2xl space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-800">Peta Infografis Bencana</h3>
                <span class="font-bold text-xs text-white bg-blue-500 px-2 py-1 rounded">Live Data</span>
            </div>
            <div class="w-full rounded-xl overflow-hidden shadow-sm bg-gray-50 border border-gray-100 relative"
                style="height: 1100px;">
                <iframe
                    src="https://lookerstudio.google.com/embed/reporting/dfd5efe1-ea9a-4acb-a3e3-74a0b68ad2ef/page/sCknF"
                    frameborder="0" style="border:0" allowfullscreen class="absolute top-0 left-0 w-full h-full"
                    title="Peta Infografis Bencana"></iframe>
            </div>
        </div>        
        
        {{-- Page 2 --}}
        <div class="glass p-6 rounded-2xl space-y-4">
            <div class="flex justify-end items-center">
                <span class="font-bold text-xs text-white bg-blue-500 px-2 py-1 rounded">Live Data</span>
            </div>
            <div
                class="w-full aspect-video rounded-xl overflow-hidden shadow-sm bg-gray-50 border border-gray-100 relative group">
                <iframe
                    src="https://lookerstudio.google.com/embed/reporting/962829b6-9ae5-4b9b-b999-3bce9b8b24d8/page/p_4ombbjzs0d"
                    frameborder="0" style="border:0" allowfullscreen class="absolute top-0 left-0 w-full h-full"
                    title="Looker Studio Report Page 2"></iframe>
            </div>
        </div>
    </div>

    {{-- Recent Reports --}}
    <div class="glass rounded-2xl overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white/50">
            <h3 class="text-xl font-bold text-gray-800">Laporan Terbaru</h3>
            <a href="{{ route('public.incidents') }}"
                class="text-sm font-medium text-blue-600 hover:text-blue-800">Lihat Semua &rarr;</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentReports as $report)
                <div class="p-6 hover:bg-white/40 transition-colors flex flex-col md:flex-row gap-4">
                    <div class="w-full md:w-32 h-20 bg-gray-200 rounded-lg flex-shrink-0 overflow-hidden">
                        {{-- Validasi relation attachment jika ada, jika tidak placeholder --}}
                        @php $thumb = $report->attachments->first(); @endphp
                        @if($thumb)
                            <img src="{{ Storage::url($thumb->file_path) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">

                                    </path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 space-y-1">
                        <div class="flex justify-between items-start">
                            <span
                                class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">{{ $report->disasterType->slug }}</span>
                            <span class="text-xs text-gray-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $report->occurred_at->diffForHumans() }}
                            </span>
                        </div>
                        <a href="#"
                            class="block text-lg font-bold text-gray-800 hover:text-blue-600 transition-colors">{{ $report->title }}</a>
                        <p class="text-sm text-gray-600 line-clamp-2">{{ $report->location_text }}</p>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-gray-500">
                    Belum ada laporan terverifikasi.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Disaster Map --}}
    <div class="glass p-6 rounded-2xl space-y-4">
        <div class="flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800">Peta Sebaran Bencana</h3>
            <span class="font-bold text-xs text-white bg-blue-500 px-2 py-1 rounded">Live Data</span>
        </div>
        <div class="w-full rounded-xl overflow-hidden shadow-sm bg-gray-50 border border-gray-100 relative"
            style="height: 1100px;">
            <iframe
                src="https://lookerstudio.google.com/embed/reporting/1a4a1d44-2162-41a4-803e-77e5c34f5d5a/page/5S4nF"
                frameborder="0" style="border:0" allowfullscreen class="absolute top-0 left-0 w-full h-full"
                title="Peta Infografis Bencana"></iframe>
        </div>
    </div>

</div>