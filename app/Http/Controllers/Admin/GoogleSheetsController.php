<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GoogleSheetsService;
use Illuminate\Support\Facades\Log;

class GoogleSheetsController extends Controller
{
    public function sync(GoogleSheetsService $sheetsService)
    {
        try {
            $count = $sheetsService->syncVerifiedReports();
            
            return back()->with('success', "Berhasil menyinkronkan {$count} laporan ke Google Sheets.");
        } catch (\Exception $e) {
            Log::error('Google Sheets Sync Error: ' . $e->getMessage());
            
            return back()->with('error', 'Gagal menyinkronkan data: ' . $e->getMessage());
        }
    }
}
