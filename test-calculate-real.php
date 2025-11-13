<?php

$apiKey = 'zulFIIFAbbfb3eab5347f86fNN0ZxnNU';
$origin = 26086; // BUANA GIRI, BEBANDEM (subdistrict_id)
$destination = 17473; // Jakarta (subdistrict_id)
$weight = 1000;

echo "Testing Real Calculate with Subdistrict IDs\n";
echo "===========================================\n\n";

// Test with district endpoint using subdistrict IDs
echo "Origin: $origin (Buana Giri, Bebandem)\n";
echo "Destination: $destination (Jakarta)\n";
echo "Weight: $weight grams\n\n";

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://rajaongkir.komerce.id/api/v1/calculate/district/domestic-cost',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        'origin' => $origin,
        'destination' => $destination,
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

if ($httpCode == 200) {
    $data = json_decode($response, true);
    if (isset($data['data']) && count($data['data']) > 0) {
        echo "✅ SUCCESS! Found " . count($data['data']) . " shipping options:\n\n";
        foreach ($data['data'] as $option) {
            echo sprintf("- %s (%s) - %s: Rp %s (%s)\n", 
                $option['name'],
                $option['code'],
                $option['service'],
                number_format($option['cost'], 0, ',', '.'),
                $option['etd']
            );
        }
    } else {
        echo "Response: $response\n";
    }
} else {
    echo "❌ FAILED\n";
    echo "Response: $response\n";
}

echo "\n";
