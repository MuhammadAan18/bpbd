<?php

namespace App\Listeners;

use App\Events\ReportUpdated;
use App\Services\GoogleSheetsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SyncReportUpdateToGoogleSheets implements ShouldQueue
{
	use InteractsWithQueue;

	/**
	 * Create the event listener.
	 */
	public function __construct(private GoogleSheetsService $sheetsService)
	{
		//
	}

	/**
	 * Handle the event - sync updated report data back to Google Sheets.
	 */
	public function handle(ReportUpdated $event): void
	{
		try {
			// Call the sync update method
			$this->sheetsService->updateReportInSheets($event->report);

			// Invalidate KPI cache so dashboard shows updated values
			$this->sheetsService->invalidateKpiCache();

			Log::info('Report ' . $event->report->id . ' updates synced to Google Sheets.');
		} catch (\Exception $e) {
			Log::error('Error syncing report updates to Google Sheets: ' . $e->getMessage());
			// Retry after 60 seconds
			$this->release(60);
		}
	}
}
