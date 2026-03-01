<div class="space-y-8" wire:init="loadIncidents">
    {{-- Header & Filters --}}
    <div class="text-center space-y-4 py-8">
        <h1
            class="text-2xl sm:text-4xl md:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-500">
            Data Kobo
        </h1>
        <p class="text-sm sm:text-base md:text-lg text-gray-600 max-w-2xl mx-auto">
            Laporan kejadian bencana dari formulir Kobo yang telah dikumpulkan.
        </p>
    </div>

    {{-- Error Message --}}
    @if($error)
        <div class="glass bg-red-50/70 border border-red-200 rounded-xl p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd" />
            </svg>
            <div>
                <h3 class="text-sm font-medium text-red-800">Error</h3>
                <p class="text-sm text-red-700">{{ $error }}</p>
            </div>
        </div>
    @endif

    {{-- Filter Bar --}}
    <div
        class="glass p-4 rounded-xl flex flex-col md:flex-row gap-4 items-center justify-between sticky top-24 z-30 transition-all duration-300">
        <div class="w-full md:w-1/3">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari berdasarkan ID atau wilayah..."
                    class="w-full pl-10 rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500 bg-white/50 backdrop-blur-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-2 w-full md:w-auto">
            <select wire:model.live="type_filter"
                class="rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm bg-white/50">
                <option value="">Semua Jenis</option>
                <option value="banjir">Banjir</option>
                <option value="gempa">Gempa Bumi</option>
                <option value="tanah longsor">Tanah Longsor</option>
                <option value="kekeringan">Kekeringan</option>
                <option value="letusan gunung">Letusan Gunung</option>
            </select>

            <select wire:model.live="region_filter"
                class="rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm bg-white/50">
                <option value="">Semua Wilayah</option>
            </select>
        </div>
    </div>

    {{-- Loading State --}}
    @if($loading)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @for($i = 0; $i < 3; $i++)
                <div class="glass rounded-2xl overflow-hidden animate-pulse">
                    <div class="h-48 bg-gray-300 rounded-t-2xl"></div>
                    <div class="p-6 space-y-4">
                        <div class="h-4 bg-gray-300 rounded w-3/4"></div>
                        <div class="h-4 bg-gray-300 rounded"></div>
                        <div class="h-4 bg-gray-300 rounded w-5/6"></div>
                    </div>
                </div>
            @endfor
        </div>
    @else
        {{-- Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($incidents as $incident)
                <div
                    class="glass rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300 group flex flex-col h-full">
                    {{-- Header/Image Placeholder --}}
                    <div
                        class="h-48 bg-gradient-to-br from-blue-100 to-indigo-200 relative overflow-hidden flex items-center justify-center">
                        <div class="text-center text-blue-400 opacity-50">
                            <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm font-medium">{{ $incident['disaster_type'] ?? 'Data Kobo' }}</p>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-6 flex-1 flex flex-col">
                        {{-- Date --}}
                        <div class="flex items-center text-xs text-gray-500 gap-1 mb-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $incident['date'] ?? 'N/A' }}</span>
                        </div>

                        {{-- Disaster Type --}}
                        <div class="flex items-center text-xs text-gray-500 gap-1 mb-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4v2m0 4v2M7.08 6.47c.39-.39 1.02-.39 1.41 0l11.66 11.66m0-1.41L8.49 5.06c-.39-.39-1.02-.39-1.41 0"></path>
                            </svg>
                            <span class="font-medium text-gray-700">{{ $incident['disaster_type'] ?? 'Bencana' }}</span>
                        </div>

                        {{-- Region --}}
                        <div class="flex items-center text-xs text-gray-600 gap-1 mb-3">
                            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>{{ $incident['region'] ?? 'Wilayah tidak diketahui' }}</span>
                        </div>

                        {{-- Location Coordinates --}}
                        <div class="flex items-center text-xs text-gray-600 gap-1 mb-3">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                            <span class="truncate text-xs" title="{{ $incident['location_coordinates'] ?? 'Koordinat tidak tersedia' }}">
                                {{ $incident['location_coordinates'] ?? 'Koordinat tidak tersedia' }}
                            </span>
                        </div>

                        {{-- Affected People (if available) --}}
                        @if($incident['affected_people'] ?? null)
                            <div class="flex items-center text-xs text-gray-600 gap-1">
                                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 4H9m6 16H9m0-11h.01M15 20h.01"></path>
                                </svg>
                                <span><strong>{{ $incident['affected_people'] }}</strong> jiwa terdampak</span>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-gray-500">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-lg font-medium">Tidak ada laporan ditemukan.</p>
                    <p class="text-sm">Coba ubah filter pencarian Anda atau data dari Kobo belum tersedia.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination Controls --}}
        @if(!empty($incidents) && isset($pagination['total']) && $pagination['total'] > 0)
            <div class="flex items-center justify-between mt-8 p-4 glass rounded-lg">
                <div class="text-sm text-gray-600">
                    Menampilkan <span class="font-medium">{{ $pagination['from'] ?? 1 }}</span>
                    hingga <span class="font-medium">{{ $pagination['to'] ?? 1 }}</span>
                    dari <span class="font-medium">{{ $pagination['total'] ?? 0 }}</span> laporan
                </div>

                <div class="flex gap-2">
                    <button wire:click="previousPage()" @disabled($page <= 1)
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        ← Sebelumnya
                    </button>

                    <div class="flex items-center gap-1">
                        <span class="text-sm text-gray-600">Halaman</span>
                        <span class="px-3 py-2 text-sm font-medium bg-blue-100 text-blue-700 rounded-lg">
                            {{ $page }} / {{ $pagination['last_page'] ?? 1 }}
                        </span>
                    </div>

                    <button wire:click="nextPage()" @disabled($page >= ($pagination['last_page'] ?? 1))
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        Selanjutnya →
                    </button>
                </div>
            </div>
        @endif
    @endif
</div>