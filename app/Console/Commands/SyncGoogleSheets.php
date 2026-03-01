<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoogleSheetsService;

class SyncGoogleSheets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-google-sheets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync verified reports to Google Sheets for Looker Studio';

    /**
     * Execute the console command.
     */
    public function handle(GoogleSheetsService $sheetsService)
    {
        $this->info('Starting sync to Google Sheets...');

        try {
            $count = $sheetsService->syncVerifiedReports();
            $this->info("Successfully synced {$count} reports.");
        } catch (\Exception $e) {
            $this->error("Sync failed: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
