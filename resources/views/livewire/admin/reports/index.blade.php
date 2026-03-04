<div class="space-y-6 px-6">
    <div class="flex justify-between items-center">
        <h2
            class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-700 to-indigo-600 space-y-8">
            Manajemen Laporan</h2>
        <div class="w-72">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari No. Laporan / Judul..."
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Tabs --}}
    <div class="flex space-x-1 border-b border-gray-200 overflow-x-auto">
        @php
            $tabs = [
                ['id' => 'submitted', 'label' => 'Baru Masuk', 'color' => 'blue'],
                ['id' => 'under_review', 'label' => 'Diproses', 'color' => 'yellow'],
                ['id' => 'verified', 'label' => 'Terverifikasi', 'color' => 'green'],
                ['id' => 'rejected', 'label' => 'Ditolak', 'color' => 'red'],
                // ['id' => 'all', 'label' => 'Semua', 'color' => 'gray'],
            ];
        @endphp

        @foreach($tabs as $tab)
            <button wire:click="setStatus('{{ $tab['id'] }}')" class="px-4 py-2 text-sm font-medium border-b-2 transition-colors whitespace-nowrap flex items-center gap-2
                        {{ $status === $tab['id']
            ? 'border-' . $tab['color'] . '-500 text-' . $tab['color'] . '-600 bg-white'
            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' 
                        }}">
                {{ $tab['label'] }}
                <span
                    class="px-2 py-0.5 rounded-full text-xs {{ $status === $tab['id'] ? 'bg-' . $tab['color'] . '-100 text-' . $tab['color'] . '-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $counts[$tab['id']] }}
                </span>
            </button>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="glass rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Laporan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis
                            / Wilayah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pelapor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reports as $item)
                        @if(isset($item['source']) && $item['source'] === 'kobo')
                            {{-- KoBO Data Row --}}
                            <tr class="hover:bg-blue-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-sm font-bold text-gray-900">{{ $item['data']['id'] ?? '-' }}</span>
                                        <span class="text-xs text-gray-500">
                                            Laporan {{ $item['data']['disaster_type'] ?? '-' }} di
                                            {{ $item['data']['district'] ?? '-' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm text-gray-900">{{ $item['data']['disaster_type'] ?? '-' }}</span>
                                        <span class="text-xs text-gray-500">{{ $item['data']['region'] ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm text-gray-900">{{ $item['data']['date'] ?? '-' }}</span>
                                        <span class="text-xs text-gray-500">{{ $item['data']['time'] ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-600">Admin</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Terverifikasi
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.reports.show-kobo', ['kobo_id' => $item['id']]) }}"
                                        class="text-indigo-600 hover:text-indigo-900 font-bold hover:underline">
                                        Detail &rarr;
                                    </a>
                                </td>
                            </tr>
                        @else
                            {{-- Website Data Row --}}
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900">{{ $item['data']->report_no }}</span>
                                        <span
                                            class="text-xs text-gray-500 truncate max-w-[200px]">{{ $item['data']->title }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm text-gray-900">{{ $item['data']->disasterType->slug }}</span>
                                        <span class="text-xs text-gray-500">{{ $item['data']->region->slug }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm text-gray-900">{{ $item['data']->occurred_at->format('d/m/Y') }}</span>
                                        <span
                                            class="text-xs text-gray-500">{{ $item['data']->occurred_at->format('H:i') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item['data']->reporter_name)
                                        <div class="text-sm text-gray-900">{{ $item['data']->reporter_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $item['data']->reporter_phone ?? '-' }}</div>
                                    @else
                                        <span class="text-sm text-gray-400 italic">Anonim</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'submitted' => 'bg-blue-100 text-blue-800',
                                            'under_review' => 'bg-yellow-100 text-yellow-800',
                                            'verified' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                        ];
                                        $statusLabels = [
                                            'submitted' => 'Baru Masuk',
                                            'under_review' => 'Diproses',
                                            'verified' => 'Terverifikasi',
                                            'rejected' => 'Ditolak',
                                        ];
                                    @endphp
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$item['data']->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$item['data']->status] ?? ucfirst($item['data']->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.reports.show', $item['data']) }}"
                                        class="text-indigo-600 hover:text-indigo-900 font-bold hover:underline">Detail
                                        &rarr;</a>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                Tidak ada laporan ditemukan pada tab ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $reports->links() }}
        </div>
    </div>
</div>