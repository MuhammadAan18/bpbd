<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\IncidentReport;
use App\Services\GoogleSheetsService;

class IncidentShow extends Component
{
	public $source;
	public $id;
	public $incident = null;
	public $impactData = [];

	public function mount($source, $id)
	{
		$this->source = $source;
		$this->id = $id;

		if ($source === 'website') {
			$this->loadWebsiteIncident();
		} elseif ($source === 'kobo') {
			$this->loadKoboIncident();
		} else {
			abort(404);
		}

		if (!$this->incident) {
			abort(404);
		}

		$this->prepareImpactData();

		// Debug: Log the prepared impact data
		// \Log::info('Impact Data Prepared:', ['impactData' => $this->impactData]);
	}

	private function loadWebsiteIncident()
	{
		$this->incident = IncidentReport::with(['disasterType', 'region', 'attachments'])
			->where('status', 'verified')
			->find($this->id);
	}

	private function loadKoboIncident()
	{
		try {
			$service = app(GoogleSheetsService::class);
			$result = $service->getKoboIncidents(1, 1000);

			if (!empty($result['success']) && !empty($result['data'])) {
				$incidents = collect($result['data']);

				// For Kobo, always use index-based (1-based)
				$index = (int) $this->id - 1;
				$this->incident = $incidents->get($index);
			}
		} catch (\Throwable $e) {
			\Log::error('[IncidentShow] loadKoboIncident: ' . $e->getMessage());
		}
	}

	private function safeInt($value)
	{
		if (is_null($value) || $value === '') {
			return 0;
		}
		return (int) $value;
	}

	private function prepareImpactData()
	{
		if ($this->source === 'website' && $this->incident) {
			$this->impactData = [
				'casualties' => [
					'labels' => ['Meninggal', 'Hilang', 'Luka'],
					'data' => [
						(int) ($this->incident->casualty_deaths ?? 0),
						(int) ($this->incident->casualty_missing ?? 0),
						(int) ($this->incident->casualty_injured ?? 0),
					],
					'colors' => ['#ef4444', '#f97316', '#eab308'],
				],
				'house_damage' => [
					'labels' => ['Rusak Berat', 'Rusak Sedang', 'Rusak Ringan', 'Tergenang'],
					'data' => [
						(int) ($this->incident->house_heavy_damage ?? 0),
						(int) ($this->incident->house_moderate_damage ?? 0),
						(int) ($this->incident->house_light_damage ?? 0),
						(int) ($this->incident->house_flooded ?? 0),
					],
					'colors' => ['#dc2626', '#ea580c', '#ca8a04', '#2563eb'],
				],
				'infrastructure' => [
					'labels' => ['Jembatan', 'Jalan', 'Bendungan', 'Tanggul', 'Listrik', 'Komunikasi'],
					'data' => [
						(int) ($this->incident->infra_bridge_damaged ?? 0),
						(int) ($this->incident->infra_road_damaged ?? 0),
						(int) ($this->incident->infra_dam_damaged ?? 0),
						(int) ($this->incident->infra_embankment_damaged ?? 0),
						(int) ($this->incident->infra_electricity_disrupted ?? 0),
						(int) ($this->incident->infra_communication_disrupted ?? 0),
					],
					'colors' => ['#7c2d12', '#9a3412', '#c2410c', '#ea580c', '#f97316', '#fb923c'],
				],
			];
		} elseif ($this->source === 'kobo' && $this->incident) {
			// Untuk Kobo, kita perlu mapping field yang ada
			// Asumsi field Kobo memiliki data serupa
			$this->impactData = [
				'casualties' => [
					'labels' => ['Meninggal', 'Hilang', 'Luka'],
					'data' => [
						$this->safeInt($this->incident['casualty_deaths'] ?? 0),
						$this->safeInt($this->incident['casualty_missing'] ?? 0),
						$this->safeInt($this->incident['casualty_injured'] ?? 0),
					],
					'colors' => ['#ef4444', '#f97316', '#eab308'],
				],
				'house_damage' => [
					'labels' => ['Rusak Berat', 'Rusak Sedang', 'Rusak Ringan', 'Tergenang'],
					'data' => [
						$this->safeInt($this->incident['house_heavy_damage'] ?? 0),
						$this->safeInt($this->incident['house_moderate_damage'] ?? 0),
						$this->safeInt($this->incident['house_light_damage'] ?? 0),
						$this->safeInt($this->incident['house_flooded'] ?? 0),
					],
					'colors' => ['#dc2626', '#ea580c', '#ca8a04', '#2563eb'],
				],
				'infrastructure' => [
					'labels' => ['Jembatan', 'Jalan', 'Bendungan', 'Tanggul', 'Listrik', 'Komunikasi'],
					'data' => [
						$this->safeInt($this->incident['infra_bridge_damaged'] ?? 0),
						$this->safeInt($this->incident['infra_road_damaged'] ?? 0),
						$this->safeInt($this->incident['infra_dam_damaged'] ?? 0),
						$this->safeInt($this->incident['infra_embankment_damaged'] ?? 0),
						$this->safeInt($this->incident['infra_electricity_disrupted'] ?? 0),
						$this->safeInt($this->incident['infra_communication_disrupted'] ?? 0),
					],
					'colors' => ['#7c2d12', '#9a3412', '#c2410c', '#ea580c', '#f97316', '#fb923c'],
				],
			];
		}
	}

	public function render()
	{
		return view('livewire.public.incident-show');
	}
}