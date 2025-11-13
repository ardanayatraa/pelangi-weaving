<?php

// Test RajaOngkir API
$apiKey = 'zulFIIFAbbfb3eab5347f86fNN0ZxnNU';
$origin = 26085; // Bebandem
$destination = 17547; // Jakarta Selatan
$weight = 1000; // 1kg
$courier = 'jne';

echo "Testing RajaOngkir API\n";
echo "=====================\n\n";

// Test 1: Search Destination
echo "1. Testing Search Destination (Bebandem)...\n";
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://rajaongkir.komerce.id/api/v1/destination/domestic-destination?search=bebandem&limit=5',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array('key: ' . $apiKey),
));
$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

echo "HTTP Code: $httpCode\n";
echo "Response: " . substr($response, 0, 200) . "...\n\n";

// Test 2: Get District ID for Bebandem
echo "2. Getting District ID for Bebandem...\n";
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://rajaongkir.komerce.id/api/v1/destination/district?city_id=20',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array('key: ' . $apiKey),
));
$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

echo "HTTP Code: $httpCode\n";
$data = json_decode($response, true);
if (isset($data['data'])) {
    foreach ($data['data'] as $district) {
        if (stripos($district['district_name'], 'bebandem') !== false) {
            echo "Found: " . $district['district_name'] . " (ID: " . $district['district_id'] . ")\n";
        }
    }
}
echo "\n";

// Test 3: Calculate Cost using District endpoint
echo "3. Testing Calculate Cost with DISTRICT endpoint...\n";
$originDistrict = 1391; // Example from docs
$destDistrict = 1376; // Example from docs
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://rajaongkir.komerce.id/api/v1/calculate/district/domestic-cost',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        'origin' => $originDistrict,
        'destination' => $destDistrict,
        'weight' => $weight,
        'courier' => 'jne:tiki:pos:jnt:ninja',
        'price' => 'lowest',
    ]),
    CURLOPT_HTTPHEADER => array(
        'key: ' . $apiKey,
        'Content-Type: application/x-www-form-urlencoded'
    ),
));
$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

echo "HTTP Code: $httpCode\n";
echo "Response: " . substr($response, 0, 500) . "...\n\n";

// Test 4: OLD endpoint (will fail)
echo "4. Testing OLD Calculate Cost endpoint (should fail)...\n";
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        'origin' => $origin,
        'destination' => $destination,
        'weight' => $weight,
        'courier' => $courier,
        'price' => 'lowest',
    ]),
    CURLOPT_HTTPHEADER => array(
        'key: ' . $apiKey,
        'Content-Type: application/x-www-form-urlencoded'
    ),
));
$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n\n";

echo "=====================\n";
echo "Test completed!\n";
