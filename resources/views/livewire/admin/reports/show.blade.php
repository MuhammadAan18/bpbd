<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-4 px-4">
        <a href="{{ route('admin.reports.index') }}"
            class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Laporan #{{ $report->report_no }}</h1>
            <p class="text-sm text-gray-500">Dilaporkan {{ $report->reported_at->format('d M Y H:i') }}</p>
        </div>
        <div class="ml-auto">
            @php
                $statusColors = [
                    'submitted' => 'bg-blue-100 text-blue-800 border-blue-200',
                    'under_review' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                    'verified' => 'bg-green-100 text-green-800 border-green-200',
                    'rejected' => 'bg-red-100 text-red-800 border-red-200',
                ];
            @endphp
            <span class="px-4 py-2 rounded-full text-sm font-bold border {{ $statusColors[$report->status] }}">
                {{ ucfirst(str_replace('_', ' ', $report->status)) }}
            </span>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left: Details --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Main Info --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 space-y-4">
                <h3 class="text-lg font-bold text-gray-800 border-b pb-2">Informasi Kejadian</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase">Jenis Bencana</label>
                        <p class="text-gray-900 font-medium">{{ $report->disasterType->slug }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase">Waktu Kejadian</label>
                        <p class="text-gray-900 font-medium">{{ $report->occurred_at->format('l, d F Y - H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase">Wilayah (Kabupaten)</label>
                        <p class="text-gray-900 font-medium">{{ $report->region->slug }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase">Kecamatan</label>
                        <p class="text-gray-900 font-medium">{{ $report->district_name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase">Desa / Kelurahan</label>
                        <p class="text-gray-900 font-medium">{{ $report->village_name }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-500 uppercase">Detail Lokasi
                            (Dusun/Jalan)</label>
                        <p class="text-gray-900 font-medium">{{ $report->location_text }}</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Deskripsi / Kronologi</label>
                    <div class="bg-gray-50 p-4 rounded-lg text-gray-700 leading-relaxed">
                        {{ $report->description }}
                    </div>
                </div>
            </div>

            {{-- Photos --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 space-y-4">
                <h3 class="text-lg font-bold text-gray-800 border-b pb-2">Bukti Lampiran</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @forelse($report->attachments as $attachment)
                        <a href="{{ Storage::url($attachment->file_path) }}" target="_blank"
                            class="block group relative overflow-hidden rounded-lg bg-gray-100 h-48">
                            <img src="{{ Storage::url($attachment->file_path) }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div
                                class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                <span
                                    class="opacity-0 group-hover:opacity-100 text-white font-medium text-sm bg-black/50 px-3 py-1 rounded-full">Lihat
                                    Full</span>
                            </div>
                        </a>
                    @empty
                        <p class="text-gray-500 italic col-span-full">Tidak ada lampiran foto.</p>
                    @endforelse
                </div>
            </div>

            {{-- Reporter Info --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 space-y-4">
                <h3 class="text-lg font-bold text-gray-800 border-b pb-2">Identitas Pelapor</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase">Nama</label>
                        <p class="text-gray-900">{{ $report->reporter_name ?: '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase">No HP</label>
                        <p class="text-gray-900">{{ $report->reporter_phone ?: '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Impact Details --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 space-y-4">
                <h3 class="text-lg font-bold text-gray-800 border-b pb-2">Detail Dampak Bencana</h3>

                <div class="space-y-3">
                    {{-- Dampak Jiwa --}}
                    <a href="{{ route('admin.reports.casualty-impact', $report) }}"
                        class="flex items-center justify-between p-4 bg-gradient-to-r from-red-50 to-red-100 hover:from-red-100 hover:to-red-200 rounded-lg border border-red-200 transition group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">Dampak Jiwa</p>
                                <p class="text-sm text-gray-600">Korban meninggal, hilang, luka-luka</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-red-500 group-hover:translate-x-1 transition" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>

                    {{-- Dampak Kerusakan Rumah --}}
                    <a href="{{ route('admin.reports.house-damage', $report) }}"
                        class="flex items-center justify-between p-4 bg-gradient-to-r from-orange-50 to-orange-100 hover:from-orange-100 hover:to-orange-200 rounded-lg border border-orange-200 transition group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">Dampak Kerusakan Rumah</p>
                                <p class="text-sm text-gray-600">Rusak berat, sedang, ringan, terendam</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-orange-500 group-hover:translate-x-1 transition" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>

                    {{-- Dampak Sarpras Vital --}}
                    <a href="{{ route('admin.reports.infrastructure-damage', $report) }}"
                        class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 rounded-lg border border-blue-200 transition group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">Dampak Sarpras Vital</p>
                                <p class="text-sm text-gray-600">Jembatan, jalan, listrik, komunikasi, dll</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-blue-500 group-hover:translate-x-1 transition" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>

                    {{-- Dampak Sosial Ekonomi --}}
                    <a href="{{ route('admin.reports.economic-impact', $report) }}"
                        class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 rounded-lg border border-green-200 transition group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">Dampak Sosial Ekonomi</p>
                                <p class="text-sm text-gray-600">Hutan, kebun, sawah, tambak, pabrik, pertokoan</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-green-500 group-hover:translate-x-1 transition" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>

                    {{-- Dampak Pelayanan Dasar --}}
                    <a href="{{ route('admin.reports.basic-services-impact', $report) }}"
                        class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-purple-100 hover:from-purple-100 hover:to-purple-200 rounded-lg border border-purple-200 transition group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">Dampak Pelayanan Dasar</p>
                                <p class="text-sm text-gray-600">Perkantoran, pasar, pendidikan, kesehatan, peribadatan
                                </p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-purple-500 group-hover:translate-x-1 transition" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- Right: Actions & Map --}}
        <div class="space-y-6">
            {{-- Action Box --}}
            <div class="bg-white p-6 rounded-xl shadow-lg border border-indigo-100 space-y-4 top-6">
                <h3 class="text-lg font-bold text-gray-800">Verifikasi</h3>

                @if($report->status === 'submitted')
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                        <p class="text-sm text-blue-700">Laporan baru masuk. Silakan tinjau data sebelum memproses.</p>
                    </div>
                    <button wire:click="markUnderReview"
                        class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow transition">
                        Mulai Proses Verifikasi
                    </button>
                @elseif($report->status === 'under_review')
                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-gray-700">Catatan Verifikasi / Alasan Penolakan</label>
                        <textarea wire:model="verification_notes" rows="4"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Tulis catatan di sini..."></textarea>
                        @error('verification_notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <button wire:click="reject" wire:confirm="Yakin ingin menolak laporan ini?"
                                class="py-2 px-4 bg-red-600 hover:bg-red-700 border border-red-500 text-white hover:bg-red-50 font-bold rounded-lg transition">
                                Tolak
                            </button>
                            <button wire:click="verify" wire:confirm="Publikasikan laporan ini?"
                                class="py-2 px-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow transition">
                                Verifikasi & Publish
                            </button>
                        </div>
                    </div>
                @else
                    {{-- Verified or Rejected --}}
                    <div class="bg-gray-50 p-4 rounded-lg space-y-2">
                        <p class="text-sm text-gray-600">
                            <strong>Diverifikasi oleh:</strong> Admin #{{ $report->verified_by }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <strong>Waktu:</strong>
                            {{ $report->verified_at ? $report->verified_at->format('d M Y H:i') : '-' }}
                        </p>
                        @if($report->verification_notes)
                            <div class="t-2 pt-2 border-t border-gray-200">
                                <strong class="text-xs text-gray-500 uppercase">Catatan:</strong>
                                <p class="text-gray-800 text-sm mt-1">{{ $report->verification_notes }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Map --}}
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <label class="block text-xs font-medium text-gray-500 uppercase mb-2">Lokasi Peta</label>
                <div id="mapDetail" class="h-64 w-full rounded-lg bg-gray-200 z-10"></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            if (document.getElementById('mapDetail')) {
                const lat = {{ $report->latitude }};
                const lng = {{ $report->longitude }};

                const map = L.map('mapDetail').setView([lat, lng], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(map);

                L.marker([lat, lng]).addTo(map)
                    .bindPopup("<b>Lokasi Kejadian</b><br>{{ $report->location_text }}")
                    .openPopup();
            }
        });
    </script>
</div>