<?php

namespace App\Services;

use App\Models\Store;
use Illuminate\Database\Eloquent\Collection;

class GeospatialService
{
    const EARTH_RADIUS_KM = 6371.0;

    public function distanceBetween(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
        ): float
    {
        $lat1Rad = deg2rad($lat1);
        $lat2Rad = deg2rad($lat2);
        $deltaLat = deg2rad($lat2 - $lat1);
        $deltaLon = deg2rad($lon2 - $lon1);

        $a = sin($deltaLat / 2) ** 2
            + cos($lat1Rad) * cos($lat2Rad) * sin($deltaLon / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round(self::EARTH_RADIUS_KM * $c, 4);
    }

    public function isWithinRadius(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2,
        float $radiusKm
        ): bool
    {
        return $this->distanceBetween($lat1, $lon1, $lat2, $lon2) <= $radiusKm;
    }

    public function findStoresNearby(
        float $latitude,
        float $longitude,
        float $radiusKm = 10.0,
        bool $openOnly = true
        ): Collection
    {
        $query = Store::withinRadius($latitude, $longitude, $radiusKm);

        if ($openOnly) {
            $query->where('is_open', true);
        }

        return $query->get();
    }

    public function boundingBox(float $latitude, float $longitude, float $radiusKm): array
    {
        $deltaLat = rad2deg($radiusKm / self::EARTH_RADIUS_KM);
        $deltaLon = rad2deg($radiusKm / (self::EARTH_RADIUS_KM * cos(deg2rad($latitude))));

        return [
            'minLat' => $latitude - $deltaLat,
            'maxLat' => $latitude + $deltaLat,
            'minLon' => $longitude - $deltaLon,
            'maxLon' => $longitude + $deltaLon,
        ];
    }

    public function formatDistance(float $distanceKm): string
    {
        if ($distanceKm < 1.0) {
            return round($distanceKm * 1000) . ' m';
        }

        if ($distanceKm < 10.0) {
            return number_format($distanceKm, 1) . ' km';
        }

        return round($distanceKm) . ' km';
    }
}