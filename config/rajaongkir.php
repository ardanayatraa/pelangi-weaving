<?php

return [
    'api_key' => env('RAJAONGKIR_API_KEY'),
    'base_url' => env('RAJAONGKIR_BASE_URL', 'https://rajaongkir.komerce.id/api/v1'),
    
    // Default origin (your store location)
    // Get subdistrict_id from RajaOngkir dashboard or API
    'origin_subdistrict_id' => env('RAJAONGKIR_ORIGIN_SUBDISTRICT_ID', '574'), // Jakarta Pusat default
];
