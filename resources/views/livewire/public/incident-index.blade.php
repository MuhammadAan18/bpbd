<div class="space-y-8">
    {{-- Header --}}
    <div class="text-center space-y-4 py-8">
        <h1
            class="text-2xl sm:text-4xl md:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-500">
            Daftar Bencana
        </h1>
        <p class="text-sm sm:text-base md:text-lg text-gray-600 max-w-2xl mx-auto">
            Berikut ini merupakan kejadian yang terjadi di sekitar Anda.
        </p>
    </div>

    {{-- Filter Bar --}}
    <div
        class="glass p-4 rounded-xl flex flex-col md:flex-row gap-4 items-center justify-between sticky top-24 z-30 transition-all duration-300">
        <div class="w-full md:w-1/3">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Cari lokasi atau jenis bencana..."
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
            <select wire:model.live="disaster_type_id"
                class="rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm bg-white/50">
                <option value="">Semua Bencana</option>
                @foreach($disaster_types as $type)
                    <option value="{{ $type->id }}">{{ $type->slug }}</option>
                @endforeach
            </select>

            <select wire:model.live="region_id"
                class="rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm bg-white/50">
                <option value="">Semua Wilayah</option>
                @foreach($regions as $region)
                    <option value="{{ $region->id }}">{{ $region->slug }}</option>
                @endforeach
            </select>
        </div>

        {{-- Total count badge --}}
        <div class="text-base font-semibold text-blue-600 shrink-0">
            <span class="font-semibold text-blue-600">{{ $incidents->total() }}</span> Kejadian Ditemukan
        </div>
    </div>

    {{-- Loading skeleton on first render --}}
    <div wire:loading.class.remove="hidden" class="hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-6">
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
    </div>

    {{-- Cards Grid --}}
    <div wire:loading.remove>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-6">
            @forelse($incidents as $index => $item)
                @if($item['source'] === 'kobo')
                    @php $incident = $item['data'];
                    $incidentId = $index + 1; @endphp
                    {{-- KoBO Card (same UI as website) --}}
                    <a href="{{ route('public.incidents.show', ['source' => 'kobo', 'id' => $incidentId]) }}"
                        class="glass rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300 group flex flex-col h-full cursor-pointer">
                        {{-- Header / Thumbnail placeholder --}}
                        <div
                            class="h-48 bg-gradient-to-br from-blue-100 to-indigo-200 relative overflow-hidden flex items-center justify-center">
                            <div class="text-center text-blue-300 opacity-60">
                                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            {{-- Disaster type badge --}}
                            <div class="absolute top-2 right-2">
                                <span
                                    class="px-2 py-1 bg-white/90 backdrop-blur-md text-gray-800 text-xs font-bold rounded shadow-sm">
                                    {{ $incident['disaster_type'] ?? 'Bencana' }}
                                </span>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-6 flex-1 flex flex-col">
                            {{-- Date & time --}}
                            <div class="flex items-center text-xs text-gray-500 gap-1 mb-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $incident['date'] ?? '-' }},
                                @if(!empty($incident['time']))
                                    <span class="ml-1">{{ $incident['time'] }}</span>
                                @endif
                            </div>

                            {{-- Title (disaster type + region) --}}
                            <h3
                                class="text-lg font-bold text-gray-800 mb-2 leading-tight group-hover:text-blue-600 transition-colors">
                                Laporan {{ ucfirst($incident['disaster_type'] ?? 'Bencana') }} di
                                {{ $incident['district'] ?? $incident['region'] ?? 'Lokasi Tidak Diketahui' }}
                            </h3>

                            {{-- detail --}}
                            <p class="text-sm text-gray-600 mb-4 flex-1">
                                Laporan hasil dari pengisian form kobotool oleh relawan
                            </p>

                            {{-- Footer: affected + source badge --}}
                            <div class="pt-4 border-t border-gray-100 flex justify-between items-center text-sm">
                                <div class="flex items-center gap-1 text-gray-500">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span
                                        class="truncate max-w-[150px]">{{ $incident['village'] ?? '' }}{{ !empty($incident['village']) && !empty($incident['district']) ? ', ' : '' }}{{ $incident['district'] ?? '' }}{{ !empty($incident['district']) && !empty($incident['region']) ? ', ' : '' }}{{ $incident['region'] ?? '' }}</span>
                                </div>
                                <span
                                    class="px-2 py-0.5 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full border border-blue-200">
                                    Kobo
                                </span>
                            </div>
                        </div>
                    </a>
                @else
                    @php $incident = $item['data']; @endphp
                    {{-- Website Card --}}
                    <a href="{{ route('public.incidents.show', ['source' => 'website', 'id' => $incident->id]) }}"
                        class="glass rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300 group flex flex-col h-full cursor-pointer">
                        {{-- Image / Map Placeholder --}}
                        <div class="h-48 bg-gray-200 relative overflow-hidden">
                            @php $thumb = $incident->attachments->first(); @endphp
                            @if($thumb)
                                <img src="{{ Storage::url($thumb->file_path) }}"
                                    class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-300">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute top-2 right-2">
                                <span
                                    class="px-2 py-1 bg-white/90 backdrop-blur-md text-gray-800 text-xs font-bold rounded shadow-sm">
                                    {{ $incident->disasterType->slug }}
                                </span>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="flex items-center text-xs text-gray-500 gap-1 mb-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $incident->occurred_at->format('d M Y, H:i') }}
                            </div>

                            <h3
                                class="text-lg font-bold text-gray-800 mb-2 leading-tight group-hover:text-blue-600 transition-colors">
                                {{ $incident->title }}
                            </h3>

                            <p class="text-sm text-gray-600 line-clamp-3 mb-4 flex-1">
                                {{ $incident->description }}
                            </p>

                            <div class="pt-4 border-t border-gray-100 flex justify-between items-center text-sm">
                                <div class="flex items-center gap-1 text-gray-500">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="truncate max-w-[150px]">{{ $incident->region->slug }}</span>
                                </div>
                                <span
                                    class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full border border-gray-200">
                                    Website
                                </span>
                            </div>
                        </div>
                    </a>
                @endif
            @empty
                <div class="col-span-full py-16 text-center text-gray-500">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-lg font-medium">Tidak ada laporan ditemukan.</p>
                    <p class="text-sm mt-1">Coba ubah filter pencarian Anda.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-8 px-6">
            {{ $incidents->links() }}
        </div>
    </div>
</div>