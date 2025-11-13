<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class RajaOngkirService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('rajaongkir.api_key');
        $this->baseUrl = 'https://rajaongkir.komerce.id/api/v1';
    }

    /**
     * Search domestic destination
     * Returns location data with id, label, province, city, district, subdistrict, and zip_code
     */
    public function searchDestination($query, $limit = 999)
    {
        try {
            $response = Http::withHeaders([
                'key' => $this->apiKey,
            ])->get($this->baseUrl . '/destination/domestic-destination', [
                'search' => $query,
                'limit' => $limit,
                'offset' => 0,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Check if response is successful
                if (isset($data['meta']['status']) && $data['meta']['status'] === 'success') {
                    return $data['data'] ?? [];
                }
                
                \Log::warning('RajaOngkir searchDestination: No data found', [
                    'query' => $query,
                    'response' => $data
                ]);
                
                return [];
            }

            \Log::error('RajaOngkir searchDestination failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [];
        } catch (\Exception $e) {
            \Log::error('RajaOngkir searchDestination error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get provinces (Step-by-Step Method)
     */
    public function getProvinces()
    {
        return Cache::remember('rajaongkir_provinces', 86400, function () {
            try {
                $response = Http::withHeaders([
                    'key' => $this->apiKey,
                ])->get($this->baseUrl . '/destination/province');

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['data']['results'] ?? [];
                }

                return [];
            } catch (\Exception $e) {
                \Log::error('RajaOngkir getProvinces error: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Get cities by province (Step-by-Step Method)
     */
    public function getCities($provinceId)
    {
        return Cache::remember("rajaongkir_cities_{$provinceId}", 86400, function () use ($provinceId) {
            try {
                $response = Http::withHeaders([
                    'key' => $this->apiKey,
                ])->get($this->baseUrl . '/destination/city', [
                    'province_id' => $provinceId,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['data']['results'] ?? [];
                }

                return [];
            } catch (\Exception $e) {
                \Log::error('RajaOngkir getCities error: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Get districts by city (Step-by-Step Method)
     */
    public function getDistricts($cityId)
    {
        return Cache::remember("rajaongkir_districts_{$cityId}", 86400, function () use ($cityId) {
            try {
                $response = Http::withHeaders([
                    'key' => $this->apiKey,
                ])->get($this->baseUrl . '/destination/district', [
                    'city_id' => $cityId,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['data']['results'] ?? [];
                }

                return [];
            } catch (\Exception $e) {
                \Log::error('RajaOngkir getDistricts error: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Get subdistricts by district (Step-by-Step Method)
     */
    public function getSubdistricts($districtId)
    {
        return Cache::remember("rajaongkir_subdistricts_{$districtId}", 86400, function () use ($districtId) {
            try {
                $response = Http::withHeaders([
                    'key' => $this->apiKey,
                ])->get($this->baseUrl . '/destination/subdistrict', [
                    'district_id' => $districtId,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['data']['results'] ?? [];
                }

                return [];
            } catch (\Exception $e) {
                \Log::error('RajaOngkir getSubdistricts error: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Calculate domestic shipping cost
     * Parameters:
     * - origin: destination ID from search
     * - destination: destination ID from search
     * - weight: package weight in grams
     * - courier: courier code (jne, tiki, pos, jnt, etc)
     * - price: 'lowest' or 'highest' (optional)
     */
    public function calculateCost($origin, $destination, $weight, $courier, $price = null)
    {
        try {
            $params = [
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $weight,
                'courier' => strtolower($courier),
            ];
            
            if ($price) {
                $params['price'] = $price;
            }
            
            $response = Http::withHeaders([
                'key' => $this->apiKey,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])->asForm()->post($this->baseUrl . '/calculate/domestic-cost', $params);

            if ($response->successful()) {
                $data = $response->json();
                
                // Check if response is successful
                if (isset($data['meta']['status']) && $data['meta']['status'] === 'success') {
                    return $data['data'] ?? [];
                }
                
                \Log::warning('RajaOngkir calculateCost: No data found', [
                    'params' => $params,
                    'response' => $data
                ]);
                
                return [];
            }

            \Log::error('RajaOngkir calculateCost failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'params' => $params
            ]);

            return [];
        } catch (\Exception $e) {
            \Log::error('RajaOngkir calculateCost error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get available couriers
     */
    public function getAvailableCouriers()
    {
        return [
            ['code' => 'jne', 'name' => 'JNE'],
            ['code' => 'tiki', 'name' => 'TIKI'],
            ['code' => 'pos', 'name' => 'POS Indonesia'],
            ['code' => 'jnt', 'name' => 'J&T Express'],
            ['code' => 'sicepat', 'name' => 'SiCepat'],
            ['code' => 'anteraja', 'name' => 'AnterAja'],
        ];
    }

    /**
     * Track waybill
     */
    public function trackWaybill($waybillNumber, $courier)
    {
        try {
            $response = Http::withHeaders([
                'key' => $this->apiKey,
            ])->post($this->baseUrl . '/waybill', [
                'waybill' => $waybillNumber,
                'courier' => strtolower($courier),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['data'] ?? null;
            }

            return null;
        } catch (\Exception $e) {
            \Log::error('RajaOngkir trackWaybill error: ' . $e->getMessage());
            return null;
        }
    }
}
