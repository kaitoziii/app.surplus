<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 'product_id', 'quantity',
        'original_price_snapshot', 'price_paid',
        'discount_applied', 'savings_amount',
        'status', 'pickup_code', 'picked_up_at', 'notes',
    ];

    protected $casts = [
        'picked_up_at' => 'datetime',
    ];

    const STATUS_LABELS = [
        'pending'   => 'Menunggu Konfirmasi',
        'confirmed' => 'Dikonfirmasi',
        'picked_up' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    const STATUS_COLORS = [
        'pending'   => ['bg' => 'rgba(222,197,158,.25)', 'text' => '#8a6a2a'],
        'confirmed' => ['bg' => 'rgba(51,67,43,.1)',     'text' => '#33432B'],
        'picked_up' => ['bg' => 'rgba(106,120,77,.12)',  'text' => '#6A784D'],
        'cancelled' => ['bg' => 'rgba(196,134,109,.12)', 'text' => '#C4866D'],
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getStoreAttribute()
    {
        return $this->product?->store;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusColorAttribute(): array
    {
        return self::STATUS_COLORS[$this->status] ?? ['bg' => '#eee', 'text' => '#666'];
    }
}