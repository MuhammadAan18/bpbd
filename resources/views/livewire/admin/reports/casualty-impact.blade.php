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
            <h1 class="text-2xl font-bold text-gray-800">Dampak Jiwa - Laporan #{{ $report->report_no }}</h1>
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
        <h3 class="text-lg font-bold text-gray-800 border-b pb-3 mb-4">Data Korban Jiwa</h3>

        <form wire:submit.prevent="save" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Deaths --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Korban Meninggal Dunia
                    </label>
                    <input type="number" wire:model="casualty_deaths" min="0"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="0">
                    @error('casualty_deaths') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Missing --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Korban Hilang
                    </label>
                    <input type="number" wire:model="casualty_missing" min="0"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="0">
                    @error('casualty_missing') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Injured --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Korban Luka-luka
                    </label>
                    <input type="number" wire:model="casualty_injured" min="0"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="0">
                    @error('casualty_injured') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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