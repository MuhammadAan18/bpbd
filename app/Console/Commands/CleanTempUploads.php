<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanTempUploads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'livewire:clean-s3-tmp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up temporary Livewire upload files in S3/MinIO older than 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $disk = Storage::disk('s3');
        $directory = config('livewire.temporary_file_upload.directory', 'livewire-tmp');

        if (!$disk->exists($directory)) {
            $this->info("Directory '{$directory}' does not exist on s3 disk.");
            return;
        }

        $files = $disk->files($directory);
        $count = 0;

        foreach ($files as $file) {
            $lastModified = $disk->lastModified($file);
            if (Carbon::createFromTimestamp($lastModified)->diffInHours(now()) > 24) {
                $disk->delete($file);
                $count++;
            }
        }

        $this->info("Successfully deleted {$count} temporary files older than 24 hours.");
    }
}
