<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\DynamicDiscountService;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'name',
        'description',
        'original_price',
        'final_price',
        'discount_percentage',
        'stock',
        'unit',
        'category',
        'expiry_date',
        'pickup_deadline',
        'image_url',
        'is_available',
        'reserved_stock',
    ];

    protected $casts = [
        'original_price' => 'float',
        'stock' => 'integer',
        'reserved_stock' => 'integer',
        'expiry_date' => 'date',
        'pickup_deadline' => 'datetime',
        'is_available' => 'boolean',
    ];

    protected $appends = [
        'dynamic_price',
        'discount_percentage',
        'urgency_level',
        'time_remaining_minutes',
        'available_stock',
    ];

    // ===============================
    // RELATION
    // ===============================
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    // ===============================
    // DYNAMIC PRICE
    // ===============================
    public function getDynamicPriceAttribute(): float
    {
        return app(DynamicDiscountService::class)->calculatePrice($this);
    }

    public function getDiscountPercentageAttribute(): float
    {
        $dynamicPrice = $this->dynamic_price;
        $originalPrice = $this->original_price;

        return $originalPrice > 0
            ? round((1 - $dynamicPrice / $originalPrice) * 100, 1)
            : 0;
    }

    // ===============================
    // URGENCY SYSTEM
    // ===============================
    public function getUrgencyLevelAttribute(): string
    {
        $minutesLeft = $this->time_remaining_minutes;

        if ($minutesLeft <= 0) return 'expired';
        if ($minutesLeft <= 60) return 'critical';
        if ($minutesLeft <= 180) return 'high';
        if ($minutesLeft <= 360) return 'medium';

        return 'low';
    }

    public function getTimeRemainingMinutesAttribute(): int
    {
        return (int) now()->diffInMinutes($this->pickup_deadline, false);
    }

    // ===============================
    // STOCK SYSTEM
    // ===============================
    public function getAvailableStockAttribute(): int
    {
        return max(0, $this->stock - $this->reserved_stock);
    }

    public function setStockAttribute($value)
    {
        $this->attributes['stock'] = max(0, $value);
    }

    public function setReservedStockAttribute($value)
    {
        $this->attributes['reserved_stock'] = max(0, $value);
    }

    // ===============================
    // SCOPES
    // ===============================
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)
            ->where('pickup_deadline', '>', now())
            ->where('stock', '>', 0);
    }

    public function scopeUrgent($query, int $hours = 3)
    {
        return $query->where('pickup_deadline', '<=', now()->addHours($hours))
            ->where('pickup_deadline', '>', now());
    }
}