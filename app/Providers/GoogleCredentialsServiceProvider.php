<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class GoogleCredentialsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $base64 = env('GOOGLE_CREDENTIALS_BASE64');

        if ($base64) {
            $path = storage_path('app/google-credentials.json');
            file_put_contents($path, base64_decode($base64));
        }
    }
}