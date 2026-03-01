<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-4 px-4">
        <a href="{{ route('admin.reports.show', $report) }}"
            class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dampak Sarpras Vital - Laporan #{{ $report->report_no }}</h1>
            <p class="text-sm text-gray-500">{{ $report->disasterType->slug }} di {{ $report->location_text }}</p>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Form --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-800 border-b pb-3 mb-4">Data Kerusakan Sarana Prasarana Vital</h3>

        <form wire:submit.prevent="save" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Bridge --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jembatan Rusak <span class="text-gray-500 text-xs">(unit)</span>
                    </label>
                    <input type="number" wire:model="infra_bridge_damaged" min="0"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="0">
                    @error('infra_bridge_damaged') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Road --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jalan Rusak <span class="text-gray-500 text-xs">(meter)</span>
                    </label>
                    <input type="number" wire:model="infra_road_damaged" min="0"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="0">
                    @error('infra_road_damaged') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Dam --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Bendungan Rusak <span class="text-gray-500 text-xs">(unit)</span>
                    </label>
                    <input type="number" wire:model="infra_dam_damaged" min="0"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="0">
                    @error('infra_dam_damaged') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Embankment --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggul Rusak <span class="text-gray-500 text-xs">(meter)</span>
                    </label>
                    <input type="number" wire:model="infra_embankment_damaged" min="0"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="0">
                    @error('infra_embankment_damaged') <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Electricity --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jaringan Listrik Terganggu <span class="text-gray-500 text-xs">(unit/titik)</span>
                    </label>
                    <input type="number" wire:model="infra_electricity_disrupted" min="0"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="0">
                    @error('infra_electricity_disrupted') <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Communication --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jaringan Komunikasi Terganggu <span class="text-gray-500 text-xs">(unit/titik)</span>
                    </label>
                    <input type="number" wire:model="infra_communication_disrupted" min="0"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="0">
                    @error('infra_communication_disrupted') <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Water --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jaringan Air Bersih Rusak <span class="text-gray-500 text-xs">(meter)</span>
                    </label>
                    <input type="number" wire:model="infra_water_damaged" min="0"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="0">
                    @error('infra_water_damaged') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Irrigation --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jaringan Irigasi Rusak <span class="text-gray-500 text-xs">(meter)</span>
                    </label>
                    <input type="number" wire:model="infra_irrigation_damaged" min="0"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="0">
                    @error('infra_irrigation_damaged') <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-3 pt-4 border-t">
                <button type="submit"
                    class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow transition">
                    Simpan Data
                </button>
                <a href="{{ route('admin.reports.show', $report) }}"
                    class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>