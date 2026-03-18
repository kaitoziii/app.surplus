<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'original_price_snapshot',
        'price_paid',
        'discount_applied',
        'savings_amount',
        'status',
        'pickup_code',
        'picked_up_at',
        'notes',
    ];

    protected $casts = [
        'original_price_snapshot' => 'float',
        'price_paid' => 'float',
        'discount_applied' => 'float',
        'savings_amount' => 'float',
        'picked_up_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function generatePickupCode(): string
    {
        do {
            $code = 'SRPL-' . strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 5));
        } while (self::where('pickup_code', $code)->exists());

        return $code;
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}