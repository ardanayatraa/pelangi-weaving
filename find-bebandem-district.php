<?php

$apiKey = 'zulFIIFAbbfb3eab5347f86fNN0ZxnNU';

// Step 1: Find Bali province ID
echo "Step 1: Finding Bali province...\n";
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://rajaongkir.komerce.id/api/v1/destination/province',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array('key: ' . $apiKey),
));
$response = curl_exec($curl);
curl_close($curl);

$data = json_decode($response, true);
$baliProvinceId = null;
if (isset($data['data'])) {
    foreach ($data['data'] as $province) {
        if (stripos($province['province'], 'bali') !== false) {
            $baliProvinceId = $province['province_id'];
            echo "Found: " . $province['province'] . " (ID: $baliProvinceId)\n\n";
            break;
        }
    }
}

// Step 2: Find Karangasem city ID
echo "Step 2: Finding Karangasem city...\n";
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://rajaongkir.komerce.id/api/v1/destination/city?province_id=' . $baliProvinceId,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array('key: ' . $apiKey),
));
$response = curl_exec($curl);
curl_close($curl);

$data = json_decode($response, true);
$karangasemCityId = null;
if (isset($data['data'])) {
    foreach ($data['data'] as $city) {
        if (stripos($city['city_name'], 'karangasem') !== false) {
            $karangasemCityId = $city['city_id'];
            echo "Found: " . $city['type'] . " " . $city['city_name'] . " (ID: $karangasemCityId)\n\n";
            break;
        }
    }
}

// Step 3: Find Bebandem district ID
echo "Step 3: Finding Bebandem district...\n";
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://rajaongkir.komerce.id/api/v1/destination/district?city_id=' . $karangasemCityId,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array('key: ' . $apiKey),
));
$response = curl_exec($curl);
curl_close($curl);

$data = json_decode($response, true);
if (isset($data['data'])) {
    foreach ($data['data'] as $district) {
        if (stripos($district['district_name'], 'bebandem') !== false) {
            echo "Found: " . $district['district_name'] . " (ID: " . $district['district_id'] . ")\n";
        }
    }
}

echo "\nDone!\n";
