<?php

namespace App\Services;

use App\Models\IncidentReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;

class GoogleSheetsService
{
    protected $spreadsheetId;
    protected $sheetName;
    protected $sheetId;
    protected $sheetsService;

    public function __construct()
    {
        $this->spreadsheetId = env('GOOGLE_SHEETS_ID');
        $this->sheetName = env('GOOGLE_SHEET_NAME', 'Sheet 2');
        $this->sheetId = env('GOOGLE_SHEET_ID', 602692484);  // Use sheet ID
        $this->initializeClient();
    }

    private function initializeClient()
    {
        $config = config('google');
        $client = new Client();
        $client->setApplicationName($config['application_name']);
        $client->setScopes([Sheets::SPREADSHEETS]);

        $keyFile = storage_path('app/google-credentials.json');
        if (file_exists($keyFile)) {
            $credentials = json_decode(file_get_contents($keyFile), true);
            $client->setAuthConfig($credentials);
        }

        $this->sheetsService = new Sheets($client);
    }

    public function syncVerifiedReports()
    {
        // Fetch verified reports that haven't been synced yet
        $reports = IncidentReport::where('status', IncidentReport::STATUS_VERIFIED)
            ->whereNull('synced_to_sheets_at')
            ->with(['disasterType', 'region'])
            ->orderBy('verified_at', 'asc')
            ->get();

        $count = 0;
        foreach ($reports as $report) {
            if ($this->appendReport($report)) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Append a single report to the sheet if not already synced
     */
    public function appendReport(IncidentReport $report)
    {
        if ($report->synced_to_sheets_at) {
            return false;
        }

        try {
            $row = $this->mapReportToRow($report);

            // Convert row to pure array ensure 0-indexed sequential
            $row = array_values($row);

            // Build payload manually to bypass library serialization issues
            $payload = [
                'values' => [$row]
            ];

            // Use direct HTTP request via Guzzle
            $client = new \GuzzleHttp\Client();
            // URL format: /values/'SheetName'!A2:AP:append
            $range = sprintf("'%s'!A2:AP", $this->sheetName);
            $url = sprintf(
                'https://sheets.googleapis.com/v4/spreadsheets/%s/values/%s:append',
                $this->spreadsheetId,
                rawurlencode($range)
            );

            // Get fresh access token
            $config = config('google');
            $keyFile = storage_path('app/google-credentials.json');
            $credentials = json_decode(file_get_contents($keyFile), true);
            $googleClient = new Client();
            $googleClient->setAuthConfig($credentials);
            $googleClient->setScopes([Sheets::SPREADSHEETS]);

            $tokenResponse = $googleClient->fetchAccessTokenWithAssertion();
            $accessToken = $tokenResponse['access_token'];

            // Make direct API call
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json'
                ],
                'query' => [
                    'valueInputOption' => 'USER_ENTERED',
                    'insertDataOption' => 'INSERT_ROWS'
                ],
                'json' => $payload
            ]);

            if ($response->getStatusCode() === 200) {
                // Mark as synced
                $report->update(['synced_to_sheets_at' => now()]);
                Log::info('Report ' . $report->id . ' successfully synced to Google Sheets');
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Failed to sync report ' . $report->id . ' to Google Sheets: ' . $e->getMessage());
            return false;
        }
    }

    protected function mapReportToRow(IncidentReport $report)
    {
        $row = [];

        // Columns 0-8: Empty (Kobo/Existing data)
        $row[0] = null;
        $row[1] = null;
        $row[2] = null;
        $row[3] = null;
        $row[4] = null;
        $row[5] = null;
        $row[6] = null;
        $row[7] = null;

        $row[8] = "{$report->latitude}, {$report->longitude}";

        // Column 9: Waktu Kejadian (results.tanggal)
        $row[9] = $report->occurred_at ? Carbon::parse($report->occurred_at)->format('Y-m-d') : '';

        // Column 10: Kabupaten/Kota (results.kabupaten)
        $row[10] = $report->region ? $report->region->name : '';

        // Column 11: Kecamatan (results.kecamatan)
        $row[11] = $report->district_name;

        // Column 12: Desa/Kelurahan (results.desa)
        $row[12] = $report->village_name;

        // Column 13: Jenis Bencana (results.jenis_bencana)
        $row[13] = $report->disasterType ? $report->disasterType->name : '';

        // Column 14: Meninggal (results.dampak_jiwa/meninggal)
        $row[14] = $report->casualty_deaths ?? 0;

        // Column 15: Hilang (results.dampak_jiwa/hilang)
        $row[15] = $report->casualty_missing ?? 0;

        // Column 16: Luka (results.dampak_jiwa/luka)
        $row[16] = $report->casualty_injured ?? 0;

        // Column 17: Total Korban (results.dampak_jiwa/jiwa_terdampak)
        $row[17] = (($row[14] ?? 0) + ($row[15] ?? 0) + ($row[16] ?? 0));

        // Column 18: Rusak Berat (results.dampak_rumah/rusak_berat)
        $row[18] = $report->house_heavy_damage ?? 0;

        // Column 19: Rusak Sedang (results.dampak_rumah/rusak_sedang)
        $row[19] = $report->house_moderate_damage ?? 0;

        // Column 20: Rusak Ringan (results.dampak_rumah/rusak_ringan)
        $row[20] = $report->house_light_damage ?? 0;

        // Column 21: Terendam (results.dampak_rumah/terendam)
        $row[21] = $report->house_flooded ?? 0;

        // Column 22: Total Rumah Rusak (results.dampak_rumah/total_rumah_rusak)
        $row[22] = (($row[18] ?? 0) + ($row[19] ?? 0) + ($row[20] ?? 0) + ($row[21] ?? 0));

        // Column 23: Jembatan (results.sarpras_vital/jembatan)
        $row[23] = $report->infra_bridge_damaged ?? 0;

        // Column 24: Jalan (results.sarpras_vital/jalan)
        $row[24] = $report->infra_road_damaged ?? 0;

        // Column 25: Bendungan (results.sarpras_vital/bendungan)
        $row[25] = $report->infra_dam_damaged ?? 0;

        // Column 26: Tanggul (results.sarpras_vital/tanggul)
        $row[26] = $report->infra_embankment_damaged ?? 0;

        // Column 27: Listrik (results.sarpras_vital/listrik)
        $row[27] = $report->infra_electricity_disrupted ?? 0;

        // Column 28: Komunikasi (results.sarpras_vital/komunikasi)
        $row[28] = $report->infra_communication_disrupted ?? 0;

        // Column 29: Air Bersih (results.sarpras_vital/air_bersih)
        $row[29] = $report->infra_water_damaged ?? 0;

        // Column 30: Irigasi (results.sarpras_vital/irigasi)
        $row[30] = $report->infra_irrigation_damaged ?? 0;

        // Column 31: Hutan (results.sosial_ekonomi/hutan)
        $row[31] = $report->econ_forest_affected ?? 0;

        // Column 32: Kebun (results.sosial_ekonomi/kebun)
        $row[32] = $report->econ_plantation_affected ?? 0;

        // Column 33: Sawah (results.sosial_ekonomi/sawah)
        $row[33] = $report->econ_rice_field_affected ?? 0;

        // Column 34: Tambak (results.sosial_ekonomi/tambak)
        $row[34] = $report->econ_pond_affected ?? 0;

        // Column 35: Pabrik (results.sosial_ekonomi/pabrik)
        $row[35] = $report->econ_factory_affected ?? 0;

        // Column 36: Warung/Toko (results.sosial_ekonomi/warung)
        $row[36] = $report->econ_shop_affected ?? 0;

        // Column 37: Perkantoran (results.pelayanan_dasar/perkantoran)
        $row[37] = $report->service_office_affected ?? 0;

        // Column 38: Pasar (results.pelayanan_dasar/pasar)
        $row[38] = $report->service_market_affected ?? 0;

        // Column 39: Pendidikan (results.pelayanan_dasar/fasdik)
        $row[39] = $report->service_education_affected ?? 0;

        // Column 40: Kesehatan (results.pelayanan_dasar/faskes)
        $row[40] = $report->service_health_affected ?? 0;

        // Column 41: Peribadatan (results.pelayanan_dasar/fasibadah)
        $row[41] = $report->service_worship_affected ?? 0;

        // Columns 42-58: Empty (Kobo/Existing data)
        $row[42] = null;
        $row[43] = null;
        $row[44] = null;
        $row[45] = null;
        $row[46] = null;
        $row[47] = null;
        $row[48] = null;
        $row[49] = null;
        $row[50] = null;
        $row[51] = null;
        $row[52] = null;
        $row[53] = null;
        $row[54] = null;
        $row[55] = null;
        $row[56] = null;
        $row[57] = null;
        $row[58] = null;


        return $row;
    }

    /**
     * Get KPI data from Total_Data sheet in Google Sheets
     * Reads data from range A1:C2 (header + 1 data row)
     */
    public function getKpiData()
    {
        try {
            $range = 'Total_Data!A1:C2';
            $response = $this->sheetsService->spreadsheets_values->get(
                $this->spreadsheetId,
                $range
            );

            $values = $response->getValues();

            if (!$values || count($values) < 2) {
                return $this->getDefaultKpiData();
            }

            // Parse header and data
            $header = array_map('strtolower', array_map('trim', $values[0]));
            $data = $values[1];

            // Map columns to values based on header names
            $dataMap = [];
            foreach ($header as $index => $column) {
                $dataMap[$column] = $data[$index] ?? 0;
            }

            // Return formatted KPI data
            return [
                'success' => true,
                'data' => [
                    'incident' => [
                        'total' => (int)($dataMap['jumlah kejadian'] ?? 0),
                        'label' => 'Jumlah Kejadian',
                        'icon' => 'alert-triangle',
                        'color' => 'blue'
                    ],
                    'affected_people' => [
                        'total' => (int)($dataMap['jiwa terdampak'] ?? 0),
                        'label' => 'Jiwa Terdampak',
                        'icon' => 'users',
                        'color' => 'orange'
                    ],
                    'damaged_houses' => [
                        'total' => (int)($dataMap['total rumah terdampak'] ?? 0),
                        'label' => 'Total Rumah Terdampak',
                        'icon' => 'home',
                        'color' => 'yellow'
                    ]
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Failed to fetch KPI data from Google Sheets: ' . $e->getMessage());
            return $this->getDefaultKpiData();
        }
    }

    /**
     * Get default KPI data (fallback when Google Sheets is unavailable)
     */
    private function getDefaultKpiData()
    {
        return [
            'success' => false,
            'data' => [
                'incident' => [
                    'total' => 0,
                    'label' => 'Jumlah Kejadian',
                    'icon' => 'alert-triangle',
                    'color' => 'blue'
                ],
                'affected_people' => [
                    'total' => 0,
                    'label' => 'Jiwa Terdampak',
                    'icon' => 'users',
                    'color' => 'orange'
                ],
                'damaged_houses' => [
                    'total' => 0,
                    'label' => 'Total Rumah Terdampak',
                    'icon' => 'home',
                    'color' => 'yellow'
                ]
            ]
        ];
    }

    /**
     * Get Kobo incident reports from Google Sheets with pagination
     * Reads data from kobo_olah sheet
     */
    public function getKoboIncidents($page = 1, $limit = 10, $filters = [])
    {
        try {
            $sheetName = 'kobo_olah';
            // Range diperluas hingga BZ (index 77) karena kolom datetime ada di index 52 (kolom BA)
            // A=0, Z=25, AZ=51, BA=52, BZ=77
            $range = "{$sheetName}!A1:BZ1000";

            $response = $this->sheetsService->spreadsheets_values->get(
                $this->spreadsheetId,
                $range
            );

            $values = $response->getValues();

            if (!$values || count($values) < 2) {
                return $this->getDefaultKoboData();
            }

            // Parse header row
            $headers = array_map('strtolower', array_map('trim', $values[0]));

            // Parse data rows (skip header at index 0)
            $incidents = [];
            for ($i = 1; $i < count($values); $i++) {
                $row = $values[$i];
                $incident = $this->parseKoboRow($row, $headers);

                // Apply filters if specified
                if ($this->matchesFilters($incident, $filters)) {
                    $incidents[] = $incident;
                }
            }

            // Implement pagination
            $total = count($incidents);
            $offset = ($page - 1) * $limit;
            $paginatedIncidents = array_slice($incidents, $offset, $limit);
            $pages = ceil($total / $limit);

            return [
                'success' => true,
                'data' => $paginatedIncidents,
                'pagination' => [
                    'total' => $total,
                    'current_page' => $page,
                    'last_page' => $pages,
                    'per_page' => $limit,
                    'from' => ($total > 0) ? $offset + 1 : 0,
                    'to' => min($offset + $limit, $total)
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Failed to fetch Kobo incidents from Google Sheets: ' . $e->getMessage());
            return $this->getDefaultKoboData();
        }
    }

    /**
     * Get single Kobo incident by ID
     */
    public function getKoboIncidentById($id)
    {
        try {
            $sheetName = 'kobo_olah';
            $range = "{$sheetName}!A1:BZ1000";

            $response = $this->sheetsService->spreadsheets_values->get(
                $this->spreadsheetId,
                $range
            );

            $values = $response->getValues();

            if (!$values || count($values) < 2) {
                return ['success' => false, 'data' => null];
            }

            $headers = array_map('strtolower', array_map('trim', $values[0]));

            // Search for the record by ID (first column is typically ID)
            for ($i = 1; $i < count($values); $i++) {
                $row = $values[$i];

                // Assuming first column is ID
                if (!empty($row[0]) && (string)$row[0] === (string)$id) {
                    $incident = $this->parseKoboRow($row, $headers);
                    return [
                        'success' => true,
                        'data' => $incident
                    ];
                }
            }

            return ['success' => false, 'data' => null];
        } catch (\Exception $e) {
            Log::error('Failed to fetch Kobo incident by ID: ' . $e->getMessage());
            return ['success' => false, 'data' => null];
        }
    }

    /**
     * Parse a Kobo row and map to incident array
     * User can customize column mapping in config/kobo.php
     */
    private function parseKoboRow($row, $headers)
    {
        $mapping = config('kobo.column_mapping', $this->getDefaultColumnMapping());

        $incident = [];

        // Map columns using header names and column indices
        foreach ($mapping as $columnIndex => $fieldName) {
            $columnValue = $row[$columnIndex] ?? null;

            if ($fieldName) {
                $incident[$fieldName] = $columnValue;
            }
        }

        // Post-process: split datetime_raw into date and time fields
        // Handles formats: "09/02/2026 2:51:19", "9/2/2026 14:05:00", etc.
        if (!empty($incident['datetime_raw'])) {
            $raw = trim($incident['datetime_raw']);

            // Split on first whitespace
            $parts = preg_split('/\s+/', $raw, 2);
            $datePart = $parts[0] ?? '';   // e.g. "09/02/2026"
            $timePart = $parts[1] ?? '';   // e.g. "2:51:19"

            $incident['date'] = $datePart ?: $raw;

            // Pad hour to 2 digits so Carbon can parse reliably: "2:51:19" → "02:51:19"
            if ($timePart) {
                $timePadded = preg_replace('/^(\d):/', '0$1:', $timePart); // pad single-digit hour
                $incident['time'] = $timePadded;
            } else {
                $incident['time'] = null;
            }

            // Build a Carbon object for proper sorting (stored separately, not shown in view)
            $incident['datetime_carbon'] = null;
            if ($datePart && $timePart) {
                $fullStr   = $datePart . ' ' . $incident['time'];
                $formats   = ['d/m/Y H:i:s', 'd/m/Y H:i', 'm/d/Y H:i:s', 'd-m-Y H:i:s'];
                foreach ($formats as $fmt) {
                    try {
                        $incident['datetime_carbon'] = \Carbon\Carbon::createFromFormat($fmt, $fullStr);
                        break;
                    } catch (\Throwable $e) {
                        // try next format
                    }
                }
            }
        }

        // Add metadata
        $incident['_raw_row'] = $row;

        return $incident;
    }


    /**
     * Get default column mapping (flexible for user configuration)
     * User should define column_mapping in config/kobo.php
     */
    private function getDefaultColumnMapping()
    {
        return [
            0 => 'id',
            1 => 'date',
            2 => 'disaster_type',
            3 => 'region',
            4 => 'district',
            5 => 'village',
            6 => 'location_text',
            7 => 'latitude',
            8 => 'longitude',
            9 => 'title',
            10 => 'description',
            11 => 'affected_people',
            12 => 'casualty_deaths',
            13 => 'casualty_missing',
            14 => 'casualty_injured',
            15 => 'house_heavy_damage',
            16 => 'house_moderate_damage',
            17 => 'house_light_damage',
            18 => 'house_flooded',
        ];
    }

    /**
     * Check if incident matches filter criteria
     */
    private function matchesFilters($incident, $filters)
    {
        if (empty($filters)) {
            return true;
        }

        foreach ($filters as $field => $value) {
            if (!isset($incident[$field]) || $incident[$field] !== $value) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get Kobo statistics (summary data)
     */
    public function getKoboStats()
    {
        try {
            $koboData = $this->getKoboIncidents(1, 10000); // Get all records

            if (!$koboData['success']) {
                return ['success' => false, 'data' => null];
            }

            $incidents = $koboData['data'];
            $total = count($incidents);

            return [
                'success' => true,
                'data' => [
                    'total_records' => $total,
                    'by_type' => $this->groupBy($incidents, 'disaster_type'),
                    'by_region' => $this->groupBy($incidents, 'region'),
                    'last_updated' => now()->toIso8601String()
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Failed to get Kobo stats: ' . $e->getMessage());
            return ['success' => false, 'data' => null];
        }
    }

    /**
     * Helper: Group array by field
     */
    private function groupBy($array, $field)
    {
        $groups = [];
        foreach ($array as $item) {
            $key = $item[$field] ?? 'unknown';
            if (!isset($groups[$key])) {
                $groups[$key] = 0;
            }
            $groups[$key]++;
        }
        return $groups;
    }

    /**
     * Get default Kobo data (fallback when unavailable)
     */
    private function getDefaultKoboData()
    {
        return [
            'success' => false,
            'data' => [],
            'pagination' => [
                'total' => 0,
                'current_page' => 1,
                'last_page' => 0,
                'per_page' => 10
            ]
        ];
    }
}