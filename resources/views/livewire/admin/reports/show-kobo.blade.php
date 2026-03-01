<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-4 px-4">
        <a href="{{ route('admin.reports.index', ['status' => 'verified']) }}"
            class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Data KoBO</h1>
            @if(!$loading && !empty($kobo_data))
                <p class="text-sm text-gray-500">ID: {{ $kobo_data['id'] ?? '-' }}</p>
            @endif
        </div>
        <div class="ml-auto">
            <span class="px-4 py-2 rounded-full text-sm font-bold border bg-green-100 text-green-800 border-green-200">
                Terverifikasi
            </span>
        </div>
    </div>

    {{-- Loading State --}}
    @if($loading)
        <div class="flex justify-center items-center py-20">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            <span class="ml-4 text-gray-600">Memuat data KoBO...</span>
        </div>
    @endif

    {{-- Error State --}}
    @if($error && !$loading)
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ $error }}</span>
        </div>
    @endif

    {{-- Content --}}
    @if(!$loading && !$error && !empty($kobo_data))
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left: Details --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Main Info --}}
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 space-y-4">
                    <h3 class="text-lg font-bold text-gray-800 border-b pb-2">Informasi Kejadian</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase">Jenis Bencana</label>
                            <p class="text-gray-900 font-medium">{{ $kobo_data['disaster_type'] ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase">Tanggal Kejadian</label>
                            <p class="text-gray-900 font-medium">{{ $kobo_data['date'] ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase">Waktu Kejadian</label>
                            <p class="text-gray-900 font-medium">{{ $kobo_data['time'] ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase">Wilayah (Kabupaten)</label>
                            <p class="text-gray-900 font-medium">{{ $kobo_data['region'] ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase">Kecamatan</label>
                            <p class="text-gray-900 font-medium">{{ $kobo_data['district'] ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase">Desa / Kelurahan</label>
                            <p class="text-gray-900 font-medium">{{ $kobo_data['village'] ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase">Jiwa Terdampak</label>
                            <p class="text-gray-900 font-medium text-blue-600 text-lg font-bold">{{ $kobo_data['affected_people'] ?? 0 }} orang</p>
                        </div>
                        @if(!empty($kobo_data['location_text']))
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-500 uppercase">Detail Lokasi</label>
                                <p class="text-gray-900 font-medium">{{ $kobo_data['location_text'] }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Impact Details --}}
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 space-y-4">
                    <h3 class="text-lg font-bold text-gray-800 border-b pb-2">Detail Dampak Bencana</h3>

                    <div class="space-y-4">
                        {{-- Dampak Jiwa --}}
                        <div class="p-4 bg-gradient-to-r from-red-50 to-red-100 rounded-lg border border-red-200">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center text-white shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <p class="font-bold text-gray-800">Dampak Jiwa</p>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="bg-white rounded-lg p-3 text-center">
                                    <p class="text-xs text-gray-500 uppercase">Meninggal</p>
                                    <p class="text-2xl font-bold text-red-600">{{ $kobo_data['casualty_deaths'] ?? 0 }}</p>
                                </div>
                                <div class="bg-white rounded-lg p-3 text-center">
                                    <p class="text-xs text-gray-500 uppercase">Hilang</p>
                                    <p class="text-2xl font-bold text-yellow-600">{{ $kobo_data['casualty_missing'] ?? 0 }}</p>
                                </div>
                                <div class="bg-white rounded-lg p-3 text-center">
                                    <p class="text-xs text-gray-500 uppercase">Luka-luka</p>
                                    <p class="text-2xl font-bold text-orange-600">{{ $kobo_data['casualty_injured'] ?? 0 }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Dampak Kerusakan Rumah --}}
                        <div class="p-4 bg-gradient-to-r from-orange-50 to-orange-100 rounded-lg border border-orange-200">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center text-white shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                </div>
                                <p class="font-bold text-gray-800">Dampak Kerusakan Rumah</p>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @foreach(['house_heavy_damage' => 'Rusak Berat', 'house_moderate_damage' => 'Rusak Sedang', 'house_light_damage' => 'Rusak Ringan', 'house_flooded' => 'Terendam'] as $key => $label)
                                    <div class="bg-white rounded-lg p-3 text-center">
                                        <p class="text-xs text-gray-500 uppercase">{{ $label }}</p>
                                        <p class="text-2xl font-bold text-gray-900">{{ $kobo_data[$key] ?? 0 }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Dampak Sarpras Vital --}}
                        <div class="p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg border border-blue-200">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <p class="font-bold text-gray-800">Dampak Sarpras Vital</p>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @foreach([
                                    'infra_bridge_damaged' => 'Jembatan', 'infra_road_damaged' => 'Jalan',
                                    'infra_dam_damaged' => 'Bendungan', 'infra_embankment_damaged' => 'Tanggul',
                                    'infra_electricity_disrupted' => 'Listrik', 'infra_communication_disrupted' => 'Komunikasi',
                                    'infra_water_damaged' => 'Air Bersih', 'infra_irrigation_damaged' => 'Irigasi',
                                ] as $key => $label)
                                    <div class="bg-white rounded-lg p-3 text-center">
                                        <p class="text-xs text-gray-500 uppercase">{{ $label }}</p>
                                        <p class="text-xl font-bold text-gray-900">{{ $kobo_data[$key] ?? 0 }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Dampak Sosial Ekonomi --}}
                        <div class="p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg border border-green-200">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center text-white shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="font-bold text-gray-800">Dampak Sosial Ekonomi</p>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach([
                                    'econ_forest_affected' => 'Hutan', 'econ_plantation_affected' => 'Kebun/Perkebunan',
                                    'econ_rice_field_affected' => 'Sawah', 'econ_pond_affected' => 'Tambak',
                                    'econ_factory_affected' => 'Pabrik', 'econ_shop_affected' => 'Warung/Toko',
                                ] as $key => $label)
                                    <div class="bg-white rounded-lg p-3 text-center">
                                        <p class="text-xs text-gray-500 uppercase">{{ $label }}</p>
                                        <p class="text-xl font-bold text-gray-900">{{ $kobo_data[$key] ?? 0 }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Dampak Pelayanan Dasar --}}
                        <div class="p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg border border-purple-200">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center text-white shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <p class="font-bold text-gray-800">Dampak Pelayanan Dasar</p>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach([
                                    'service_office_affected' => 'Perkantoran', 'service_market_affected' => 'Pasar',
                                    'service_education_affected' => 'Pendidikan', 'service_health_affected' => 'Kesehatan',
                                    'service_worship_affected' => 'Peribadatan',
                                ] as $key => $label)
                                    <div class="bg-white rounded-lg p-3 text-center">
                                        <p class="text-xs text-gray-500 uppercase">{{ $label }}</p>
                                        <p class="text-xl font-bold text-gray-900">{{ $kobo_data[$key] ?? 0 }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            {{-- Right: Status & Map --}}
            <div class="space-y-6">
                {{-- Status Box (read-only) --}}
                <div class="bg-white p-6 rounded-xl shadow-lg border border-green-100 space-y-4 top-6">
                    <h3 class="text-lg font-bold text-gray-800">Status Verifikasi</h3>
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm font-bold text-green-800">Sudah Terverifikasi</p>
                        </div>
                        <p class="text-sm text-green-700">Data ini berasal dari survei KoBO Toolbox dan sudah terverifikasi secara otomatis.</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg space-y-2">
                        <p class="text-sm text-gray-600">
                            <strong>Sumber Data:</strong> KoBO Surve
                        </p>
                        <p class="text-sm text-gray-600">
                            <strong>ID Respons:</strong> {{ $kobo_data['id'] ?? '-' }}
                        </p>
                        <p class="text-sm text-gray-600 italic">
                            Data ditampilkan dalam mode baca saja (read-only).
                        </p>
                    </div>
                </div>

                {{-- Location / Map --}}
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-2">Koordinat Lokasi</label>
                    @if(!empty($kobo_data['location_coordinates']))
                        <p class="text-sm text-gray-700 mb-3 font-mono bg-gray-50 p-2 rounded break-all">
                            {{ $kobo_data['location_coordinates'] }}
                        </p>
                        @php
                            $coords = explode(' ', trim($kobo_data['location_coordinates']));
                            $lat = $coords[0] ?? null;
                            $lng = $coords[1] ?? null;
                        @endphp
                        @if($lat && $lng && is_numeric($lat) && is_numeric($lng))
                            <div id="mapKobo" class="h-64 w-full rounded-lg bg-gray-200 z-10"></div>
                        @else
                            <div class="h-32 w-full rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 text-sm">
                                Format koordinat tidak dikenali
                            </div>
                        @endif
                    @else
                        <div class="h-32 w-full rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 text-sm">
                            Koordinat tidak tersedia
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if(!$loading && !$error && !empty($kobo_data) && !empty($kobo_data['location_coordinates']))
        @php
            $coords = explode(' ', trim($kobo_data['location_coordinates']));
            $lat = $coords[0] ?? null;
            $lng = $coords[1] ?? null;
        @endphp
        @if($lat && $lng && is_numeric($lat) && is_numeric($lng))
            <script>
                document.addEventListener('livewire:initialized', () => {
                    if (document.getElementById('mapKobo')) {
                        const lat = {{ (float)$lat }};
                        const lng = {{ (float)$lng }};

                        const map = L.map('mapKobo').setView([lat, lng], 13);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '© OpenStreetMap'
                        }).addTo(map);

                        L.marker([lat, lng]).addTo(map)
                            .bindPopup('<b>Lokasi Kejadian</b><br>{{ $kobo_data["region"] ?? "" }} - {{ $kobo_data["district"] ?? "" }}')
                            .openPopup();
                    }
                });
            </script>
        @endif
    @endif
</div>