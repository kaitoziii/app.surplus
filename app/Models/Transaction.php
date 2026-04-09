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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}