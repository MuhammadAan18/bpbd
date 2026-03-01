<div class="max-w-4xl mx-auto space-y-8">

    {{-- Header --}}
    <div class="text-center space-y-4 py-8">
        <h1
            class="text-2xl sm:text-4xl md:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-700">
            Lapor Kejadian Bencana
        </h1>
        <p class="text-sm sm:text-base md:text-lg text-gray-600 max-w-2xl mx-auto">
            Bantu kami memetakan daerah rawan dan mempercepat penanganan dengan melaporkan kejadian di sekitar Anda.
        </p>
    </div>

    {{-- Success State --}}
    @if($isSubmitted)
        <div class="glass rounded-2xl text-center space-y-6 animate-fade-in-up pt-3 sm:my-36 sm:px-18">
            <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Laporan Berhasil Dikirim!</h2>
                <p class="text-gray-500 mt-2">Terima kasih atas kontribusi Anda. Nomor laporan Anda adalah:</p>
                <div
                    class="mt-4 text-3xl font-mono font-bold text-blue-600 bg-blue-50 py-3 px-6 rounded-lg inline-block selection:bg-blue-200">
                    {{ $submittedReportNo }}
                </div>
                <p class="text-sm text-gray-400 mt-4">Simpan nomor ini untuk memantau status laporan Anda.</p>
            </div>
            <div class="flex flex-row gap-4 py-6 items-center justify-center">
                <button href="{{ route('public.dashboard') }}" wire:navigate
                    class="bg-transparent text-blue-600 font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transform transition hover:-translate-y-0.5 text-center flex items-center justify-center">
                    Kembali
                </button>
                <button wire:click="$set('isSubmitted', false)"
                    class="bg-transparent text-blue-600 font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transform transition hover:-translate-y-0.5 text-center flex items-center justify-center">
                    Buat laporan baru
                </button>
            </div>
        </div>
        <div class="pb-64"></div>
    @else

        {{-- Form --}}
        <form wire:submit="save" class="space-y-8">

            {{-- Section 1: Identitas Pelapor --}}
            <div class="glass rounded-2xl p-6 md:p-8 space-y-6">
                <h3 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                    <span
                        class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold">1</span>
                    Identitas Pelapor
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap <span
                                class="text-red-500">*</span></label>
                        <input type="text" wire:model="reporter_name"
                            class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm bg-white/50 backdrop-blur-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Nomor HP/WA <span
                                class="text-red-500">*</span></label>
                        <input type="text" wire:model="reporter_phone" placeholder="Contoh : 08**********"
                            class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm bg-white/50 backdrop-blur-sm">
                        @error('reporter_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        @error('reporter_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500">Tidak akan dipublikasikan.</p>
                    </div>
                </div>
            </div>

            {{-- Section 2: Lokasi & Waktu --}}
            <div class="glass rounded-2xl p-6 md:p-8 space-y-6">
                <h3 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                    <span
                        class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold">2</span>
                    Lokasi & Waktu
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Waktu --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Waktu Kejadian <span
                                class="text-red-500">*</span></label>
                        <input type="datetime-local" wire:model="occurred_at"
                            class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm bg-white/50 backdrop-blur-sm">
                        @error('occurred_at') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- Wilayah & Kecamatan --}}
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Wilayah (Kabupaten/Kota) <span
                                    class="text-red-500">*</span></label>
                            <select wire:model.live="region_id"
                                class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm bg-white/50 backdrop-blur-sm">
                                <option value="">-- Pilih Wilayah --</option>
                                @foreach($regions as $region)
                                    <option value="{{ $region->id }}">{{ $region->slug }}</option>
                                @endforeach
                            </select>
                            @error('region_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Kecamatan <span
                                class="text-red-500">*</span></label>
                        <input type="text" wire:model="district_name" placeholder="Ketik nama kecamatan..."
                            class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm bg-white/50 backdrop-blur-sm">
                        @error('district_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Desa / Kelurahan <span
                                class="text-red-500">*</span></label>
                        <input type="text" wire:model="village_name" placeholder="Ketik nama desa/kelurahan..."
                            class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm bg-white/50 backdrop-blur-sm">
                        @error('village_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Detail Lokasi --}}
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Detail Lokasi (Dusun / Jalan) <span
                            class="text-red-500">*</span></label>
                    <input type="text" wire:model="location_text" placeholder="Contoh: Dusun A, Jalan Mawar..."
                        class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm bg-white/50 backdrop-blur-sm">
                    @error('location_text') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- MAP --}}
                <div class="space-y-2" wire:ignore>
                    <div class="flex justify-between items-center">
                        <label class="block text-sm font-medium text-gray-700">Titik Peta (Klik lokasi) <span
                                class="text-red-500">*</span></label>
                        <button type="button" onclick="detectMyLocation()"
                            class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1.5 rounded-lg font-medium flex items-center gap-1 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Deteksi Lokasi Saya
                        </button>
                    </div>
                    <div id="map" class="h-80 w-full rounded-xl shadow-inner border border-gray-200 z-0 relative"></div>
                    <p class="text-xs text-gray-500 text-right">Lat: <span x-text="$wire.latitude || '-'"></span>, Lng:
                        <span x-text="$wire.longitude || '-'"></span></p>
                </div>
                @error('latitude') <span class="text-red-500 text-xs block">{{ $message }}</span> @enderror

            </div>

            {{-- Section 3: Detail Kejadian --}}
            <div class="glass rounded-2xl p-6 md:p-8 space-y-6">
                <h3 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                    <span
                        class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold">3</span>
                    Detail Kejadian
                </h3>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Jenis Bencana <span
                            class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach($disaster_types as $type)
                            <label class="cursor-pointer relative">
                                <input type="radio" wire:model.live="disaster_type_id" value="{{ $type->id }}"
                                    class="peer sr-only">
                                <div
                                    class="p-4 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 peer-checked:bg-blue-50 peer-checked:border-blue-500 peer-checked:text-blue-700 text-center transition-all h-full flex flex-col items-center justify-center gap-2">
                                    <span class="text-sm font-medium">{{ $type->slug }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('disaster_type_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Narasi / Deskripsi <span
                            class="text-red-500">*</span></label>
                    <textarea wire:model="description" rows="4"
                        class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm bg-white/50 backdrop-blur-sm"
                        placeholder="Jelaskan kronologi singkat, dampak, dan kebutuhan mendesak..."></textarea>
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Upload Foto --}}
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Bukti Foto</label>
                    <div x-data="{ isDropping: false, focused: false }" x-on:dragover.prevent="isDropping = true"
                        x-on:dragleave.prevent="isDropping = false" x-on:drop.prevent="isDropping = false"
                        class="relative border-2 border-dashed rounded-xl p-6 text-center transition-all duration-200 bg-white/40"
                        :class="{ 'border-blue-500 bg-blue-50': isDropping, 'border-gray-300': !isDropping }">
                        <input type="file" wire:model="photo" accept="image/*"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                        @if ($photo)
                            <div class="relative w-full h-48 bg-gray-100 rounded-lg overflow-hidden">
                                <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                                <button type="button" wire:click="$set('photo', null)"
                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 shadow-lg hover:bg-red-600 z-20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        @else
                            <div class="space-y-1">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48" aria-hidden="true">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <span class="font-medium text-blue-600 hover:text-blue-500">Upload file</span>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                            </div>
                        @endif
                    </div>
                    @error('photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>


            <div class="flex justify-end pb-24">
                <div class="flex flex-row gap-4">
                    <a href="{{ route('public.dashboard') }}" wire:navigate
                        class="bg-gradient-to-r from-red-600 to-red-600 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transform transition hover:-translate-y-0.5 text-center flex items-center justify-center">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transform transition hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove>Kirim Laporan</span>
                        <span wire:loading>Mengirim...</span>
                    </button>
                </div>
            </div>
        </form>
    @endif

    {{-- Map Script --}}
    <script>
        // Store map instance globally to prevent memory leaks
        let mapInstance = null;
        let markerInstance = null;

        function initMap() {
            const defaultLat = -7.4; // Default center (approx Java)
            const defaultLng = 110.0;

            const mapElement = document.getElementById('map');
            if (!mapElement) return;

            // Destroy existing map instance if it exists
            if (mapInstance) {
                mapInstance.remove();
                mapInstance = null;
                markerInstance = null;
            }

            // Create new map instance
            mapInstance = L.map('map').setView([defaultLat, defaultLng], 8);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(mapInstance);

            // Function to set marker
            const setMarker = (lat, lng) => {
                if (markerInstance) {
                    markerInstance.setLatLng([lat, lng]);
                } else {
                    markerInstance = L.marker([lat, lng]).addTo(mapInstance);
                }
                @this.set('latitude', lat);
                @this.set('longitude', lng);
            };

            // Click event to set marker
            mapInstance.on('click', function (e) {
                setMarker(e.latlng.lat, e.latlng.lng);
            });

            // Auto-detect user location and set marker
            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    // Pan to user's location with higher zoom
                    mapInstance.setView([lat, lng], 15);

                    // Auto-set marker at user's current location
                    setMarker(lat, lng);
                }, function (error) {
                    console.log('Geolocation error:', error.message);
                    // Fallback: just keep default center if geolocation fails
                });
            }

            // Expose function to re-detect location (can be called from button)
            window.detectMyLocation = function () {
                if ("geolocation" in navigator) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        mapInstance.setView([lat, lng], 15);
                        setMarker(lat, lng);
                    }, function (error) {
                        alert('Tidak dapat mendeteksi lokasi: ' + error.message);
                    });
                } else {
                    alert('Browser Anda tidak mendukung geolocation.');
                }
            };
        }

        // Initialize map on first load
        document.addEventListener('livewire:initialized', initMap);

        // Re-initialize map when navigating back to this page
        document.addEventListener('livewire:navigated', initMap);
    </script>
</div>