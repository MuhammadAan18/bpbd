<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		{{-- Back Button --}}
		<div class="pb-6">
			<h2
				class="text-2xl sm:text-4xl md:text-6xl font-extrabold text-center text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-500 ">
				Rincian Dampak Bencana</h2>
			
			<p class=" pt-3 text-sm sm:text-base md:text-lg text-gray-600 text-center max-w-2xl mx-auto">Detail Dampak Bencana</p>

		</div>
		<div class="mb-6">
			<a href="{{ route('public.incidents') }}"
				class="inline-flex items-center px-4 py-2 bg-white/80 backdrop-blur-sm rounded-lg shadow-sm hover:shadow-md transition-all duration-200 text-gray-700 hover:text-gray-900">
				<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
				</svg>
				Kembali ke Daftar Bencana
			</a>
		</div>

		@if($incident)
			{{-- Incident Header --}}
			<div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl overflow-hidden mb-8">
				<div class="p-8">
					<div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
						{{-- Incident Info --}}
						<div class="flex-1">
							<div class="flex items-center gap-3 mb-4">
								<span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
									{{ $source === 'website' ? 'Website' : 'Kobo' }}
								</span>
								<span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-semibold rounded-full">
									@if($source === 'website')
										{{ $incident->disasterType->slug }}
									@else
										{{ $incident['disaster_type'] ?? 'Bencana' }}
									@endif
								</span>
							</div>

							<h1 class="text-3xl font-bold text-gray-900 mb-2">
								@if($source === 'website')
									{{ $incident->title }}
								@else
									Laporan {{ ucfirst($incident['disaster_type'] ?? 'Bencana') }} di
									{{ $incident['district'] ?? $incident['region'] ?? 'Lokasi Tidak Diketahui' }}
								@endif
							</h1>

							<div class="flex items-center text-gray-600 mb-4">
								<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
										d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
								</svg>
								@if($source === 'website')
									{{ $incident->occurred_at->format('d M Y, H:i') }}
								@else
									{{ $incident['date'] ?? '-' }}
									@if(!empty($incident['time']))
										, {{ $incident['time'] }}
									@endif
								@endif
							</div>

							<div class="flex items-center text-gray-600 mb-6">
								<svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor"
									viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
										d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
										d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
								</svg>
								@if($source === 'website')
									{{ $incident->location_text }}, {{ $incident->region->name }}
								@else
									{{ $incident['village'] ?? '' }}{{ !empty($incident['village']) && !empty($incident['district']) ? ', ' : '' }}{{ $incident['district'] ?? '' }}{{ !empty($incident['district']) && !empty($incident['region']) ? ', ' : '' }}{{ $incident['region'] ?? '' }}
								@endif
							</div>

							@if($source === 'website')
								<p class="text-gray-700 leading-relaxed">
									{{ $incident->description }}
								</p>
							@else
								<p class="text-gray-700 leading-relaxed">
									Laporan hasil dari pengisian form kobotool oleh relawan
								</p>
							@endif
						</div>

						{{-- Image/Thumbnail --}}
						<div class="lg:w-96">
							@if($source === 'website')
								@php $thumb = $incident->attachments->first(); @endphp
								@if($thumb)
									<img src="{{ Storage::url($thumb->file_path) }}"
										class="w-full h-48 object-cover rounded-xl shadow-lg">
								@else
									<div class="w-full h-48 bg-gray-200 rounded-xl flex items-center justify-center">
										<svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
												d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
										</svg>
									</div>
								@endif
							@else
								<div
									class="w-full h-48 bg-gradient-to-br from-blue-100 to-indigo-200 rounded-xl flex items-center justify-center">
									<div class="text-center text-blue-300 opacity-60">
										<svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
												d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
										</svg>
									</div>
								</div>
							@endif
						</div>
					</div>
				</div>
			</div>

			{{-- Impact Visualization --}}
			<div class="space-y-8">

				{{-- Casualties Chart --}}
				<div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-8">
					<h3 class="text-xl font-semibold text-gray-800 mb-6 text-center">Korban Jiwa</h3>
					<div class="h-64">
						<canvas id="casualtiesChart"></canvas>
					</div>
				</div>

				{{-- House Damage Chart --}}
				<div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-8">
					<h3 class="text-xl font-semibold text-gray-800 mb-6 text-center">Kerusakan Rumah</h3>
					<div class="h-64">
						<canvas id="houseDamageChart"></canvas>
					</div>
				</div>

				{{-- Infrastructure Damage Chart --}}
				<div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-8">
					<h3 class="text-xl font-semibold text-gray-800 mb-6 text-center">Kerusakan Infrastruktur</h3>
					<div class="h-64">
						<canvas id="infrastructureChart"></canvas>
					</div>
				</div>
			</div>
		@else
			<div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-16 text-center">
				<svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
						d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
				</svg>
				<h2 class="text-2xl font-bold text-gray-900 mb-2">Data Tidak Ditemukan</h2>
				<p class="text-gray-600">Kejadian bencana yang Anda cari tidak dapat ditemukan.</p>
			</div>
		@endif
	</div>
</div>

@push('scripts')
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			@if($impactData)
				console.log('Impact data found:', @json($impactData));

				// Helper function to check if data has non-zero values
				function hasData(dataArray) {
					return dataArray.some(value => value > 0);
				}

				// Casualties Chart
				const casualtiesCtx = document.getElementById('casualtiesChart');
				if (casualtiesCtx) {
					const casualtiesData = @json($impactData['casualties']['data']);
					console.log('Casualties data:', casualtiesData);

					if (hasData(casualtiesData)) {
						try {
							new Chart(casualtiesCtx, {
								type: 'bar',
								data: {
									labels: @json($impactData['casualties']['labels']),
									datasets: [{
										label: 'Jumlah',
										data: casualtiesData,
										backgroundColor: @json($impactData['casualties']['colors']),
										borderWidth: 1
									}]
								},
								options: {
									responsive: true,
									maintainAspectRatio: false,
									scales: {
										y: {
											beginAtZero: true,
											ticks: {
												stepSize: 1
											}
										}
									},
									plugins: {
										legend: {
											display: false
										}
									}
								}
							});
							console.log('Casualties chart created successfully');
						} catch (error) {
							console.error('Error creating casualties chart:', error);
						}
					} else {
						console.log('No casualties data, showing message');
						casualtiesCtx.parentElement.innerHTML = '<div class="flex items-center justify-center h-64 text-gray-500"><div class="text-center"><svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg><p>Tidak ada data korban jiwa</p></div></div>';
					}
				} else {
					console.error('Casualties chart canvas not found');
				}

				// House Damage Chart
				const houseDamageCtx = document.getElementById('houseDamageChart');
				if (houseDamageCtx) {
					const houseDamageData = @json($impactData['house_damage']['data']);
					console.log('House damage data:', houseDamageData);

					if (hasData(houseDamageData)) {
						try {
							new Chart(houseDamageCtx, {
								type: 'doughnut',
								data: {
									labels: @json($impactData['house_damage']['labels']),
									datasets: [{
										data: houseDamageData,
										backgroundColor: @json($impactData['house_damage']['colors']),
										borderWidth: 2
									}]
								},
								options: {
									responsive: true,
									maintainAspectRatio: false,
									plugins: {
										legend: {
											position: 'bottom'
										}
									}
								}
							});
							console.log('House damage chart created successfully');
						} catch (error) {
							console.error('Error creating house damage chart:', error);
						}
					} else {
						console.log('No house damage data, showing message');
						houseDamageCtx.parentElement.innerHTML = '<div class="flex items-center justify-center h-64 text-gray-500"><div class="text-center"><svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg><p>Tidak ada data kerusakan rumah</p></div></div>';
					}
				} else {
					console.error('House damage chart canvas not found');
				}

				// Infrastructure Chart
				const infrastructureCtx = document.getElementById('infrastructureChart');
				if (infrastructureCtx) {
					const infrastructureData = @json($impactData['infrastructure']['data']);
					console.log('Infrastructure data:', infrastructureData);

					if (hasData(infrastructureData)) {
						try {
							new Chart(infrastructureCtx, {
								type: 'bar',
								data: {
									labels: @json($impactData['infrastructure']['labels']),
									datasets: [{
										label: 'Jumlah',
										data: infrastructureData,
										backgroundColor: @json($impactData['infrastructure']['colors']),
										borderWidth: 1
									}]
								},
								options: {
									responsive: true,
									maintainAspectRatio: false,
									scales: {
										y: {
											beginAtZero: true,
											ticks: {
												stepSize: 1
											}
										}
									},
									plugins: {
										legend: {
											display: false
										}
									}
								}
							});
							console.log('Infrastructure chart created successfully');
						} catch (error) {
							console.error('Error creating infrastructure chart:', error);
						}
					} else {
						console.log('No infrastructure data, showing message');
						infrastructureCtx.parentElement.innerHTML = '<div class="flex items-center justify-center h-64 text-gray-500"><div class="text-center"><svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg><p>Tidak ada data kerusakan infrastruktur</p></div></div>';
					}
				} else {
					console.error('Infrastructure chart canvas not found');
				}
			@endif
			});
	</script>
@endpush