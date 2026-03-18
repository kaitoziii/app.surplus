<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\GeospatialService;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'latitude',
        'longitude',
        'category',
        'phone',
        'logo_url',
        'is_open',
        'description',
        'open_time',
        'close_time',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'is_open' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function availableProducts()
    {
        return $this->hasMany(Product::class)
            ->where('is_available', true)
            ->where('pickup_deadline', '>', now())
            ->orderBy('pickup_deadline');
    }

    public function scopeWithinRadius($query, float $latitude, float $longitude, float $radiusKm = 10)
    {
        $haversineSQL = '(6371 * acos(
            GREATEST(
                -1,
                LEAST(
                    1,
                    cos(radians(?))
                    * cos(radians(latitude))
                    * cos(radians(longitude) - radians(?))
                    + sin(radians(?))
                    * sin(radians(latitude))
                )
            )
        ))';

        $bindings = [$latitude, $longitude, $latitude];

        return $query
            ->selectRaw("stores.*, ({$haversineSQL}) AS distance_km", $bindings)
            ->whereRaw("({$haversineSQL}) <= ?", array_merge($bindings, [$radiusKm]))
            ->orderBy('distance_km');
    }

    public function scopeOpen($query)
    {
        return $query->where('is_open', true);
    }

    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}