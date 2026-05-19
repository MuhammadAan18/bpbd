<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class GoogleCredentialsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $base64 = env('GOOGLE_CREDENTIALS_BASE64');

        if ($base64) {
            $path = storage_path('storage/app/google-credentials.json');
            
            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }

            file_put_contents($path, base64_decode($base64));
        }
    }
}