<?php

define('EARTH_R', 6371.0);

function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
{
    $lat1Rad = deg2rad($lat1);
    $lat2Rad = deg2rad($lat2);
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat / 2) ** 2
        + cos($lat1Rad) * cos($lat2Rad) * sin($dLon / 2) ** 2;

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return round(EARTH_R * $c, 4);
}

function assertApprox(string $label, float $actual, float $expected, float $tolerance = 0.5): void
{
    $passed = abs($actual - $expected) <= $tolerance;
    $icon = $passed ? 'OK' : 'FAIL';
    printf("  %s %-45s | Aktual: %6.2f km\n", $icon, $label, $actual);
}

$monas = ['lat' => -6.1754, 'lon' => 106.8272];
$sudirman = ['lat' => -6.2088, 'lon' => 106.8456];
$kemang = ['lat' => -6.2607, 'lon' => 106.8187];
$blokM = ['lat' => -6.2444, 'lon' => 106.7996];

$d1 = haversineDistance($monas['lat'], $monas['lon'], $sudirman['lat'], $sudirman['lon']);
assertApprox("Monas -> Sudirman ", $d1, 4.0, 2.0);

$d2 = haversineDistance($monas['lat'], $monas['lon'], $kemang['lat'], $kemang['lon']);
assertApprox("Monas -> Kemang   ", $d2, 10.0, 3.0);

$d3 = haversineDistance($monas['lat'], $monas['lon'], $blokM['lat'], $blokM['lon']);
assertApprox("Monas -> Blok M   ", $d3, 8.0, 3.0);

$userLat = $monas['lat'];
$userLon = $monas['lon'];
$radius = 5.0;

$stores = [
    ['name' => 'RM Padang Barokah (Monas)', 'lat' => -6.1754, 'lon' => 106.8272],
    ['name' => 'Sunrise Bakery (Sudirman)', 'lat' => -6.2088, 'lon' => 106.8456],
    ['name' => 'FreshMart Kemang', 'lat' => -6.2607, 'lon' => 106.8187],
    ['name' => 'Kopi & Roti Blok M', 'lat' => -6.2444, 'lon' => 106.7996],
];

foreach ($stores as $store) {
    $dist = haversineDistance($userLat, $userLon, $store['lat'], $store['lon']);
    $inRadius = $dist <= $radius;
    $icon = $inRadius ? 'OK' : 'X';
    printf("  %s %-40s | %.2f km\n", $icon, $store['name'], $dist);
}

function calculateDynamicDiscount(
    float $originalPrice,
    float $timeProgress,
    int $stock,
    string $category,
    bool $isPostDeadline = false
    ): array
{
    $BASE_DISCOUNT = 0.10;
    $MAX_TIME_DISCOUNT = 0.60;
    $MAX_TOTAL_DISCOUNT = 0.90;
    $PRICE_FLOOR_RATIO = 0.10;
    $POST_DEADLINE_RATIO = 0.05;

    $CATEGORY_MULTIPLIERS = [
        'dairy' => 1.20,
        'meat' => 1.20,
        'seafood' => 1.20,
        'bakery' => 1.10,
        'produce' => 1.05,
        'general' => 1.00,
        'beverage' => 0.95,
    ];

    if ($isPostDeadline) {
        $price = round($originalPrice * $POST_DEADLINE_RATIO, 2);
        return ['price' => $price, 'discount_pct' => 95.0];
    }

    $timeDiscount = ($timeProgress ** 2) * $MAX_TIME_DISCOUNT;

    if ($stock > 50)
        $stockDiscount = 0.20;
    elseif ($stock > 20)
        $stockDiscount = 0.15;
    elseif ($stock > 10)
        $stockDiscount = 0.05;
    else
        $stockDiscount = 0.00;

    $multiplier = $CATEGORY_MULTIPLIERS[strtolower($category)] ?? 1.0;
    $totalDiscount = ($BASE_DISCOUNT + $timeDiscount + $stockDiscount) * $multiplier;
    $totalDiscount = min($totalDiscount, $MAX_TOTAL_DISCOUNT);

    $finalPrice = $originalPrice * (1 - $totalDiscount);
    $priceFloor = $originalPrice * $PRICE_FLOOR_RATIO;
    $finalPrice = max($finalPrice, $priceFloor);

    return [
        'price' => round($finalPrice, 2),
        'discount_pct' => round($totalDiscount * 100, 1)
    ];
}

$scenarios = [
    ['original' => 10000, 'time_progress' => 0.0, 'stock' => 30, 'category' => 'general', 'post_deadline' => false],
    ['original' => 20000, 'time_progress' => 0.5, 'stock' => 60, 'category' => 'dairy', 'post_deadline' => false],
    ['original' => 15000, 'time_progress' => 0.9, 'stock' => 15, 'category' => 'bakery', 'post_deadline' => false],
    ['original' => 85000, 'time_progress' => 0.99, 'stock' => 8, 'category' => 'seafood', 'post_deadline' => false],
    ['original' => 50000, 'time_progress' => 1.0, 'stock' => 5, 'category' => 'general', 'post_deadline' => true],
];

foreach ($scenarios as $s) {
    $result = calculateDynamicDiscount($s['original'], $s['time_progress'], $s['stock'], $s['category'], $s['post_deadline']);
    printf("  OK %-40s | Rp %s | %s%%\n", 'Test', number_format($result['price'], 0, ',', '.'), $result['discount_pct']);
}
