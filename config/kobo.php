<?php

/**
 * Kobo Data Configuration
 *
 * Define column mappings for kobo_olah sheet
 * User can customize the column_mapping array based on actual Kobo sheet structure
 *
 * Example:
 * Column 0 (A) -> id
 * Column 1 (B) -> date
 * Column 2 (C) -> disaster_type
 * etc.
 */

return [
    // Google Sheets configuration
    'sheets_id' => env('GOOGLE_SHEETS_ID'),
    'sheet_name' => 'kobo_olah',

    // Column mapping: array index (0-based) => field name
    // IMPORTANT: Based on actual kobo_olah sheet structure from data analysis
    // NOTE: Fields not in Kobo (title, location_text, description) will show default values
    'column_mapping' => [
        // Core fields from kobo_olah (verified with actual data)
        3  => 'id',                              // UUID identifier
        52 => 'datetime_raw',                   // Tanggal & waktu gabungan (e.g. "09/02/2026 2:51:19")
        13 => 'disaster_type',                  // Type of disaster (banjir, gempa, etc)
        11 => 'region',                         // Region/Province (Mataram, etc)
        12 => 'district',                       // District/City
        10 => 'village',                        // Village name
        8 => 'location_coordinates',            // Latitude & Longitude (combined: "-8.612583 116.102646 0 0")

        // Impact fields - Affected People & Casualties
        18 => 'affected_people',                // Total people affected
        15 => 'casualty_deaths',                // Deaths
        16 => 'casualty_missing',               // Missing persons
        17 => 'casualty_injured',               // Injured

        // House Damage
        19 => 'house_heavy_damage',             // Heavy damage
        20 => 'house_moderate_damage',          // Moderate damage
        21 => 'house_light_damage',             // Light damage
        22 => 'house_flooded',                  // Flooded houses

        // Infrastructure Damage
        23 => 'infra_bridge_damaged',
        24 => 'infra_road_damaged',
        25 => 'infra_dam_damaged',
        // Columns continue based on actual sheet structure

        // NOTE: NOT IN KOBO - Will show default values:
        // - title: 'Laporan Kejadian' (default)
        // - description: 'Tidak ada deskripsi' (default)
        // - location_text: 'Lokasi tidak diketahui' (default)
    ],

    // Cache configuration
    'cache_ttl' => 300, // 5 minutes

    // Pagination configuration
    'pagination' => [
        'default_limit' => 10,
        'max_limit' => 100,
    ],

    // Display configuration (used in frontend)
    'display' => [
        // Columns to show in public incidents list (only fields that exist in Kobo)
        'public_columns' => [
            'date',
            'disaster_type',
            'region',
            'location_coordinates',  // Latitude & Longitude combined
            'affected_people',
        ],

        // Columns to show in admin panel (comprehensive view)
        'admin_columns' => [
            'id',
            'date',
            'disaster_type',
            'region',
            'district',
            'village',
            'location_coordinates',
            'affected_people',
            'casualty_deaths',
            'casualty_missing',
            'casualty_injured',
            'house_heavy_damage',
            'house_moderate_damage',
            'house_light_damage',
            'house_flooded',
            'infra_bridge_damaged',
            'infra_road_damaged',
            'infra_dam_damaged',
            'infra_embankment_damaged',
            'infra_electricity_disrupted',
            'infra_communication_disrupted',
            'infra_water_damaged',
            'infra_irrigation_damaged',
            'econ_forest_affected',
            'econ_plantation_affected',
            'econ_rice_field_affected',
            'econ_pond_affected',
            'econ_factory_affected',
            'econ_shop_affected',
            'services_office_affected',
            'services_market_affected',
            'services_education_affected',
            'services_health_affected',
            'services_worship_affected',
        ],
    ],
];
