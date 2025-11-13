<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Available Couriers
    |--------------------------------------------------------------------------
    |
    | List of available couriers from .env AVAILABLE_COURIERS
    | Format: comma separated courier codes (e.g., jne,tiki,pos,jnt)
    |
    */
    'available' => env('AVAILABLE_COURIERS', 'jne,tiki,pos,jnt'),

    /*
    |--------------------------------------------------------------------------
    | Courier Names Mapping
    |--------------------------------------------------------------------------
    |
    | Mapping of courier codes to their display names
    |
    */
    'names' => [
        'jne' => 'JNE',
        'pos' => 'POS Indonesia',
        'tiki' => 'TIKI',
        'jnt' => 'J&T Express',
        'sicepat' => 'SiCepat',
        'anteraja' => 'AnterAja',
        'wahana' => 'Wahana',
        'ninja' => 'Ninja Xpress',
        'lion' => 'Lion Parcel',
        'pcp' => 'PCP Express',
        'rpx' => 'RPX',
        'sap' => 'SAP Express',
        'jet' => 'JET Express',
        'dse' => 'DSE',
        'first' => 'First Logistics',
        'idl' => 'IDL Cargo',
        'rex' => 'REX',
        'sentral' => 'Sentral Cargo',
        'star' => 'Star Cargo',
    ],

    /*
    |--------------------------------------------------------------------------
    | Service Types
    |--------------------------------------------------------------------------
    |
    | Common service types for couriers
    |
    */
    'service_types' => [
        'REG' => 'Regular',
        'YES' => 'YES (1 hari)',
        'OKE' => 'OKE (2-3 hari)',
        'CTC' => 'City Courier',
        'CTCYES' => 'City Courier YES',
        'ONS' => 'Over Night Service',
        'SPS' => 'Super Speed',
        'ECO' => 'Economy',
        'EXPRESS' => 'Express',
    ],
];
