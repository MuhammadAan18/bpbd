<div
    class="glass p-6 shadow-md rounded-2xl flex items-center gap-6 transform hover:scale-105 transition-transform duration-300">
    <div class="w-16 h-16 rounded-full {{ $colorClass }} flex items-center justify-center">
        <!-- Icon SVG will be rendered based on icon property -->
        @php
            $icons = [
                'check-circle' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'clock' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                'hourglass' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                'chart-bar' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
            ];
        @endphp
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="{{ $icons[$icon] ?? $icons['chart-bar'] }}"></path>
        </svg>
    </div>
    <div>
        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">{{ $label }}</p>
        <p class="text-4xl font-bold text-gray-800">{{ number_format($value) }}</p>
    </div>
</div>