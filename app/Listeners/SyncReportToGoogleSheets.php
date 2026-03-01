<?php

namespace App\Listeners;

use App\Events\ReportVerified;
use App\Services\GoogleSheetsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SyncReportToGoogleSheets implements ShouldQueue
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
     * Handle the event.
     */
    public function handle(ReportVerified $event): void
    {
        try {
            // Sync the verified report to Google Sheets
            $this->sheetsService->appendReport($event->report);
            Log::info('Report ' . $event->report->id . ' synced to Google Sheets automatically.');
        } catch (\Exception $e) {
            Log::error('Error syncing report to Google Sheets: ' . $e->getMessage());
            // Optionally re-throw or retry
            $this->release(60); // Retry after 60 seconds
        }
    }
}
